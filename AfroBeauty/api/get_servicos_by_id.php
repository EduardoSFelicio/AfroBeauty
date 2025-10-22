<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('connect_bd.php');

if (!isset($_GET['id'])) {
  echo json_encode([
    "success" => false,
    "message" => "ID não fornecido"
  ]);
  exit;
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM tbl_trancas WHERE id_trancista = $id";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
  // Aqui está a correção
  $trancas = $result->fetch_all(MYSQLI_ASSOC);

  echo json_encode([
    "success" => true,
    "data" => $trancas
  ]);
} else {
  echo json_encode([
    "success" => false,
    "message" => "Tranças não encontradas"
  ]);
}

$conn->close();
