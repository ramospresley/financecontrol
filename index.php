<?php
session_start();
require_once 'conexao.php';

// Redireciona se já estiver logado
if (isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'] === true) {
    header('Location: dashboard.php');
    exit;
}

// Mensagens de erro/sucesso
$erro_login = $_SESSION['erro_login'] ?? '';
$erro_cadastro = $_SESSION['erro_cadastro'] ?? '';
$sucesso_cadastro = $_SESSION['sucesso_cadastro'] ?? '';

unset($_SESSION['erro_login'], $_SESSION['erro_cadastro'], $_SESSION['sucesso_cadastro']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>FinanceControl - Controle Inteligente</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <!-- Cabeçalho -->
  <header class="header-green">
    <div class="container header-content">
      <div class="logo">
        <img src="img/FC4.png" alt="logo">
      </div>
      <div class="user-actions">
        <button class="btn btn-outline abrirModalLogin">Entrar</button>
      </div>
    </div>
  </header>

  <!-- Seção principal -->
  <section class="hero-green">
    <div class="container">
      <h1>Controle suas Finanças de Forma Inteligente</h1>
      <p>Gerencie receitas, despesas, investimentos e aprenda educação financeira com nossa plataforma gamificada</p>
    </div>
  </section>

  <!-- Seção motivacional -->
  <section class="motivational">
    <div class="container">
      <h2>Domine suas finanças.</h2>
      <p>O futuro que você deseja começa agora!</p>
    </div>
  </section>

  <!-- Modal de Login -->
  <div id="loginModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Entrar na sua conta</h2>

      <?php if (!empty($erro_login)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($erro_login); ?></div>
      <?php endif; ?>

      <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit" name="entrar" class="btn btn-primary">Entrar</button>
      </form>

      <div class="modal-switch">
        <p style="text-align: center; margin: 10px 0;">
          <a href="recuperar_senha.php" style="color: #58cc02; text-decoration: none;">
            Esqueci minha senha
          </a>
        </p>
        <p>Ainda não tenho cadastro. <a href="#" id="abrirCadastro">Cadastrar</a></p>
        <p>Ou acesse com:</p>
        <a href="https://accounts.google.com/o/oauth2/v2/auth?client_id=672165073469-06j44lan63o8vrsagiv6ko2ksctibc1f.apps.googleusercontent.com&redirect_uri=http://localhost/financecontrol/google_callback.php&response_type=code&scope=email%20profile&access_type=offline" class="btn btn-google">
          <i class="fab fa-google"></i> Google
        </a>
      </div>
    </div>
  </div>

  <!-- Modal de Cadastro -->
  <div id="cadastroModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Criar nova conta</h2>

      <?php if (!empty($erro_cadastro)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($erro_cadastro); ?></div>
      <?php endif; ?>

      <?php if (!empty($sucesso_cadastro)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($sucesso_cadastro); ?></div>
      <?php endif; ?>

      <form action="cadastro.php" method="POST">
        <input type="text" name="nome" placeholder="Nome completo" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="senha" placeholder="Senha" required minlength="6">
        <input type="password" name="confirmar_senha" placeholder="Confirmar Senha" required>
        <button type="submit" name="cadastrar" class="btn btn-success">Cadastrar</button>
      </form>

      <div class="modal-switch">
        <p>Já tenho cadastro. <a href="#" id="abrirLogin">Entrar</a></p>
      </div>
    </div>
  </div>

  <!-- Rodapé -->
<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>FinanceControl</h3>
                <p>Sua plataforma completa para controle financeiro e aprendizado</p>
            </div>
                
            <div class="footer-section">
                <h4>Contato</h4>
                <p>financecontroltccetec@gmail.com</p>
                <p><a href="quemsomos.php">Quem Somos</a></p>
                <p><a href="servicosgerais.php">Recursos</a></p>
           <div class="social-icons">
            <a href="https://www.facebook.com/profile.php?id=61582206695203&notif_id=1761008756883584&notif_t=follow_profile&ref=notif&locale=pt_BR" target = "_blank"><i class="fab fa-facebook"></i></a>
            <a href="https://www.instagram.com/finance_control20/" target = "_blank"><i class="fab fa-instagram"></i></a>
            <a href="https://x.com/Financecon9247?t=f-sd_hELRjPXDVcJFRM2ww&s=08" target = "_blank"><i class="fab fa-twitter"></i></a>
            <a href="https://www.youtube.com/@FinanceControlApp" target = "_blank"><i class="fab fa-youtube"></i></a>
            
          </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 FinanceControl - Todos os direitos reservados</p>
        </div>
    </div>
</footer>

  <!-- Script para alternar modais -->
  <script>
    const loginModal = document.getElementById('loginModal');
    const cadastroModal = document.getElementById('cadastroModal');
    const abrirLoginBtn = document.querySelector('.abrirModalLogin');
    const abrirCadastroLink = document.getElementById('abrirCadastro');
    const abrirLoginLink = document.getElementById('abrirLogin');
    const fecharBtns = document.querySelectorAll('.close');

    abrirLoginBtn.addEventListener('click', () => {
      loginModal.style.display = 'block';
    });

    abrirCadastroLink.addEventListener('click', (e) => {
      e.preventDefault();
      loginModal.style.display = 'none';
      cadastroModal.style.display = 'block';
    });

    abrirLoginLink.addEventListener('click', (e) => {
      e.preventDefault();
      cadastroModal.style.display = 'none';
      loginModal.style.display = 'block';
    });

    fecharBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        loginModal.style.display = 'none';
        cadastroModal.style.display = 'none';
      });
    });

    window.addEventListener('click', (e) => {
      if (e.target === loginModal || e.target === cadastroModal) {
        loginModal.style.display = 'none';
        cadastroModal.style.display = 'none';
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        loginModal.style.display = 'none';
        cadastroModal.style.display = 'none';
      }
    });

    <?php if (!empty($erro_login)): ?>
      document.addEventListener('DOMContentLoaded', function() {
        loginModal.style.display = 'block';
      });
    <?php endif; ?>

    <?php if (!empty($erro_cadastro) || !empty($sucesso_cadastro)): ?>
      document.addEventListener('DOMContentLoaded', function() {
        cadastroModal.style.display = 'block';
      });
    <?php endif; ?>
  </script>
</body>
</html>