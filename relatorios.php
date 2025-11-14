<?php
session_start();
require_once 'conexao.php';

$paginaAtual = 'relatorios';
$tituloPagina = 'Relatórios Financeiros';

$usuarioId = $_SESSION['usuario_id'] ?? null;
if (!$usuarioId) {
    header('Location: index.php');
    exit;
}

// Filtros com validação
$mes = isset($_GET['mes']) && is_numeric($_GET['mes']) && $_GET['mes'] >= 1 && $_GET['mes'] <= 12 ? $_GET['mes'] : date('m');
$ano = isset($_GET['ano']) && is_numeric($_GET['ano']) && $_GET['ano'] >= 2025 && $_GET['ano'] <= 2100 ? $_GET['ano'] : date('Y');
$tipo = $_GET['tipo'] ?? 'todos';
$tipo = in_array($tipo, ['receita', 'despesa', 'investimento', 'todos']) ? $tipo : 'todos';

// Consulta
$query = "SELECT * FROM transacoes WHERE usuario_id = ? AND MONTH(data) = ? AND YEAR(data) = ?";
$params = [$usuarioId, $mes, $ano];
if ($tipo !== 'todos') {
    $query .= " AND tipo = ?";
    $params[] = $tipo;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupamento por categoria
$categorias = [];
foreach ($transacoes as $t) {
    $cat = isset($t['categoria']) ? ucfirst(str_replace('_', ' ', $t['categoria'])) : 'Outros';
    $categorias[$cat] = ($categorias[$cat] ?? 0) + floatval($t['valor']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($tituloPagina); ?></title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'includes_header.php'; ?>
            <!-- Font Awesome -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <section class="relatorios">
        <div class="container">
            <h2>Relatórios Financeiros</h2>

            <!-- Filtros -->
            <form method="GET" class="filtros-relatorio">
                <label for="mes">Mês:</label>
                <select name="mes" id="mes">
                    <?php
                    $nomesMeses = [
                        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                        5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                    ];
                    foreach ($nomesMeses as $num => $nome) {
                        $selected = ($num == $mes) ? 'selected' : '';
                        echo "<option value='$num' $selected>$nome</option>";
                    }
                    ?>
                </select>

                <label for="ano">Ano:</label>
                <select name="ano" id="ano">
                    <?php
                    for ($a = 2025; $a <= 2100; $a++) {
                        $selected = ($a == $ano) ? 'selected' : '';
                        echo "<option value='$a' $selected>$a</option>";
                    }
                    ?>
                </select>

                <label for="tipo">Tipo:</label>
                <select name="tipo" id="tipo">
                    <option value="todos" <?php if ($tipo === 'todos') echo 'selected'; ?>>Todos</option>
                    <option value="receita" <?php if ($tipo === 'receita') echo 'selected'; ?>>Receita</option>
                    <option value="despesa" <?php if ($tipo === 'despesa') echo 'selected'; ?>>Despesa</option>
                    <option value="investimento" <?php if ($tipo === 'investimento') echo 'selected'; ?>>Investimento</option>
                </select>

                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>

            <!-- Botões de exportação -->
            <div style="margin: 20px 0;">
                <a href="exportar_pdf.php?mes=<?php echo $mes; ?>&ano=<?php echo $ano; ?>&tipo=<?php echo $tipo; ?>" class="btn btn-outline">Exportar PDF</a>
                <a href="exportar_excel.php?mes=<?php echo $mes; ?>&ano=<?php echo $ano; ?>&tipo=<?php echo $tipo; ?>" class="btn btn-outline">Exportar Excel</a>
            </div>

            <!-- Gráfico -->
            <h3>Distribuição por Categoria</h3>
            <canvas id="graficoRelatorio" class="grafico-reduzido"></canvas>

            <!-- Tabela -->
            <h3>Transações Filtradas</h3>
            <table>
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Categoria</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($transacoes) === 0): ?>
                        <tr><td colspan="5">Nenhuma transação encontrada para os filtros selecionados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($transacoes as $t): ?>
                        <tr>
                            <td><?php echo ucfirst($t['tipo']); ?></td>
                            <td><?php echo ucfirst(str_replace('_', ' ', $t['categoria'])); ?></td>
                            <td><?php echo htmlspecialchars($t['descricao']); ?></td>
                            <td>R$ <?php echo number_format($t['valor'], 2, ',', '.'); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($t['data'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <script>
    const ctx = document.getElementById('graficoRelatorio').getContext('2d');
    const dados = {
        labels: <?php echo json_encode(array_keys($categorias)); ?>,
        datasets: [{
            data: <?php echo json_encode(array_values($categorias)); ?>,
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                '#9966FF', '#FF9F40', '#C9CBCF', '#8BC34A',
                '#E91E63', '#00BCD4'
            ]
        }]
    };

    new Chart(ctx, {
        type: 'pie',
        data: dados,
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                title: { display: true, text: 'Distribuição por Categoria' }
            }
        }
    });
    </script>

    <?php include 'includes_footer.php'; ?>
</body>
</html>
