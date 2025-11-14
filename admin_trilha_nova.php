<?php
session_start();
require_once 'conexao.php';

$usuarioId = $_SESSION['usuario_id'] ?? null;
$tipoUsuario = $_SESSION['tipo_usuario'] ?? null;

if (!$usuarioId || $tipoUsuario !== 'admin') {
    header('Location: index.php');
    exit;
}

// Processar envio
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $totalFases = is_numeric($_POST['total_fases']) ? (int)$_POST['total_fases'] : 5;
    $trilhaPaga = isset($_POST['trilha_paga']) ? 1 : 0;
    $ordem = is_numeric($_POST['ordem']) ? (int)$_POST['ordem'] : 0;
    $nivel = $_POST['nivel'] ?? 'iniciante';

    $stmt = $pdo->prepare("INSERT INTO trilha_conhecimento (titulo, descricao, total_fases, trilha_paga, ordem, nivel) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$titulo, $descricao, $totalFases, $trilhaPaga, $ordem, $nivel]);

    header('Location: admin_painel.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nova Trilha - Painel Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'includes_header.php'; ?>

<div class="container">
    <h2 class="section-title">➕ Criar Nova Trilha</h2>
    <form method="POST" class="form-box">
        <label>Título da trilha:</label>
        <input type="text" name="titulo" required>

        <label>Descrição:</label>
        <textarea name="descricao" rows="4"></textarea>

        <label>Total de fases:</label>
        <input type="number" name="total_fases" value="5" min="1" required>

        <label>Trilha paga?</label>
        <input type="checkbox" name="trilha_paga"> Sim

        <label>Ordem de exibição:</label>
        <input type="number" name="ordem" value="0" min="0">

        <label>Nível:</label>
        <select name="nivel">
            <option value="iniciante">Iniciante</option>
            <option value="intermediario">Intermediário</option>
            <option value="avancado">Avançado</option>
        </select>

        <button type="submit" class="btn btn-primary">Salvar Trilha</button>
    </form>
</div>

<?php include 'includes_footer.php'; ?>
</body>
</html>
