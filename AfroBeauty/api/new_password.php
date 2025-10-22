<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('connect_bd.php'); // Conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(["success" => false, "message" => "Método não permitido"]);
  exit;
}

// Lê os dados recebidos
$input = json_decode(file_get_contents("php://input"), true);

// Valida se os dados necessários estão presentes
if (!isset($input['id']) || !isset($input['password']) || !isset($input['role'])) {
  echo json_encode(["success" => false, "message" => "ID, senha ou role não fornecidos."]);
  exit;
}

$userId = intval($input['id']);
$password = trim($input['password']);
$role = $input['role'];

// Verifica se a senha e o ID são válidos
if (empty($password) || $userId <= 0) {
  echo json_encode(["success" => false, "message" => "ID ou senha inválidos."]);
  exit;
}

// Cria o hash da senha
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Atualiza a senha de acordo com o role
if ($role === 'cliente') {
  // Para 'cliente', atualiza na tabela 'tbl_clientes'
  $query = $conn->prepare("UPDATE tbl_clientes SET senha = ? WHERE id_cliente = ?");
  $query->bind_param("si", $hashedPassword, $userId);
} elseif ($role === 'trancista') {
  // Para 'trancista', atualiza na tabela 'tbl_trancistas'
  $query = $conn->prepare("UPDATE tbl_trancistas SET senha = ? WHERE id_trancista = ?");
  $query->bind_param("si", $hashedPassword, $userId);
} else {
  // Caso o 'role' não seja válido
  echo json_encode(["success" => false, "message" => "Role inválido."]);
  exit;
}

if ($query->execute()) {
  if ($query->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Senha atualizada com sucesso."]);
  } else {
    echo json_encode(["success" => false, "message" => "Nenhuma linha foi alterada. Talvez a senha seja igual à anterior."]);
  }
} else {
  echo json_encode(["success" => false, "message" => "Erro ao atualizar senha."]);
}

/*
// Executa a consulta de atualização
if ($query->execute()) {
  echo json_encode(["success" => true, "message" => "Senha atualizada com sucesso."]);
} else {
  echo json_encode(["success" => false, "message" => "Erro ao atualizar senha."]);
}*/

