<?php

require_once 'vendor/autoload.php';

use Google\Client as Google_Client;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once('connect_bd.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(["success" => false, "message" => "Método não permitido"]);
  exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['idToken'])) {
  echo json_encode(["success" => false, "message" => "Token não fornecido"]);
  exit;
}

$idToken = $data['idToken'];
//file_put_contents('token.txt', $idToken);

// Configure seu client_id Google correto
$client = new Google_Client(['client_id' => '732961100516-d0e21d70ve2hfiiti5ivkld5a4euic9a.apps.googleusercontent.com']);

$payload = $client->verifyIdToken($idToken);

if (!$payload) {
  echo json_encode(["success" => false, "message" => "Token inválido"]);
  exit;
}

$email = strtolower(trim($payload['email'] ?? ''));

if (!$email) {
  echo json_encode(["success" => false, "message" => "E-mail não encontrado no token"]);
  exit;
}

// Buscar usuário no banco
$stmt = $conn->prepare("SELECT id_cliente AS id, email, nome, foto_perfil AS foto, telefone, data_nascimento as data_de_nascimento, genero, e_google FROM tbl_clientes WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
  echo json_encode(["success" => false, "message" => "Usuário não cadastrado"]);
  exit;
}

// Formata data de nascimento para o formato dd/mm/yyyy
if (!empty($user['data_de_nascimento'])) {
  $dateObj = DateTime::createFromFormat('Y-m-d', $user['data_de_nascimento']);
  if ($dateObj) {
    $user['data_de_nascimento'] = $dateObj->format('d/m/Y');
  }
}

$secretKey = 'DuduFafaChadKimRoTeteu';

$issuedAt = time();
$expiration = $issuedAt + 3600 * 24 * 7;

$payloadJWT = [
  'iat' => $issuedAt,
  'exp' => $expiration,
  'sub' => $user['id'],
  'email' => $user['email'],
  'name' => $user['nome'],
];

// Gera o token JWT
$jwt = JWT::encode($payloadJWT, $secretKey, 'HS256');

echo json_encode([
  "success" => true,
  "user" => [
    "id" => $user["id"],
    "name" => $user["nome"],
    "email" => $user["email"],
    "phone" => $user["telefone"],
    "birth" => $user["data_de_nascimento"],
    "gender" => $user["genero"],
    "photo" => $user["foto"],
    "isGoogle" => $user["e_google"],
    "role" => "cliente",
  ],
  "token" => $jwt
]);
