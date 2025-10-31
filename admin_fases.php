<?php
session_start();
require_once 'conexao.php';

$usuarioId = $_SESSION['usuario_id'] ?? null;
$tipoUsuario = $_SESSION['tipo_usuario'] ?? null;

if (!$usuarioId || $tipoUsuario !== 'admin') {
    header('Location: index.php');
    exit;
}

$trilhaId = isset($_GET['trilha']) && is_numeric($_GET['trilha']) ? intval($_GET['trilha']) : null;

if (!$trilhaId) {
    header('Location: admin_painel.php');
    exit;
}

// Buscar trilha
$stmt = $pdo->prepare("SELECT titulo FROM trilha_conhecimento WHERE id = ?");
$stmt->execute([$trilhaId]);
$trilha = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar fases
$stmt = $pdo->prepare("SELECT * FROM conteudo_fases WHERE trilha_id = ? ORDER BY fase_numero ASC");
$stmt->execute([$trilhaId]);
$fases = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Fases da Trilha - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'includes_header.php'; ?>

<div class="container">
    <h2 class="section-title">📄 Fases da Trilha: <?php echo htmlspecialchars($trilha['titulo']); ?></h2>
    <a href="admin_fase_nova.php?trilha=<?php echo $trilhaId; ?>" class="btn btn-primary">➕ Nova Fase</a>

    <table>
        <thead>
            <tr>
                <th>Fase</th>
                <th>Título</th>
                <th>Vídeo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fases as $f): ?>
            <tr>
                <td><?php echo $f['fase_numero']; ?></td>
                <td><?php echo htmlspecialchars($f['titulo_fase']); ?></td>
                <td><?php echo $f['video_url'] ? '🎥' : '—'; ?></td>
                <td>
                    <a href="admin_fase_editar.php?id=<?php echo $f['id']; ?>" class="btn btn-outline">✏️ Editar</a>
                    <a href="admin_quiz.php?trilha=<?php echo $trilhaId; ?>&fase=<?php echo $f['fase_numero']; ?>" class="btn btn-outline">🧠 Quiz</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes_footer.php'; ?>
</body>
</html>
