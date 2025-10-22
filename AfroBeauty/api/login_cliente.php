<?php

// Autentica o cliente com base no  e-mail e senha, usando uma
// requisição POST. Se autenticado com sucesso, retorna os dados
// do usuário em questão (e sem a senha) em formato JSON.
// Posteriormente os dados são consumidos pelo aplicativo.

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require 'vendor/autoload.php';

$chave_secreta = 'TeteuRoKimChadFafaDudu';
$expiracao_em_segundos = time() + (30 * 24 * 60 * 60); // 30 dias

require_once('connect_bd.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(["success" => false, "message" => "Método não permitido"]);
  exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$email = filter_var(trim($input['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$password = trim($input['password'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(["success" => false, "message" => "E-mail inválido."]);
  exit;
}


// Verifica se todos os campos foram preenchidos
if (empty($email) || empty($password)) {
  echo json_encode(["success" => false, "message" => "Todos os campos são obrigatórios."]);
  exit;
}

// SQL Query. Coloquei para retornar todos os dados para facilitar no aplicativo.
$query = $conn -> prepare("SELECT id_cliente AS id, nome, senha, foto_perfil AS foto, telefone, data_nascimento AS data_de_nascimento, genero, email FROM tbl_clientes WHERE email = ?");
$query -> bind_param("s", $email); // -> s de string
$query -> execute();
$result = $query -> get_result();

// Verifica se encontrou o usuário
if ($result -> num_rows === 1) {
  $user = $result -> fetch_assoc();

  // Formata data de nascimento para o formato dd/mm/yyyy
  if (!empty($user['data_de_nascimento'])) {
    $dateObj = DateTime::createFromFormat('Y-m-d', $user['data_de_nascimento']);
    if ($dateObj) {
      $user['data_de_nascimento'] = $dateObj->format('d/m/Y');
    }
  }

  if (password_verify($password, $user['senha'])) {
    unset($user['senha']);

    $payload = [
      'iss' => 'easybraids.com', // ?
      'sub' => $user['id'],
      'email' => $user['email'],
      'exp' => $expiracao_em_segundos
    ];

    $jwt = JWT::encode($payload, $chave_secreta, 'HS256');


    // Os dados aqui recebem outro nome para ficar de acordo
    // com os nomes e os tipos definidos no aplicativo,
    // verifique no rodapé deste arquivo para saber quais são
    echo json_encode([
        "success" => true,
        "message" => "Autenticação realizada com sucesso.",
        "token" => $jwt,
        "user" => [
            "id" => $user["id"],
            "name" => $user["nome"],
            "email" => $user["email"],
            "phone" => $user["telefone"],
            "birth" => $user["data_de_nascimento"],
            "gender" => $user["genero"],
            "photo" => $user["foto"],
            "role" => "cliente", // É usado para identificação no aplicativo
        ],
    ]);
  } else {
    echo json_encode(["success" => false, "message" => "Senha incorreta."]);
  }
} else {
  echo json_encode(["success" => false, "message" => "Usuário não encontrado."]);
}

$conn -> close();


/* Tipos utilizado pelo react-hook-form

export type AccountProps = {
  name?: string;
  email?: string;
  phone?: string;
  password?: string;
  birth?: string;
  passwordConfirmation?: string;
  cpfcnpj?: string;
  gender?: string;
  photo?: { uri: string } | null; //photo?: string | null;
  id?: number;
};

*/


