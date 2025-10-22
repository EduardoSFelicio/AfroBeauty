<?php
require_once('connect_bd.php');
header('Content-Type: application/json');

// Lê o JSON recebido
$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !is_array($input)) {
  echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
  exit;
}

$id_trancista = $input[0]['id_trancista'] ?? null;

if (!$id_trancista) {
  echo json_encode(['success' => false, 'message' => 'ID do trancista não fornecido']);
  exit;
}

// Validação: Verifica se hora_fim é maior que hora_inicio
foreach ($input as $item) {
  $hora_inicio = new DateTime($item['hora_inicio']);
  $hora_fim = new DateTime($item['hora_fim']);

  if ($hora_fim <= $hora_inicio) {
    echo json_encode(['success' => false, 'message' => 'Hora de fim não pode ser anterior ou igual à hora de início']);
    exit;
  }
}

// Remove as disponibilidades anteriores
$stmtDelete = $conn->prepare("DELETE FROM tbl_disponibilidades WHERE id_trancista = ?");
$stmtDelete->bind_param("i", $id_trancista);
$stmtDelete->execute();
$stmtDelete->close();

// Prepara inserção
$stmtInsert = $conn->prepare("INSERT INTO tbl_disponibilidades (id_trancista, dia_semana, hora_inicio, hora_fim, intervalo_min) VALUES (?, ?, ?, ?, ?)");

foreach ($input as $item) {
  $stmtInsert->bind_param(
    "iissi",
    $item['id_trancista'],
    $item['dia_semana'],
    $item['hora_inicio'],
    $item['hora_fim'],
    $item['intervalo_min']
  );
  $stmtInsert->execute();
}

$stmtInsert->close();
$conn->close();

echo json_encode(['success' => true]);
