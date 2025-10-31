<?php
session_start();
require_once 'conexao.php';
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$usuarioId = $_SESSION['usuario_id'] ?? null;
if (!$usuarioId) exit;

$mes = str_pad($_GET['mes'] ?? date('m'), 2, '0', STR_PAD_LEFT);
$ano = $_GET['ano'] ?? date('Y');
$tipo = $_GET['tipo'] ?? 'todos';

$query = "SELECT * FROM transacoes WHERE usuario_id = ? AND MONTH(data) = ? AND YEAR(data) = ?";
$params = [$usuarioId, $mes, $ano];
if ($tipo !== 'todos') {
    $query .= " AND tipo = ?";
    $params[] = $tipo;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Relatorio_" . $mes . "_" . $ano);

// Cabeçalhos
$sheet->fromArray(['Tipo', 'Categoria', 'Descrição', 'Valor', 'Data'], NULL, 'A1');

// Dados
$row = 2;
foreach ($transacoes as $t) {
    $tipo = ucfirst($t['tipo']);
    $categoria = ucfirst(str_replace('_', ' ', $t['categoria']));
    $descricao = $t['descricao'];
    $valor = (float)$t['valor'];
    $data = date('d/m/Y', strtotime($t['data']));

    $sheet->setCellValue("A$row", $tipo);
    $sheet->setCellValue("B$row", $categoria);
    $sheet->setCellValue("C$row", $descricao);
    $sheet->setCellValue("D$row", $valor);
    $sheet->setCellValue("E$row", $data);
    $row++;
}

// Exportar
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=relatorio_$mes-$ano.xlsx");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
