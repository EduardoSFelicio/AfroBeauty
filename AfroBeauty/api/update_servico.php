<?php
header('Content-Type: application/json');

// Conexão com o banco
require_once('connect_bd.php');

// Função para resposta JSON
function response($success, $message) {
  echo json_encode(['success' => $success, 'message' => $message]);
  exit;
}

// Verifica se os dados obrigatórios foram enviados
if (
  !isset($_POST['titulo']) ||
  !isset($_POST['preco']) ||
  !isset($_POST['id_trancista']) ||
  !isset($_POST['id_tranca'])
) {
  response(false, 'Parâmetros ausentes.');
}

$titulo = $_POST['titulo'];
$preco = $_POST['preco'];
$descricao = isset($_POST['descricao']) ? $_POST['descricao'] : null;
$id_tranca = $_POST['id_tranca'];
$id_trancista = $_POST['id_trancista'];

$fotoPath = null;

// Se foi enviada uma nova imagem, salva ela
if (isset($_FILES['foto_tranca']) && $_FILES['foto_tranca']['error'] === UPLOAD_ERR_OK) {
  $foto = $_FILES['foto_tranca'];

  $uploadDir = 'uploads/';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

  $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);
  $filename = uniqid('servico_') . '.' . $ext;
  $destPath = $uploadDir . $filename;

  if (move_uploaded_file($foto['tmp_name'], $destPath)) {
    $fotoPath = $destPath;
  } else {
    response(false, 'Erro ao salvar imagem.');
  }
}

// Prepara a query SQL
try {
  if ($fotoPath) {
    $query = "UPDATE tbl_trancas SET titulo = ?, preco = ?, descricao = ?, foto_tranca = ? WHERE id_tranca = ? AND id_trancista = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sdssii', $titulo, $preco, $descricao, $fotoPath, $id_tranca, $id_trancista);
  } else {
    $query = "UPDATE tbl_trancas SET titulo = ?, preco = ?, descricao = ? WHERE id_tranca = ? AND id_trancista = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sdsii', $titulo, $preco, $descricao, $id_tranca, $id_trancista);
  }

  if ($stmt->execute()) {
    response(true, 'Serviço atualizado com sucesso.');
  } else {
    response(false, 'Erro ao atualizar serviço.');
  }
} catch (Exception $e) {
  response(false, 'Erro no servidor: ' . $e->getMessage());
}
