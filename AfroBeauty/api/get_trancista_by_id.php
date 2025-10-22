<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('connect_bd.php');

// Verifica se o ID foi passado
if (!isset($_GET['id'])) {
  echo json_encode([
    "success" => false,
    "message" => "ID não fornecido"
  ]);
  exit;
}

$id = intval($_GET['id']);

$sql = "SELECT id_trancista AS id, nome, telefone, genero, foto_perfil AS foto, estabelecimento, residencial, qtd_servicos
        FROM tbl_trancistas
        WHERE id_trancista = $id";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
  $trancista = $result->fetch_assoc();

  echo json_encode([
    "success" => true,
    "data" => $trancista
  ]);
} else {
  echo json_encode([
    "success" => false,
    "message" => "Trancista não encontrado"
  ]);
}

$conn->close();
