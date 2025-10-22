<?php
require_once('connect_bd.php');

header('Content-Type: application/json');

$id_trancista = $_GET['id_trancista'] ?? null;

if ($id_trancista) {
  $stmt = $conn->prepare("SELECT * FROM tbl_trancas WHERE id_trancista = ?");
  $stmt->bind_param("i", $id_trancista);
} else {
  $stmt = $conn->prepare("SELECT * FROM tbl_trancas");
}

$stmt->execute();
$result = $stmt->get_result();

$servicos = [];

while ($row = $result->fetch_assoc()) {
  $servicos[] = $row;
}

echo json_encode(['success' => true, 'data' => $servicos]);

$stmt->close();
$conn->close();
