<?php
session_start();
require_once 'conexao.php';

$usuarioId = $_SESSION['usuario_id'] ?? null;
$tipoUsuario = $_SESSION['tipo_usuario'] ?? null;

if (!$usuarioId || $tipoUsuario !== 'admin') {
    header('Location: index.php');
    exit;
}

// Buscar trilhas
$stmt = $pdo->query("SELECT * FROM trilha_conhecimento ORDER BY ordem ASC");
$trilhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar assinaturas
$stmt = $pdo->query("SELECT a.*, u.nome FROM assinaturas a JOIN usuarios u ON a.usuario_id = u.id ORDER BY a.data_inicio DESC");
$assinaturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'includes_header.php'; ?>

<div class="container">
    <h2 class="section-title">🛠️ Painel Administrativo</h2>
    <p class="section-subtitle">Gerencie trilhas, fases, quizzes e assinaturas</p>

    <section>
        <h3>📚 Trilhas de Conhecimento</h3>
        <a href="admin_trilha_nova.php" class="btn btn-primary">➕ Nova Trilha</a>
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Nível</th>
                    <th>Premium</th>
                    <th>Fases</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trilhas as $t): ?>
                <tr>
                    <td><?php echo htmlspecialchars($t['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($t['nivel']); ?></td>
                    <td><?php echo $t['trilha_paga'] ? '🔒' : '✅'; ?></td>
                    <td><?php echo $t['total_fases']; ?></td>
                    <td>
                        <a href="admin_trilha_editar.php?id=<?php echo $t['id']; ?>" class="btn btn-outline">✏️ Editar</a>
                        <a href="admin_fases.php?trilha=<?php echo $t['id']; ?>" class="btn btn-outline">📄 Fases</a>
                        
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section style="margin-top: 40px;">
        <h3>💼 Assinaturas Ativas</h3>
        <table>
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Início</th>
                    <th>Fim</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assinaturas as $a): ?>
                <tr>
                    <td><?php echo htmlspecialchars($a['nome']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($a['data_inicio'])); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($a['data_fim'])); ?></td>
                    <td><?php echo $a['ativo'] ? '✅ Ativa' : '❌ Inativa'; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>

<?php include 'includes_footer.php'; ?>
</body>
</html>
