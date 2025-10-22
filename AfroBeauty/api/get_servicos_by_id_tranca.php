<?php
require_once('connect_bd.php');
header('Content-Type: application/json');

$id_tranca = $_GET['id_tranca'] ?? null;

if (!$id_tranca) {
  echo json_encode(['success' => false, 'message' => 'ID não fornecido']);
  exit;
}

$stmt = $conn->prepare("SELECT * FROM tbl_trancas WHERE id_tranca = ?");
$stmt->bind_param("i", $id_tranca);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $data = $result->fetch_assoc();
  echo json_encode(['success' => true, 'data' => $data]);
} else {
  echo json_encode(['success' => false, 'message' => 'Serviço não encontrado']);
}

$stmt->close();
$conn->close();
