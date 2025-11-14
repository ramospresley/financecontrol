<?php
session_start();
require_once 'conexao.php';

$usuarioId = $_SESSION['usuario_id'] ?? null;
$tipoUsuario = $_SESSION['tipo_usuario'] ?? null;

if (!$usuarioId || $tipoUsuario !== 'admin') {
    header('Location: index.php');
    exit;
}

$trilhaId = $_GET['id'] ?? null;
if (!is_numeric($trilhaId)) {
    header('Location: admin_painel.php');
    exit;
}

// Buscar trilha existente
$stmt = $pdo->prepare("SELECT * FROM trilha_conhecimento WHERE id = ?");
$stmt->execute([$trilhaId]);
$trilha = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trilha) {
    echo "<script>alert('Trilha não encontrada.'); window.location.href = 'admin_painel.php';</script>";
    exit;
}

// Processar edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $totalFases = is_numeric($_POST['total_fases']) ? (int)$_POST['total_fases'] : 5;
    $trilhaPaga = isset($_POST['trilha_paga']) ? 1 : 0;
    $ordem = is_numeric($_POST['ordem']) ? (int)$_POST['ordem'] : 0;
    $nivel = $_POST['nivel'] ?? 'iniciante';

    $stmt = $pdo->prepare("UPDATE trilha_conhecimento SET titulo = ?, descricao = ?, total_fases = ?, trilha_paga = ?, ordem = ?, nivel = ? WHERE id = ?");
    $stmt->execute([$titulo, $descricao, $totalFases, $trilhaPaga, $ordem, $nivel, $trilhaId]);

    header('Location: admin_painel.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Trilha - Painel Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'includes_header.php'; ?>

<div class="container">
    <h2 class="section-title">✏️ Editar Trilha</h2>
    <form method="POST" class="form-box">
        <label>Título da trilha:</label>
        <input type="text" name="titulo" value="<?php echo htmlspecialchars($trilha['titulo']); ?>" required>

        <label>Descrição:</label>
        <textarea name="descricao" rows="4"><?php echo htmlspecialchars($trilha['descricao']); ?></textarea>

        <label>Total de fases:</label>
        <input type="number" name="total_fases" value="<?php echo $trilha['total_fases']; ?>" min="1" required>

        <label>Trilha paga?</label>
        <input type="checkbox" name="trilha_paga" <?php echo $trilha['trilha_paga'] ? 'checked' : ''; ?>> Sim

        <label>Ordem de exibição:</label>
        <input type="number" name="ordem" value="<?php echo $trilha['ordem']; ?>" min="0">

        <label>Nível:</label>
        <select name="nivel">
            <option value="iniciante" <?php if ($trilha['nivel'] === 'iniciante') echo 'selected'; ?>>Iniciante</option>
            <option value="intermediario" <?php if ($trilha['nivel'] === 'intermediario') echo 'selected'; ?>>Intermediário</option>
            <option value="avancado" <?php if ($trilha['nivel'] === 'avancado') echo 'selected'; ?>>Avançado</option>
        </select>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</div>

<?php include 'includes_footer.php'; ?>
</body>
</html>
