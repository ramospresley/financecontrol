<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioLogado = !empty($_SESSION['usuario_logado']);
$nomeUsuario   = $usuarioLogado ? ($_SESSION['nome_usuario'] ?? 'Usuário') : 'Visitante';
$paginaAtual   = basename($_SERVER['SCRIPT_NAME'], '.php');
$tipoUsuario   = $_SESSION['tipo_usuario'] ?? null;
$usuario_id    = $_SESSION['usuario_id'] ?? null;

// Buscar avatar do usuário se estiver logado
$avatar_url = null;
if ($usuarioLogado && $usuario_id) {
    require_once 'conexao.php';
    $stmt = $pdo->prepare("SELECT avatar_url FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($usuario && !empty($usuario['avatar_url'])) {
        $avatar_url = htmlspecialchars($usuario['avatar_url']);
    }
}
?>
<header>
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="dashboard.php">
                    <img src="img/FC4.png" alt="FinanceControl Logo">
                </a>
            </div>

            <nav class="main-nav">
                <ul class="nav-links">
                    <li><a href="dashboard.php" class="btn btn-outline <?= $paginaAtual === 'dashboard' ? 'active' : ''; ?>">Dashboard</a></li>
                    <li><a href="carteira.php" class="btn btn-outline <?= $paginaAtual === 'carteira' ? 'active' : ''; ?>">Carteira</a></li>
                    <li><a href="aprender.php" class="btn btn-outline <?= $paginaAtual === 'aprender' ? 'active' : ''; ?>">Aprender</a></li>

                    <?php if ($tipoUsuario === 'admin'): ?>
                        <li><a href="admin_dashboard.php" class="btn btn-outline <?= $paginaAtual === 'admin_dashboard' ? 'active' : ''; ?>">Admin</a></li>
                        <li><a href="admin_painel.php" class="btn btn-outline <?= $paginaAtual === 'admin_painel' ? 'active' : ''; ?>">Painel</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="user-actions">
                <?php if (!$usuarioLogado): ?>
                    <button class="btn btn-primary abrirModalLogin">Login / Cadastro</button>
                <?php else: ?>
                    <div class="user-info">
                        <img 
                            src="<?= $avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($nomeUsuario) . '&background=58cc02&color=fff'; ?>" 
                            alt="Avatar" 
                            class="user-avatar"
                        >
                        <span>Olá, <?= htmlspecialchars($nomeUsuario); ?></span>
                    </div>

                    <!-- Ícone de engrenagem visível -->
                    <div class="dropdown">
                        <button class="btn btn-outline dropdown-toggle" title="Configurações">
                            <i class="fas fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="settings.php"><i class="fas fa-user-cog"></i> Meu Perfil</a></li>
                            <li><hr style="margin: 5px 0;"></li>
                            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
