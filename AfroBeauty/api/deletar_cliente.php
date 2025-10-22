<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('connect_bd.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(["success" => false, "message" => "Método não permitido"]);
  exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);

if ($id <= 0) {
  echo json_encode(["success" => false, "message" => "ID inválido"]);
  exit;
}

// 1. Buscar os dados do cliente
$selectQuery = $conn->prepare("SELECT * FROM tbl_clientes WHERE id_cliente = ?");
$selectQuery->bind_param("i", $id);
$selectQuery->execute();
$result = $selectQuery->get_result();

if ($result->num_rows === 0) {
  echo json_encode(["success" => false, "message" => "Cliente não encontrado"]);
  exit;
}

$cliente = $result->fetch_assoc();

// 2. Definir caminhos
$originalFotoPath = $cliente['foto_perfil'];
$newFotoPath = $originalFotoPath; // por padrão, mantém o mesmo

$uploadDir = 'uploads/';
$deletedDir = 'uploads_deletados/';
$assetsDir = 'assets/';

// Cria pasta de deletados se não existir
if (!is_dir($deletedDir)) {
  mkdir($deletedDir, 0777, true);
}

// Verifica se é uma imagem do uploads e não de assets
if (
  !empty($originalFotoPath) &&
  strpos($originalFotoPath, $uploadDir) === 0 &&
  strpos($originalFotoPath, $assetsDir) !== 0 &&
  file_exists($originalFotoPath)
) {
  $fileName = basename($originalFotoPath);
  $newFotoPath = $deletedDir . $fileName;

  // Move a imagem
  if (!rename($originalFotoPath, $newFotoPath)) {
    echo json_encode(["success" => false, "message" => "Erro ao mover a imagem de perfil."]);
    exit;
  }
  // A imagem original em uploads/ já foi movida, então não há necessidade de deletar manualmente
}

// 3. Inserir na tabela de deletados com o novo caminho da imagem
$insertQuery = $conn->prepare("
  INSERT INTO tbl_clientes_deletados
  (id_cliente, email, nome, telefone, data_nascimento, genero, senha, foto_perfil, endereco, e_google, data_criacao, favorito_tranca, favorito_trancista)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$insertQuery->bind_param(
  "issssssssisss",
  $cliente['id_cliente'],
  $cliente['email'],
  $cliente['nome'],
  $cliente['telefone'],
  $cliente['data_nascimento'],
  $cliente['genero'],
  $cliente['senha'],
  $newFotoPath,
  $cliente['endereco'],
  $cliente['e_google'],
  $cliente['data_criacao'],
  $cliente['favorito_tranca'],
  $cliente['favorito_trancista']
);


if (!$insertQuery->execute()) {
  echo json_encode(["success" => false, "message" => "Erro ao mover para deletados: " . $insertQuery->error]);
  exit;
}

// 4. Deletar da tabela original
$deleteQuery = $conn->prepare("DELETE FROM tbl_clientes WHERE id_cliente = ?");
$deleteQuery->bind_param("i", $id);

if ($deleteQuery->execute()) {
  echo json_encode(["success" => true, "message" => "Conta deletada com sucesso"]);
} else {
  echo json_encode(["success" => false, "message" => "Erro ao deletar conta: " . $deleteQuery->error]);
}

$conn->close();

