<?php
require_once('connect_bd.php');
header('Content-Type: application/json');

$id_trancista = $_GET['id_trancista'] ?? null;
$data = $_GET['data'] ?? null;
$id_tranca = $_GET['id_tranca'] ?? null;

if (!$id_trancista || !$data || !$id_tranca) {
  echo json_encode(['success' => false, 'message' => 'Parâmetros obrigatórios não informados.']);
  exit;
}

try {
  // Verifica se o trancista está bloqueado nesse dia
  $stmt = $conn->prepare("SELECT 1 FROM tbl_bloqueios WHERE id_trancista = ? AND data_bloqueada = ?");
  $stmt->bind_param("is", $id_trancista, $data);
  $stmt->execute();
  $stmt->store_result();
  if ($stmt->num_rows > 0) {
    echo json_encode(['success' => true, 'horarios_disponiveis' => []]); // Dia bloqueado
    exit;
  }
  $stmt->close();

  // Obtem o dia da semana (0=domingo ... 6=sábado)
  $dia_semana = date('w', strtotime($data));

  // Pega a disponibilidade semanal
  $stmt = $conn->prepare("SELECT hora_inicio, hora_fim, intervalo_min FROM tbl_disponibilidades WHERE id_trancista = ? AND dia_semana = ?");
  $stmt->bind_param("ii", $id_trancista, $dia_semana);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    echo json_encode(['success' => true, 'horarios_disponiveis' => []]); // Trancista não atende nesse dia
    exit;
  }

  $disp = $result->fetch_assoc();
  $hora_inicio = $disp['hora_inicio'];
  $hora_fim = $disp['hora_fim'];
  $intervalo = $disp['intervalo_min'];
  $stmt->close();

  // Pega a duração da trança
  $stmt = $conn->prepare("SELECT duracao_min FROM tbl_trancas WHERE id_tranca = ?");
  $stmt->bind_param("i", $id_tranca);
  $stmt->execute();
  $result = $stmt->get_result();
  $tranca = $result->fetch_assoc();
  $duracao = $tranca['duracao_min'];
  $stmt->close();

  // Pega agendamentos existentes
  $stmt = $conn->prepare("SELECT hora, duracao_min FROM tbl_agendamentos WHERE id_trancista = ? AND data = ? AND status != 'cancelado'");
  $stmt->bind_param("is", $id_trancista, $data);
  $stmt->execute();
  $result = $stmt->get_result();

  $horarios_ocupados = [];
  while ($row = $result->fetch_assoc()) {
    $inicio = strtotime($row['hora']);
    $fim = $inicio + ($row['duracao_min'] * 60);
    $horarios_ocupados[] = ['inicio' => $inicio, 'fim' => $fim];
  }
  $stmt->close();

  // Gera os horários disponíveis com base nos intervalos
  $hora_atual = strtotime("$data $hora_inicio");
  $hora_limite = strtotime("$data $hora_fim");
  $horarios_disponiveis = [];

  while (($hora_atual + ($duracao * 60)) <= $hora_limite) {
    $hora_fim_atendimento = $hora_atual + ($duracao * 60);
    $conflito = false;

    foreach ($horarios_ocupados as $ocupado) {
      if (
        ($hora_atual < $ocupado['fim']) &&
        ($hora_fim_atendimento > $ocupado['inicio'])
      ) {
        $conflito = true;
        break;
      }
    }

    if (!$conflito) {
      $horarios_disponiveis[] = date('H:i', $hora_atual);
    }

    $hora_atual += ($intervalo * 60);
  }

  echo json_encode(['success' => true, 'horarios_disponiveis' => $horarios_disponiveis]);

} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => 'Erro ao processar requisição.']);
}

$conn->close();
