<?php
session_start();
require_once 'conexao.php';

$usuarioId = $_SESSION['usuario_id'] ?? null;
$tipoUsuario = $_SESSION['tipo_usuario'] ?? null;

if (!$usuarioId || $tipoUsuario !== 'admin') {
    header('Location: index.php');
    exit;
}

$quizId = $_GET['id'] ?? null;
if (!is_numeric($quizId)) {
    header('Location: admin_painel.php');
    exit;
}

// Buscar pergunta
$stmt = $pdo->prepare("SELECT * FROM quiz_fases WHERE id = ?");
$stmt->execute([$quizId]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    echo "<script>alert('Pergunta não encontrada.'); window.location.href = 'admin_painel.php';</script>";
    exit;
}

$opcoes = json_decode($quiz['opcoes'], true) ?? [];

// Processar edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pergunta = $_POST['pergunta'] ?? '';
    $opcoes = array_map('trim', $_POST['opcoes'] ?? []);
    $resposta = $_POST['resposta_correta'] ?? '';

    $jsonOpcoes = json_encode($opcoes, JSON_UNESCAPED_UNICODE);

    $stmt = $pdo->prepare("UPDATE quiz_fases SET pergunta = ?, opcoes = ?, resposta_correta = ? WHERE id = ?");
    $stmt->execute([$pergunta, $jsonOpcoes, $resposta, $quizId]);

    header("Location: admin_quiz.php?trilha={$quiz['trilha_id']}&fase={$quiz['fase_numero']}");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Pergunta - Quiz</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'includes_header.php'; ?>

<div class="container">
    <h2 class="section-title">✏️ Editar Pergunta de Quiz</h2>
    <form method="POST" class="form-box">
        <label>Pergunta:</label>
        <textarea name="pergunta" rows="3" required><?php echo htmlspecialchars($quiz['pergunta']); ?></textarea>

        <label>Opções:</label>
        <?php foreach ($opcoes as $op): ?>
            <input type="text" name="opcoes[]" value="<?php echo htmlspecialchars($op); ?>" required>
        <?php endforeach; ?>

        <label>Resposta correta:</label>
        <input type="text" name="resposta_correta" value="<?php echo htmlspecialchars($quiz['resposta_correta']); ?>" required>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</div>

<?php include 'includes_footer.php'; ?>
</body>
</html>
