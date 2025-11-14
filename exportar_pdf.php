<?php
session_start();
require_once 'conexao.php';
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;

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

$html = "<h2>Relatório Financeiro - $mes/$ano</h2>";
$html .= "<table border='1' cellpadding='5' cellspacing='0' style='width:100%; border-collapse:collapse;'>";
$html .= "<tr><th>Tipo</th><th>Categoria</th><th>Descrição</th><th>Valor</th><th>Data</th></tr>";

foreach ($transacoes as $t) {
    $tipo = htmlspecialchars(ucfirst($t['tipo']));
    $categoria = htmlspecialchars(ucfirst(str_replace('_', ' ', $t['categoria'])));
    $descricao = htmlspecialchars($t['descricao']);
    $valor = number_format($t['valor'], 2, ',', '.');
    $data = date('d/m/Y', strtotime($t['data']));

    $html .= "<tr>
                <td>$tipo</td>
                <td>$categoria</td>
                <td>$descricao</td>
                <td>R$ $valor</td>
                <td>$data</td>
              </tr>";
}
$html .= "</table>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("relatorio_$mes-$ano.pdf", ["Attachment" => true]);
