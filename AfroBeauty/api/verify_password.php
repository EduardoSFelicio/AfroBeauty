<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('connect_bd.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(["success" => false, "message" => "Método não permitido"]);
  exit;
}

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['id']) || !isset($input['password']) || !isset($input['role'])) {
  echo json_encode(["success" => false, "message" => "ID, senha ou role não fornecidos."]);
  exit;
}

$userId = intval($input['id']);
$password = trim($input['password']);
$role = $input['role'];

// Determinar a tabela de acordo com o role
$table = ($role === 'cliente') ? 'tbl_clientes' : 'tbl_trancistas';
$idColumn = ($role === 'cliente') ? 'id_cliente' : 'id_trancista';

$query = $conn->prepare("SELECT senha FROM $table WHERE $idColumn = ?");
$query->bind_param("i", $userId);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();

  if (password_verify($password, $user['senha'])) {
    echo json_encode(['success' => true, 'message' => "Senha correta."]);
  } else {
    echo json_encode(["success" => false, "message" => "Senha incorreta."]);
  }
} else {
  echo json_encode(["success" => false, "message" => "Usuário não encontrado."]);
}
