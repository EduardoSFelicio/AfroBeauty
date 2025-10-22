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

// 1. Buscar os dados do trancista
$selectQuery = $conn->prepare("SELECT * FROM tbl_trancistas WHERE id_trancista = ?");
$selectQuery->bind_param("i", $id);
$selectQuery->execute();
$result = $selectQuery->get_result();

if ($result->num_rows === 0) {
  echo json_encode(["success" => false, "message" => "Trancista não encontrado"]);
  exit;
}

$trancista = $result->fetch_assoc();

// 2. Definir caminhos
$originalFotoPath = $trancista['foto_perfil'];
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
  INSERT INTO tbl_trancistas_deletados
  (id_trancista, nome, email, senha, data_nascimento, genero, cpfcnpj, telefone, endereco, foto_perfil, data_criacao, qtd_servicos, estabelecimento, residencial, media_notas, galeria, link)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$insertQuery->bind_param(
  "issssssssssississ",
  $trancista['id_trancista'],// i
  $trancista['nome'], // s
  $trancista['email'], // s
  $trancista['senha'], // s
  $trancista['data_nascimento'], // s
  $trancista['genero'], // s
  $trancista['cpfcnpj'], // s
  $trancista['telefone'], // s
  $trancista['endereco'], // s
  $newFotoPath, // s
  $trancista['data_criacao'], // s
  $trancista['qtd_servicos'], // i
  $trancista['estabelecimento'], // s
  $trancista['residencial'], // s
  $trancista['media_notas'], // i -> ou float
  $trancista['galeria'], // s
  $trancista['link'] // s
);


if (!$insertQuery->execute()) {
  echo json_encode(["success" => false, "message" => "Erro ao mover para deletados: " . $insertQuery->error]);
  exit;
}

// 4. Deletar da tabela original
$deleteQuery = $conn->prepare("DELETE FROM tbl_trancistas WHERE id_trancista = ?");
$deleteQuery->bind_param("i", $id);

if ($deleteQuery->execute()) {
  echo json_encode(["success" => true, "message" => "Conta deletada com sucesso"]);
} else {
  echo json_encode(["success" => false, "message" => "Erro ao deletar conta: " . $deleteQuery->error]);
}

$conn->close();

