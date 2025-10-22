<?php

require_once 'vendor/autoload.php';
use Google\Client as Google_Client;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once('connect_bd.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(["success" => false, "message" => "Método não permitido"]);
  exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$nascimento = !empty($data['nascimento']) ? $data['nascimento'] : null;
$genero = !empty($data['genero']) ? $data['genero'] : null;
$e_google = !empty($data['e_google']) ? $data['e_google'] : null;

if (empty($data['idToken'])) {
  echo json_encode(["success" => false, "message" => "Token não fornecido"]);
  exit;
}

$idToken = $data['idToken'];

$client = new Google_Client(['client_id' => '732961100516-d0e21d70ve2hfiiti5ivkld5a4euic9a.apps.googleusercontent.com']);

$payload = $client->verifyIdToken($idToken);

function saveImageLocally($url, $uploadDir = 'uploads/') {
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

  $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
  if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
    $ext = 'jpg'; // fallback
  }
  $fileName = uniqid() . "_foto_perfil." . $ext;
  $targetFilePath = $uploadDir . $fileName;

  $content = @file_get_contents($url);
  if ($content === false) {
    return 'assets/foto-padrao-cliente-1x.png'; // fallback se falhar
  }

  file_put_contents($targetFilePath, $content);

  // Retorna caminho relativo para salvar no banco
  return $targetFilePath;
}

if ($payload) {
  $email = $payload['email'];
  $name = $payload['name'] ?? '';
  $photoGoogleUrl = $payload['picture'] ?? null;
  $photo = $photoGoogleUrl ? saveImageLocally($photoGoogleUrl) : 'assets/foto-padrao-cliente-1x.png';

  // Verifica se o e-mail já está registrado no banco
  $stmt = $conn->prepare("SELECT id_cliente, email, nome, foto_perfil FROM tbl_clientes WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  // Se o e-mail já estiver registrado, retorna mensagem de erro
  if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "E-mail já registrado."]);
    exit;
  }

  // Se o e-mail não estiver registrado, insere novo cliente
  $insert = $conn->prepare("INSERT INTO tbl_clientes (email, nome, foto_perfil, data_nascimento, genero, e_google) VALUES (?, ?, ?, ?, ?, ?)");
  $insert->bind_param("sssssi", $email, $name, $photo, $nascimento, $genero, $e_google);

  if ($insert->execute()) {
    $userId = $insert->insert_id;
    $user = [
      "id_cliente" => $userId,
      "email" => $email,
      "nome" => $name,
      "foto_perfil" => $photo
    ];
    echo json_encode(["success" => true, "user" => $user]);
  } else {
    echo json_encode(["success" => false, "message" => "Erro ao inserir usuário"]);
  }
} else {
  echo json_encode(["success" => false, "message" => "Token inválido"]);
}
