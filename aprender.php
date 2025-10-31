<?php 
session_start();
require_once 'conexao.php';
require_once 'funcoes.php';

$usuarioId = $_SESSION['usuario_id'] ?? null;
if (!$usuarioId) {
    header('Location: index.php');
    exit;
}

// Verifica se o usuário tem assinatura ativa
$stmt = $pdo->prepare("SELECT ativo FROM assinaturas WHERE usuario_id = ? AND ativo = 1");
$stmt->execute([$usuarioId]);
$usuarioTemAcessoPremium = $stmt->fetchColumn() ? true : false;

// Buscar trilhas
$stmt = $pdo->prepare("SELECT * FROM trilha_conhecimento ORDER BY ordem ASC");
$stmt->execute();
$trilhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar progresso do usuário
$stmt = $pdo->prepare("SELECT trilha_id, fase_concluida, xp, vidas, ultima_vida_perdida FROM progresso_aprendizado WHERE usuario_id = ?");
$stmt->execute([$usuarioId]);
$progresso = [];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $progresso[$row['trilha_id']] = $row;
}

// Criar registro inicial se não existir
if (isset($_GET['iniciar_trilha']) && is_numeric($_GET['iniciar_trilha'])) {
    $trilhaId = (int)$_GET['iniciar_trilha'];

    // Verifica se a trilha é paga
    $stmt = $pdo->prepare("SELECT trilha_paga FROM trilha_conhecimento WHERE id = ?");
    $stmt->execute([$trilhaId]);
    $trilhaSelecionada = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($trilhaSelecionada && $trilhaSelecionada['trilha_paga'] && !$usuarioTemAcessoPremium) {
        header("Location: assinatura.php");
        exit;
    }

    if (!isset($progresso[$trilhaId])) {
        $stmt = $pdo->prepare("INSERT INTO progresso_aprendizado (usuario_id, trilha_id, fase_concluida, xp, vidas, ultima_vida_perdida) VALUES (?, ?, 0, 0, 5, NULL)");
        $stmt->execute([$usuarioId, $trilhaId]);
        $progresso[$trilhaId] = ['fase_concluida' => 0, 'xp' => 0, 'vidas' => 5, 'ultima_vida_perdida' => null];
    }

    header("Location: fase.php?trilha=$trilhaId&fase=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Aprender - Finance Control</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'includes_header.php'; ?>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="painel-topo">
    <?php
    $totalXP = 0;
    $totalVidas = 0;
    foreach ($progresso as $p) {
        $totalXP += (int)$p['xp'];
        $totalVidas += calcularVidas((int)$p['vidas'], $p['ultima_vida_perdida']);
    }
    ?>
    <div class="painel-status">
        <span class="xp-badge">💎 <?php echo $totalXP; ?> XP</span>
        <span class="vidas-badge">
            <?php for ($i = 0; $i < 5; $i++): ?>
                <span style="font-size: 20px; color:<?php echo $i < $totalVidas ? '#e63946' : '#ccc'; ?>;">❤️</span>
            <?php endfor; ?>
        </span>
    </div>
</div>

<main class="trilhas-container">
    <?php foreach ($trilhas as $trilha):
        $trilhaId = (int)$trilha['id'];
        $dados = $progresso[$trilhaId] ?? ['fase_concluida' => 0, 'xp' => 0, 'vidas' => 5, 'ultima_vida_perdida' => null];
        $faseConcluida = (int)$dados['fase_concluida'];
        $totalFases = (int)$trilha['total_fases'];
        $proximaFase = $faseConcluida < $totalFases ? $faseConcluida + 1 : $totalFases;
        $porcentagem = $totalFases > 0 ? round(($faseConcluida / $totalFases) * 100) : 0;
        $concluida = ($faseConcluida >= $totalFases);
    ?>
    <div class="trilha-card <?php echo $concluida ? 'concluida' : 'ativa'; ?>">
        <h3 class="trilha-titulo"><?php echo htmlspecialchars($trilha['titulo']); ?></h3>
        <p class="trilha-descricao"><?php echo htmlspecialchars($trilha['descricao'] ?? 'Descrição não disponível.'); ?></p>

        <div class="trilha-progresso">
            <div class="progresso-barra">
                <div class="progresso-preenchido" style="width: <?php echo $porcentagem; ?>%;"></div>
            </div>
            <p class="progresso-texto"><?php echo $porcentagem; ?>% completo</p>
        </div>

        <div class="trilha-acoes">
            <?php if ($trilha['trilha_paga'] && !$usuarioTemAcessoPremium): ?>
                <p class="alert alert-warning">🔒 Trilha premium. <a href="assinatura.php">Assine para acessar</a>.</p>
            <?php elseif ($concluida): ?>
                <a href="fase.php?trilha=<?php echo $trilhaId; ?>&fase=<?php echo $totalFases; ?>" class="btn btn-success">✅ Revisar Conteúdo</a>
            <?php elseif ($faseConcluida === 0): ?>
                <a href="aprender.php?iniciar_trilha=<?php echo $trilhaId; ?>" class="btn btn-primary">🚀 Iniciar Trilha</a>
            <?php else: ?>
                <a href="fase.php?trilha=<?php echo $trilhaId; ?>&fase=<?php echo $proximaFase; ?>" class="btn btn-primary">⏩ Continuar (Fase <?php echo $proximaFase; ?>)</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</main>

<?php include 'includes_footer.php'; ?>
</body>
</html>
