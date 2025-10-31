<?php
session_start();
require_once 'conexao.php';

$usuarioId = $_SESSION['usuario_id'] ?? null;
if (!$usuarioId) {
    header('Location: index.php');
    exit;
}

// Buscar progresso do usuário com trilhas
$stmt = $pdo->prepare("SELECT p.trilha_id, p.fase_concluida, p.xp, p.vidas, p.ultima_vida_perdida,
                              t.titulo, t.total_fases, t.trilha_paga
                       FROM progresso_aprendizado p
                       JOIN trilha_conhecimento t ON p.trilha_id = t.id
                       WHERE p.usuario_id = ?
                       ORDER BY t.ordem ASC");
$stmt->execute([$usuarioId]);
$trilhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Função para calcular vidas recuperadas
function calcularVidas($vidas, $ultimaVidaPerdida) {
    if ($vidas >= 5 || !$ultimaVidaPerdida) return $vidas;
    $agora = new DateTime();
    $ultima = new DateTime($ultimaVidaPerdida);
    $intervalo = $ultima->diff($agora);
    $horasPassadas = $intervalo->h + ($intervalo->days * 24);
    $vidasRecuperadas = floor($horasPassadas / 2);
    return min(5, $vidas + $vidasRecuperadas);
}

// Função para gerar conquistas
function gerarConquistas($faseConcluida, $xp, $totalFases, $trilhaPaga) {
    $conquistas = [];

    if ($faseConcluida >= 1) $conquistas[] = "🎯 Primeira fase concluída";
    if ($xp >= 500) $conquistas[] = "💎 500 XP acumulados";
    if ($faseConcluida >= $totalFases) $conquistas[] = "🏁 Trilha concluída";
    if ($xp >= 200) $conquistas[] = "❤️ Recuperou uma vida com XP";
    if ($trilhaPaga && $faseConcluida >= $totalFases) $conquistas[] = "🔓 Concluiu trilha premium";

    return $conquistas;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meu Painel de Aprendizado</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'includes_header.php'; ?>

<div class="container">
    <h2 class="section-title">📊 Meu Painel de Aprendizado</h2>
    <p class="section-subtitle">Acompanhe seu progresso, XP, vidas e conquistas em cada trilha</p>

    <?php foreach ($trilhas as $trilha):
        $vidas = calcularVidas((int)$trilha['vidas'], $trilha['ultima_vida_perdida']);
        $xp = (int)$trilha['xp'];
        $faseConcluida = (int)$trilha['fase_concluida'];
        $totalFases = (int)$trilha['total_fases'];
        $porcentagem = $totalFases > 0 ? round(($faseConcluida / $totalFases) * 100) : 0;
        $conquistas = gerarConquistas($faseConcluida, $xp, $totalFases, $trilha['trilha_paga']);
    ?>
    <div class="trilha-card">
        <h3><?php echo htmlspecialchars($trilha['titulo']); ?></h3>
        <p><strong>Fases concluídas:</strong> <?php echo $faseConcluida; ?> de <?php echo $totalFases; ?> (<?php echo $porcentagem; ?>%)</p>

        <p><strong>XP acumulado:</strong> <span class="xp-badge"><?php echo $xp; ?> XP</span></p>

        <p><strong>Vidas restantes:</strong>
            <?php for ($i = 0; $i < 5; $i++): ?>
                <span style="font-size: 20px; color:<?php echo $i < $vidas ? '#e63946' : '#ccc'; ?>;">❤️</span>
            <?php endfor; ?>
        </p>

        <div class="progress-bar-container">
            <div class="progress-bar" style="width: <?php echo $porcentagem; ?>%;"></div>
        </div>
        <p class="progresso-texto"><?php echo $porcentagem; ?>% completo</p>

        <?php if ($conquistas): ?>
        <div class="conquistas-box" style="margin-top: 15px;">
            <h4>🏅 Conquistas</h4>
            <?php foreach ($conquistas as $c): ?>
                <span class="xp-badge" style="margin-right: 5px;"><?php echo $c; ?></span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($porcentagem < 50): ?>
            <p class="motivational">🚀 Continue! Você está no caminho certo.</p>
        <?php elseif ($porcentagem < 100): ?>
            <p class="motivational">💪 Quase lá! Só mais algumas fases.</p>
        <?php else: ?>
            <p class="motivational">🎉 Parabéns! Você concluiu esta trilha.</p>
        <?php endif; ?>

        <!-- Botão de acesso à trilha -->
        <div class="trilha-acesso">
            <?php if ($faseConcluida < $totalFases): ?>
                <a href="fase.php?trilha=<?php echo $trilha['trilha_id']; ?>&fase=<?php echo $faseConcluida + 1; ?>" class="btn btn-primary">⏩ Continuar (Fase <?php echo $faseConcluida + 1; ?>)</a>
            <?php else: ?>
                <a href="fase.php?trilha=<?php echo $trilha['trilha_id']; ?>&fase=<?php echo $totalFases; ?>" class="btn btn-success">✅ Revisar Conteúdo</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <?php if (count($trilhas) === 0): ?>
        <div class="card-box">
            <p>Você ainda não iniciou nenhuma trilha. Comece agora em <a href="aprender.php">Aprender</a>.</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes_footer.php'; ?>
</body>
</html>