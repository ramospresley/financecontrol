<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar'])) {
    // Sanitização básica
    $nome = trim($_POST['nome']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'];
    $confirmarSenha = $_POST['confirmar_senha'];

    // Validação
    if (!$nome || !$email || !$senha || !$confirmarSenha) {
        $_SESSION['erro_cadastro'] = 'Preencha todos os campos corretamente.';
        header('Location: index.php');
        exit;
    }

    if ($senha !== $confirmarSenha) {
        $_SESSION['erro_cadastro'] = 'As senhas não coincidem.';
        header('Location: index.php');
        exit;
    }

    // Verificar se e-mail já existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['erro_cadastro'] = 'E-mail já cadastrado. Tente outro.';
        header('Location: index.php');
        exit;
    }

    // Criptografar senha
    $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

    // Inserir novo usuário
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha_hash) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $email, $senhaCriptografada]);

    // Autenticar e redirecionar
    $_SESSION['usuario_logado'] = true;
    $_SESSION['nome_usuario'] = $nome;
    $_SESSION['usuario_id'] = $pdo->lastInsertId();
    $_SESSION['sucesso_cadastro'] = 'Cadastro realizado com sucesso!';

    header('Location: dashboard.php');
    exit;
}
?>
