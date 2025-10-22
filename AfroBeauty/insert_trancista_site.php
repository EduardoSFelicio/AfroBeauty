<?php

require_once('api/connect_bd.php');

// Se o usuário não selecionar nenhuma foto, é selecionado
// por padrão o ícone do trancista.
$fotoPadraoPath = "assets/foto-padrao-trancista-1x.png";
$fotoPath = $fotoPadraoPath;


if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
  $fileTmpPath = $_FILES["photo"]["tmp_name"];
  $fileName = uniqid() . "_" . basename($_FILES["photo"]["name"]);
  $fileSize = $_FILES["photo"]["size"];
  $fileType = mime_content_type($fileTmpPath);
  $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
  $targetFilePath = $uploadDir . $fileName;

  // Verifica tipo
  if (!in_array($fileType, $allowedMimeTypes) || !in_array($fileExtension, $allowedExtensions)) {
    echo json_encode(["success" => false, "message" => "Tipo de imagem não permitido. Use JPG, JPEG, PNG ou WEBP."]);
    exit;
  }

  // Verifica tamanho
  if ($fileSize > $maxFileSize) {
    echo json_encode(["success" => false, "message" => "Imagem muito grande. Máximo permitido é 8MB."]);
    exit;
  }

  // Move a imagem se for válida
  if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
    $fotoPath = $targetFilePath;
  } else {
    echo json_encode(["success" => false, "message" => "Erro ao salvar a imagem."]);
    exit;
  }
}

$cpfcnpj = filter_var($_POST['cpfcnpj'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$phone = filter_var($_POST['phone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$birth = filter_var($_POST['birth'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$gender = filter_var($_POST['gender'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Verificação de email e cpfcnpj duplicados
$checkQuery = $conn->prepare("SELECT id_trancista FROM tbl_trancistas WHERE email = ? OR cpfcnpj = ?");
$checkQuery->bind_param("ss", $email, $cpfcnpj);
$checkQuery->execute();
$checkResult = $checkQuery->get_result();

if ($checkResult->num_rows > 0) {
  echo json_encode(["success" => false, "message" => "Este e-mail ou CPF/CNPJ já está registrado."]);
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(["success" => false, "message" => "E-mail inválido."]);
  exit;
}

$birthFormatted = null;
if ($birth) {
  $dateObj = DateTime::createFromFormat("d/m/Y", $birth);
  if ($dateObj) {
    $birthFormatted = $dateObj->format("Y-m-d");
  } else {
    $birthFormatted = $birth;
  }
}

// hash básico
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Inserção no banco
$query = $conn -> prepare("INSERT INTO tbl_trancistas (cpfcnpj, email, nome, telefone, data_nascimento, genero, senha, foto_perfil) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$query -> bind_param("ssssssss", $cpfcnpj, $email, $name, $phone, $birthFormatted, $gender, $hashedPassword, $fotoPath);

if ($query -> execute()) {
  echo json_encode(["success" => true, "message" => "Usuário salvo com sucesso"]);
  header('Location: baixar.php');
} else {
  echo json_encode(["success" => false, "message" => "Erro ao salvar usuário: " . $query -> error]);
}

$conn -> close();

