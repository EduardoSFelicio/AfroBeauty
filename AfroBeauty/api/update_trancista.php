<?php

// Atualiza os dados de um trancista já existente

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('connect_bd.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(["success" => false, "message" => "Método não permitido"]);
  exit;
}

// Diretório de upload
$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
  mkdir($uploadDir, 0777, true);
}

$maxFileSize = 8 * 1024 * 1024; // 8 MB
$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
$allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

// Dados recebidos
$id = intval($_POST['id'] ?? 0);
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$name = trim($_POST['name'] ?? '');
$cpfcnpj = filter_var(trim($_POST['cpfcnpj'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$phone = filter_var(trim($_POST['phone'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$birth = filter_var(trim($_POST['birth'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$gender = filter_var(trim($_POST['gender'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$hasEstablishment = trim($_POST['hasEstablishment'] ?? '');
$doesResidential = trim($_POST['doesResidential'] ?? '');

// Validação básica
if ($id <= 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(["success" => false, "message" => "ID ou e-mail inválido."]);
  exit;
}

// Verifica se o e-mail já está cadastrado (excluindo o trancista atual)
$sqlEmailCheck = "SELECT COUNT(*) FROM tbl_trancistas WHERE email = ? AND id_trancista != ?";
$stmtEmailCheck = $conn->prepare($sqlEmailCheck);
$stmtEmailCheck->bind_param("si", $email, $id);
$stmtEmailCheck->execute();
$stmtEmailCheck->bind_result($emailExists);
$stmtEmailCheck->fetch();
$stmtEmailCheck->close();

if ($emailExists > 0) {
  echo json_encode(["success" => false, "message" => "Este e-mail já está em uso."]);
  exit;
}

// Verifica se o cpfcnpj já está cadastrado (excluindo o trancista atual)
$sqCpfCnpjCheck = "SELECT COUNT(*) FROM tbl_trancistas WHERE cpfcnpj = ? AND id_trancista != ?";
$sqCpfCnpjCheck = $conn->prepare($sqCpfCnpjCheck);
$sqCpfCnpjCheck->bind_param("si", $cpfcnpj, $id);
$sqCpfCnpjCheck->execute();
$sqCpfCnpjCheck->bind_result($cpfcnpjExists);
$sqCpfCnpjCheck->fetch();
$sqCpfCnpjCheck->close();

if ($cpfcnpjExists > 0) {
  echo json_encode(["success" => false, "message" => "Este CPF ou CNPJ já está em uso."]);
  exit;
}

// Formata a data
$birthFormatted = null;
if ($birth) {
  $dateObj = DateTime::createFromFormat("d/m/Y", $birth);
  if ($dateObj) {
    $birthFormatted = $dateObj->format("Y-m-d");
  }
}

// Verifica se um novo arquivo foi enviado
$fotoPath = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
  $fileTmpPath = $_FILES["photo"]["tmp_name"];
  $fileName = uniqid() . "_" . basename($_FILES["photo"]["name"]);
  $fileSize = $_FILES["photo"]["size"];
  $fileType = mime_content_type($fileTmpPath);
  $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
  $targetFilePath = $uploadDir . $fileName;

  if (!in_array($fileType, $allowedMimeTypes) || !in_array($fileExtension, $allowedExtensions)) {
    echo json_encode(["success" => false, "message" => "Tipo de imagem não permitido."]);
    exit;
  }

  if ($fileSize > $maxFileSize) {
    echo json_encode(["success" => false, "message" => "Imagem muito grande. Máximo permitido é 8MB."]);
    exit;
  }

  if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
    // Buscar imagem anterior
    $sqlSelect = "SELECT foto_perfil FROM tbl_trancistas WHERE id_trancista = ?";
    $stmtSelect = $conn->prepare($sqlSelect);
    $stmtSelect->bind_param("i", $id);
    $stmtSelect->execute();
    $stmtSelect->bind_result($oldPhotoPath);
    $stmtSelect->fetch();
    $stmtSelect->close();

    // Exclui imagem antiga se não for da pasta assets
    if ($oldPhotoPath && strpos($oldPhotoPath, 'assets/') !== 0 && file_exists($oldPhotoPath)) {
      unlink($oldPhotoPath);
    }

    $fotoPath = $targetFilePath;
  } else {
    echo json_encode(["success" => false, "message" => "Erro ao salvar a imagem."]);
    exit;
  }
}

// Atualiza com ou sem foto nova
if ($fotoPath) {
  $sql = "UPDATE tbl_trancistas
          SET nome = ?, email = ?, telefone = ?, data_nascimento = ?, genero = ?, foto_perfil = ?, cpfcnpj = ?, estabelecimento = ?
          WHERE id_trancista = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssssssssi", $name, $email, $phone, $birthFormatted, $gender, $fotoPath, $cpfcnpj, $hasEstablishment, $doesResidential, $id);
} else {
  $sql = "UPDATE tbl_trancistas
          SET nome = ?, email = ?, telefone = ?, data_nascimento = ?, genero = ?, cpfcnpj = ?, estabelecimento = ?, residencial = ?
          WHERE id_trancista = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssssssi", $name, $email, $phone, $birthFormatted, $gender, $cpfcnpj, $hasEstablishment, $doesResidential, $id);
}

if ($stmt->execute()) {
  echo json_encode(["success" => true, "message" => "Perfil do trancista atualizado com sucesso."]);
} else {
  echo json_encode(["success" => false, "message" => "Erro ao atualizar: " . $stmt->error]);
}

$conn->close();
