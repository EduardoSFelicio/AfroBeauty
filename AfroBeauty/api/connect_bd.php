<?php

/*
| ***************************************************************************|

| AVISO em JUL DE 2026. O CNPJ PODERÁ TER LETRAS, MEDIDAS A SEREM TOMADAS:   |
| TIRAR TODOS OS REGEX QUE TENHAM "remove tudo que não for número" OU  '/\D/'|
| AJEITAR CAMPO DO APP PARA PERMITIR TEXTO                                   |

| ***************************************************************************|
*/


// $host = "localhost";
// $user = "root";
// $password = "";
// $database = "easybraids";

/*  

Os dados de conexão foram modificados para fazer conexão com o banco de dados do provedor
ASS: Dudu


*/

$host = "localhost";
$user = "eduprogram_afrobeauty";
$password = "(2D)fNGKrE@E";
$database = "eduprogram_db_afrobeauty";


// o PDO eu não sei fazer e acabei nem procurando sobre,
// fiz por mysqli mesmo - apesar que eu li em algum canto
// que não era seguro - mas sei não da veracidade da informação.
$conn = new mysqli($host, $user, $password, $database);
$conn->set_charset('utf8mb4');

if ($conn -> connect_error) {
  http_response_code(500);
  die(json_encode([
    "success" => false,
    "message" => "Erro na conexão: " . $conn -> connect_error
  ]));
}

