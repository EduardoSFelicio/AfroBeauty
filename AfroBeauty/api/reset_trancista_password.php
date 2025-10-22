<?php

/* ATUALMENTE NÃO ESTÁ IMPLEMENTADO */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('connect_bd.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(["success" => false, "message" => "Método não permitido"]);
  exit;
}

$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(["success" => false, "message" => "E-mail inválido."]);
  exit;
}

$query = $conn -> prepare("SELECT id_trancista FROM tbl_trancistas WHERE email = ?");
$query -> bind_param("s", $email);
$query -> execute();
$result = $query -> get_result();

if ($result->num_rows === 1) {
  // Aqui você pode gerar um código temporário e salvar no banco ou enviar link por e-mail
  $codigo = rand(100000, 999999); // ou usar um token
  // salvar esse código com validade, enviar por e-mail, etc

  echo json_encode([
    "success" => true,
    "message" => "Código de verificação enviado para o e-mail."
  ]);
} else {
  echo json_encode([
    "success" => false,
    "message" => "E-mail não encontrado."
  ]);
}

$conn -> close();
