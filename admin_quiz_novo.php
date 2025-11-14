<?php
session_start();
require_once 'conexao.php';

$usuarioId = $_SESSION['usuario_id'] ?? null;
$tipoUsuario = $_SESSION['tipo_usuario'] ?? null;

if (!$usuarioId || $tipoUsuario !== 'admin') {
    header('Location: index.php');
    exit;
}

$trilhaId = $_GET['trilha'] ?? null;
$fase = $_GET['fase'] ?? null;

if (!is_numeric($trilhaId) || !is_numeric($fase)) {
    header('Location: admin_painel.php');
    exit;
}

// Processar envio
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pergunta = $_POST['pergunta'] ?? '';
    $opcoes = array_map('trim', $_POST['opcoes'] ?? []);
    $resposta = $_POST['resposta_correta'] ?? '';

    $jsonOpcoes = json_encode($opcoes, JSON_UNESCAPED_UNICODE);

    $stmt = $pdo->prepare("INSERT INTO quiz_fases (trilha_id, fase_numero, pergunta, opcoes, resposta_correta) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$trilhaId, $fase, $pergunta, $jsonOpcoes, $resposta]);

    header("Location: admin_quiz.php?trilha=$trilhaId&fase=$fase");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nova Pergunta - Quiz</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'includes_header.php'; ?>

<div class="container">
    <h2 class="section-title">➕ Nova Pergunta de Quiz</h2>
    <form method="POST" class="form-box">
        <label>Pergunta:</label>
        <textarea name="pergunta" rows="3" required></textarea>

        <label>Opções (4 alternativas):</label>
        <?php for ($i = 0; $i < 4; $i++): ?>
            <input type="text" name="opcoes[]" required>
        <?php endfor; ?>

        <label>Resposta correta (copie exatamente uma das opções):</label>
        <input type="text" name="resposta_correta" required>

        <button type="submit" class="btn btn-primary">Salvar Pergunta</button>
    </form>
</div>

<?php include 'includes_footer.php'; ?>
</body>
</html>
