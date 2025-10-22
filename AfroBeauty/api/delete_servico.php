<?php
header('Content-Type: application/json');
require_once('connect_bd.php');

function response($success, $message) {
  echo json_encode(['success' => $success, 'message' => $message]);
  exit;
}

// Validação dos parâmetros obrigatórios
if (!isset($_POST['id_tranca'])) {
  response(false, 'Parâmetro "id_tranca" ausente.');
}

if (!isset($_POST['id_trancista'])) {
  response(false, 'Parâmetro "id_trancista" ausente.');
}

$id_tranca = intval($_POST['id_tranca']);
$id_trancista = intval($_POST['id_trancista']);

try {
  $query = "DELETE FROM tbl_trancas WHERE id_tranca = ? AND id_trancista = ?";
  $stmt = $conn->prepare($query);

  if (!$stmt) {
    response(false, 'Erro ao preparar query.');
  }

  $stmt->bind_param('ii', $id_tranca, $id_trancista);

  if ($stmt->execute()) {
    response(true, 'Serviço deletado com sucesso.');
  } else {
    response(false, 'Erro ao deletar serviço.');
  }
} catch (Exception $e) {
  response(false, 'Erro no servidor: ' . $e->getMessage());
}
