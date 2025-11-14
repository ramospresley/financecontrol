<?php
session_start();
require_once 'conexao.php';

$usuarioId = $_SESSION['usuario_id'] ?? null;
$tipoUsuario = $_SESSION['tipo_usuario'] ?? null;

if (!$usuarioId || $tipoUsuario !== 'admin') {
    header('Location: index.php');
    exit;
}

// Buscar dados de progresso
$stmt = $pdo->query("
    SELECT u.nome AS usuario, t.titulo AS trilha, p.fase_concluida, t.total_fases, p.xp 
    FROM progresso_aprendizado p 
    JOIN usuarios u ON p.usuario_id = u.id 
    JOIN trilha_conhecimento t ON p.trilha_id = t.id 
    ORDER BY u.nome, t.ordem 
");

$dados = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $porcentagem = $row['total_fases'] > 0 ? round(($row['fase_concluida'] / $row['total_fases']) * 100) : 0;
    $dados[] = [
        'usuario' => $row['usuario'],
        'trilha' => $row['trilha'],
        'porcentagem' => $porcentagem,
        'xp' => $row['xp']
    ];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard de Progresso - Admin</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'includes_header.php'; ?>
    
    <div class="container">
        <h2 class="section-title">📊 Dashboard de Progresso dos Usuários</h2>
        
        <div style="margin-bottom: 20px;">
            <a href="exportar_pdf.php" class="btn btn-outline">📄 Exportar PDF</a>
            <a href="exportar_excel.php" class="btn btn-outline">📊 Exportar Excel</a>
        </div>

        <canvas id="graficoProgresso" height="100"></canvas>

        <script>
            const dados = <?php echo json_encode($dados); ?>;
            const labels = dados.map(d => `${d.usuario} - ${d.trilha}`);
            const progresso = dados.map(d => d.porcentagem);
            const xp = dados.map(d => d.xp);

            new Chart(document.getElementById('graficoProgresso'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: '% Concluído',
                            data: progresso,
                            backgroundColor: '#36A2EB'
                        },
                        {
                            label: 'XP',
                            data: xp,
                            backgroundColor: '#FF6384'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Progresso por Usuário e Trilha'
                        },
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        </script>

        <table>
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Trilha</th>
                    <th>% Concluído</th>
                    <th>XP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dados as $d): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($d['usuario']); ?></td>
                        <td><?php echo htmlspecialchars($d['trilha']); ?></td>
                        <td><?php echo $d['porcentagem']; ?>%</td>
                        <td><?php echo $d['xp']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include 'includes_footer.php'; ?>
</body>
</html>