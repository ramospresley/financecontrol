<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['entrar'])) {
    // Sanitização básica
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    // Validação
    if (!$email || !$senha) {
        $_SESSION['erro_login'] = 'Preencha todos os campos corretamente.';
        header('Location: index.php');
        exit;
    }

    // Buscar usuário pelo e-mail
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar senha
    if ($usuario && isset($usuario['senha_hash']) && password_verify($senha, $usuario['senha_hash'])) {
        // Definir sessões de usuário
        $_SESSION['usuario_logado'] = true;
        $_SESSION['usuario_id'] = (int)$usuario['id'];
        $_SESSION['nome_usuario'] = $usuario['nome'];
        $_SESSION['tipo_usuario'] = $usuario['tipo_usuario']; // <-- unificado
        $_SESSION['usuario_admin'] = ($usuario['tipo_usuario'] === 'admin'); // compatibilidade retroativa

        // Direcionar para página adequada
        if ($usuario['tipo_usuario'] === 'admin') {
            header('Location: admin_dashboard.php');
        } else {
            header('Location: dashboard.php');
        }
        exit;
    }

    // Falha no login
    $_SESSION['erro_login'] = 'E-mail ou senha inválidos.';
    header('Location: index.php');
    exit;
}
?>