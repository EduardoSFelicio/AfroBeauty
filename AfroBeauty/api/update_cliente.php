<?php

// Atualiza os dados de um cliente já existente, incluindo:
// nome, email, telefone, data de nascimento, gênero e foto de perfil.

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
$phone = filter_var(trim($_POST['phone'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$birth = filter_var(trim($_POST['birth'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$gender = filter_var(trim($_POST['gender'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Validação básica
if ($id <= 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(["success" => false, "message" => "ID ou e-mail inválido."]);
  exit;
}

// Verifica se o e-mail já está em uso por outro cliente
$sqlCheckEmail = "SELECT id_cliente FROM tbl_clientes WHERE email = ? AND id_cliente != ?";
$stmtCheck = $conn->prepare($sqlCheckEmail);
$stmtCheck->bind_param("si", $email, $id);
$stmtCheck->execute();
$stmtCheck->store_result();

if ($stmtCheck->num_rows > 0) {
  echo json_encode(["success" => false, "message" => "Este e-mail já está cadastrado."]);
  $stmtCheck->close();
  $conn->close();
  exit;
}
$stmtCheck->close();

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
    echo json_encode(value: ["success" => false, "message" => "Imagem muito grande. Máximo permitido é 8MB."]);
    exit;
  }

  if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
    // Buscar imagem anterior
    $sqlSelect = "SELECT foto_perfil FROM tbl_clientes WHERE id_cliente = ?";
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
  $sql = "UPDATE tbl_clientes
          SET nome = ?, email = ?, telefone = ?, data_nascimento = ?, genero = ?, foto_perfil = ?
          WHERE id_cliente = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssssi", $name, $email, $phone, $birthFormatted, $gender, $fotoPath, $id);
} else {
  $sql = "UPDATE tbl_clientes
          SET nome = ?, email = ?, telefone = ?, data_nascimento = ?, genero = ?
          WHERE id_cliente = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssssi", $name, $email, $phone, $birthFormatted, $gender, $id);
}

if ($stmt->execute()) {
  echo json_encode(["success" => true, "message" => "Perfil atualizado com sucesso."]);
} else {
  echo json_encode(["success" => false, "message" => "Erro ao atualizar: " . $stmt->error]);
}

$conn->close();
