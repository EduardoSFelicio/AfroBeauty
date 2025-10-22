<?php
require_once('connect_bd.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $preco = $_POST['preco'] ?? '';
    $id_trancista = $_POST['id_trancista'] ?? '';

    // Verifica se a imagem foi enviada
    if (isset($_FILES['foto_tranca']) && $_FILES['foto_tranca']['error'] === UPLOAD_ERR_OK) {
        $nomeArquivo = uniqid() . '_' . $_FILES['foto_tranca']['name'];
        $caminho = 'uploads/' . $nomeArquivo;
        move_uploaded_file($_FILES['foto_tranca']['tmp_name'], $caminho);
    } else {
        // Caminho padrão da imagem
        $caminho = 'assets/icone-chave-inglesa-4x.png';
    }

    $sql = "INSERT INTO tbl_trancas (titulo, descricao, preco, foto_tranca, id_trancista)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $titulo, $descricao, $preco, $caminho, $id_trancista);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Serviço cadastrado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar serviço.']);
    }

    $stmt->close();
    $conn->close();
}
