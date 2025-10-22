<?php

// Registra um novo cliente, incluindo informações como:
// email, nome, telefone, data de nascimento e genero,
// senha e upload de imagem. Salva os dados via POST.

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('connect_bd.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(["success" => false, "message" => "Método não permitido"]);
  exit;
}

// Diretório para salvar as imagens.
// E por qualquer razão que se não tiver automaticamente é criado.
$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
  mkdir($uploadDir, 0777, true);
}

// Limites de upload
$maxFileSize = 8 * 1024 * 1024; // 8 MB
$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
$allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

// Se o usuário não selecionar nenhuma foto, é selecionado
// por padrão o ícone do cliente.
$fotoPadraoPath = "assets/foto-padrao-cliente-1x.png";
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

// Campos do formulário. Qalquer dúvida em relação aos nomes dos campos,
// verifique o arquivo de login_trancista.php, no rodapé está detalhado.
$email = filter_var(isset($_POST['email']) ? trim($_POST['email']) : '', FILTER_SANITIZE_EMAIL);
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$phone = filter_var(isset($_POST['phone']) ? trim($_POST['phone']) : '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$birth = filter_var(isset($_POST['birth']) ? trim($_POST['birth']) : '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$gender = filter_var(isset($_POST['gender']) ? trim($_POST['gender']) : '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(["success" => false, "message" => "E-mail inválido."]);
  exit;
}

// Verificar se o e-mail já está registrado
$emailCheckQuery = $conn->prepare("SELECT id_cliente FROM tbl_clientes WHERE email = ?");
$emailCheckQuery->bind_param("s", $email);
$emailCheckQuery->execute();
$emailCheckResult = $emailCheckQuery->get_result();

if ($emailCheckResult->num_rows > 0) {
  echo json_encode(["success" => false, "message" => "E-mail já registrado."]);
  exit;
}

// No aplicativo a data de nascimento recebe uma máscara para evitar que
// o usuário precise digitar "/". O formato de lá é o: dd/mm/yyyy, já no
//  SQL é yyyy/mm/dd, logo é necessário fazer este ajuste.

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
$hashedPassword = null;
if (!empty($password)) {
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
}

// Inserção no banco
$query = $conn -> prepare("INSERT INTO tbl_clientes (email, nome, telefone, data_nascimento, genero, senha, foto_perfil) VALUES (?, ?, ?, ?, ?, ?, ?)");
$query -> bind_param("sssssss",  $email, $name, $phone, $birthFormatted, $gender, $hashedPassword, $fotoPath);

if ($query -> execute()) {
  header('Location: ../baixar.php');
  echo json_encode(["success" => true, "message" => "Usuário salvo com sucesso"]);

} else {
  echo json_encode(["success" => false, "message" => "Erro ao salvar usuário: " . $query -> error]);
}

$conn -> close();