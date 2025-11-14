<?php
session_start();
require_once 'conexao.php';

$usuarioId = $_SESSION['usuario_id'] ?? null;
$usuarioLogado = isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'];

if (!$usuarioLogado || !$usuarioId) {
    header('Location: index.php');
    exit;
}

// Verifica se o usuário já possui assinatura ativa
$stmt = $pdo->prepare("SELECT ativo, data_inicio, data_fim FROM assinaturas WHERE usuario_id = ?");
$stmt->execute([$usuarioId]);
$assinatura = $stmt->fetch(PDO::FETCH_ASSOC);

// Processa nova assinatura (simulação)
if (isset($_POST['assinar'])) {
    $dataInicio = date('Y-m-d');
    $dataFim = date('Y-m-d', strtotime('+30 days'));

    if ($assinatura) {
        $stmt = $pdo->prepare("UPDATE assinaturas SET ativo = 1, data_inicio = ?, data_fim = ? WHERE usuario_id = ?");
        $stmt->execute([$dataInicio, $dataFim, $usuarioId]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO assinaturas (usuario_id, ativo, data_inicio, data_fim) VALUES (?, 1, ?, ?)");
        $stmt->execute([$usuarioId, $dataInicio, $dataFim]);
    }

    header('Location: assinatura.php?sucesso=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Assinatura Premium - FinanceControl</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'includes_header.php'; ?>

<div class="container">
    <h2 class="section-title">🌟 Assinatura Premium</h2>
    <p class="section-subtitle">Tenha acesso completo às trilhas avançadas e conteúdos exclusivos</p>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success">Assinatura ativada com sucesso! Aproveite os conteúdos premium.</div>
    <?php endif; ?>

    <?php if ($assinatura && $assinatura['ativo']): ?>
        <div class="card-box">
            <p><strong>Status:</strong> ✅ Assinatura ativa</p>
            <p><strong>Início:</strong> <?php echo date('d/m/Y', strtotime($assinatura['data_inicio'])); ?></p>
            <p><strong>Expira em:</strong> <?php echo date('d/m/Y', strtotime($assinatura['data_fim'])); ?></p>
            <p>Você já tem acesso às trilhas premium!</p>
            <a href="aprender.php" class="btn btn-success">Ir para Aprender</a>
        </div>
    <?php else: ?>
        <div class="card-box">
            <h3>Benefícios da Assinatura</h3>
            <ul>
                <li>🔓 Acesso às trilhas avançadas e certificações</li>
                <li>📚 Conteúdo exclusivo e aprofundado</li>
                <li>🎓 Reconhecimento de conquistas premium</li>
            </ul>
            <form method="POST">
                <button type="submit" name="assinar" class="btn btn-primary">Ativar Assinatura (30 dias grátis)</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes_footer.php'; ?>
</body>
</html>
