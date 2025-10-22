<?php
header('Content-Type: application/json');

include 'connect_bd.php'; // Conexão com o banco de dados

$id_trancista = $_GET['id_trancista'];

$sql = "SELECT * FROM tbl_disponibilidades WHERE id_trancista = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_trancista);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['success' => true, 'data' => $data]);
?>
