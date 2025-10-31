<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário é administrador
$usuarioId = $_SESSION['usuario_id'] ?? null;
$usuarioAdmin = $_SESSION['usuario_admin'] ?? false;

if (!$usuarioId || !$usuarioAdmin) {
    header('Location: index.php');
    exit;
}

// Verifica se o ID da fase foi passado
$faseId = $_GET['id'] ?? null;
if (!is_numeric($faseId)) {
    header('Location: admin_painel.php');
    exit;
}

// Busca os dados da fase
$stmt = $pdo->prepare("SELECT * FROM conteudos_fases WHERE id = ?");
$stmt->execute([$faseId]);
$fase = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fase) {
    echo "<script>alert('Fase não encontrada.'); window.location.href = 'admin_painel.php';</script>";
    exit;
}

// Processa o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo_fase'] ?? '';
    $conteudo = $_POST['conteudo'] ?? '';
    $video = $_POST['video_url'] ?? '';

    $stmt = $pdo->prepare("UPDATE conteudos_fases SET titulo_fase = ?, conteudo = ?, video_url = ? WHERE id = ?");
    $stmt->execute([$titulo, $conteudo, $video, $faseId]);

    header("Location: admin_fases.php?trilha={$fase['trilha_id']}");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>✏️ Editar Fase</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'includes_header.php'; ?>

<div class="container">
    <h2 class="section-title">✏️ Editar Fase da Trilha</h2>
    <form method="POST" class="form-box">
        <label>Título da Fase:</label>
        <input type="text" name="titulo_fase" value="<?php echo htmlspecialchars($fase['titulo_fase']); ?>" required>

        <label>Conteúdo:</label>
        <textarea name="conteudo" rows="6" required><?php echo htmlspecialchars($fase['conteudo']); ?></textarea>

        <label>URL do Vídeo (opcional):</label>
        <input type="url" name="video_url" value="<?php echo htmlspecialchars($fase['video_url']); ?>">

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</div>

<?php include 'includes_footer.php'; ?>
</body>
</html>
    