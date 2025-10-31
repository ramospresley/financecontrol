<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioLogado = isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'];
$nomeUsuario = $usuarioLogado ? $_SESSION['nome_usuario'] : 'Visitante';
$paginaAtual = basename($_SERVER['SCRIPT_NAME'], '.php');
$tipoUsuario = $_SESSION['tipo_usuario'] ?? null;
?>
<header>
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="dashboard.php"> <img src="img/FC4.png" alt="FinanceControl Logo"> </a>
            </div>

            <nav class="main-nav">
                <ul class="nav-links">
                    <li><a href="servicosgerais.php#dashboard" class="btn btn-outline <?php echo $paginaAtual === 'dashboard' ? 'active' : ''; ?>">Dashboard</a></li>
                    <li><a href="servicosgerais.php#carteira" class="btn btn-outline <?php echo $paginaAtual === 'carteira' ? 'active' : ''; ?>">Carteira</a></li>
                    <li><a href="servicosgerais.php#aprender" class="btn btn-outline <?php echo $paginaAtual === 'aprender' ? 'active' : ''; ?>">Aprender</a></li>

                    <?php if ($tipoUsuario === 'admin'): ?>
                        <li><a href="admin_dashboard.php" class="btn btn-outline <?php echo $paginaAtual === 'admin_dashboard' ? 'active' : ''; ?>">Admin</a></li>
                        <li><a href="admin_painel.php" class="btn btn-outline <?php echo $paginaAtual === 'admin_painel' ? 'active' : ''; ?>">Painel</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

             <a href="index.php" class="btn btn-outline">Voltar</a>
        </div>
    </div>

<style>
/* --- CENTRALIZAÇÃO DO MENU --- */
.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.main-nav {
    flex: 1;
    display: flex;
    justify-content: center; /* Centraliza o menu */
}

.nav-links {
    list-style: none;
    display: flex;
    gap: 25px; /* Espaçamento entre os itens */
    margin: 0;
    padding: 0;
}

.nav-links a {
    text-decoration: none;
    font-weight: 500;
    color: #333;
}

.nav-links a.active {
    color: var(--cor-principal, #007bff);
}
</style>




</header>
