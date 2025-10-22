<?php
header('Content-Type: application/json');
require_once('connect_bd.php');

$id_trancista = isset($_GET['id_trancista']) ? intval($_GET['id_trancista']) : null;
$dia_semana = isset($_GET['dia_semana']) ? intval($_GET['dia_semana']) : null;

if (!$id_trancista || $dia_semana === null) {
    echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos.']);
    exit;
}

$sql = "SELECT hora_inicio, hora_fim, intervalo_min FROM tbl_disponibilidades
        WHERE id_trancista = ? AND dia_semana = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_trancista, $dia_semana);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => true, 'horarios' => []]); // sem disponibilidade
    exit;
}

$row = $result->fetch_assoc();
$hora_inicio = $row['hora_inicio'];
$hora_fim = $row['hora_fim'];
$intervalo_min = intval($row['intervalo_min']);

$horarios = [];
$inicio = new DateTime($hora_inicio);
$fim = new DateTime($hora_fim);
$id = 1;

while ($inicio <= $fim) {
  $horarios[] = [
    'id' => $id++,
    'hora_inicio' => $inicio->format('H:i'),
  ];
    $inicio->modify("+{$intervalo_min} minutes");
}

echo json_encode(['success' => true, 'horarios' => $horarios]);

