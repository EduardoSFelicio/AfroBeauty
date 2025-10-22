<?php

/*
| ***************************************************************************|

| AVISO em JUL DE 2026. O CNPJ PODERÁ TER LETRAS, MEDIDAS A SEREM TOMADAS:   |
| TIRAR TODOS OS REGEX QUE TENHAM "remove tudo que não for número" OU  '/\D/'|
| AJEITAR CAMPO DO APP PARA PERMITIR TEXTO                                   |

| ***************************************************************************|
*/

// Retorna uma lista de todos os trancistas cadastrados no
// banco de dados, incluindo todos os campos, em formato JSON.

// Pra falar a verdade, este arquivo só existe pois eu estava,
// fazendo testes e não tinha autenticação feita, por ora ele
// não tem uso, mas deixe-o ai.

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('connect_bd.php');

$sql = "SELECT id_trancista AS id, cpfcnpj, nome, telefone, genero, foto_perfil AS foto, estabelecimento, residencial, qtd_servicos FROM tbl_trancistas";
$result = $conn -> query($sql);

$trancistas = [];

if ($result && $result -> num_rows > 0) {
  while ($row = $result -> fetch_assoc()) {
    $cpf_cnpj = preg_replace('/\D/', '', $row['cpfcnpj']); // remove tudo que não for número

    // Verifica se é CPF (11 dígitos)
    if (strlen( $cpf_cnpj) === 11) {
      // Exemplo: 12345678901 => 123.***.***-01
      $row['CPF_CNPJ'] = substr( $cpf_cnpj, 0, 3) . '.***.***-' . substr($cpf_cnpj, -2);
    }

    // No caso do CNPJ, mantém como está
    $trancistas[] = $row;
  }
}

echo json_encode([
  "success" => true,
  "data" => $trancistas
]);

$conn -> close();
