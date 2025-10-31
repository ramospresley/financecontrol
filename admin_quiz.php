<?php
session_start();
require_once 'conexao.php';

$usuarioId = $_SESSION['usuario_id'] ?? null;
$tipoUsuario = $_SESSION['tipo_usuario'] ?? null;

if (!$usuarioId || $tipoUsuario !== 'admin') {
    header('Location: index.php');
    exit;
}

$trilhaId = $_GET['trilha_id'] ?? null;
$fase = $_GET['fase'] ?? null;

if (!is_numeric($trilhaId) || !is_numeric($fase)) {
    header('Location: admin_painel.php');
    exit;
}

// Buscar trilha e fase
$stmt = $pdo->prepare("SELECT titulo FROM trilha_conhecimento WHERE id = ?");
$stmt->execute([$trilhaId]);
$trilha = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT titulo_fase FROM conteudo_fases WHERE trilha_id = ? AND fase_numero = ?");
$stmt->execute([$trilhaId, $fase]);
$faseInfo = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar perguntas
$stmt = $pdo->prepare("SELECT * FROM quiz_fases WHERE trilha_id = ? AND fase_numero = ? ORDER BY id ASC");
$stmt->execute([$trilhaId, $fase]);
$perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Quiz da Fase - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'includes_header.php'; ?>

<div class="container">
    <h2 class="section-title">🧠 Quiz - <?php echo htmlspecialchars($trilha['titulo']); ?> / Fase <?php echo $fase; ?>: <?php echo htmlspecialchars($faseInfo['titulo_fase'] ?? ''); ?></h2>
    <a href="admin_quiz_novo.php?trilha=<?php echo $trilhaId; ?>&fase=<?php echo $fase; ?>" class="btn btn-primary">➕ Nova Pergunta</a>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Pergunta</th>
                <th>Resposta Correta</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($perguntas as $q): ?>
            <tr>
                <td><?php echo $q['id']; ?></td>
                <td><?php echo htmlspecialchars($q['pergunta']); ?></td>
                <td><?php echo htmlspecialchars($q['resposta_correta']); ?></td>
                <td>
                    <a href="admin_quiz_editar.php?id=<?php echo $q['id']; ?>" class="btn btn-outline">✏️ Editar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes_footer.php'; ?>
</body>
</html>
