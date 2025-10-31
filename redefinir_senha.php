<?php
session_start();
require_once 'conexao.php';
require_once 'funcoes.php'; // Inclui as funções de e-mail

$mensagem = '';
$erro = '';
$token_valido = false;
$email_usuario = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Verificar se o token é válido e não expirou
    $stmt = $pdo->prepare("SELECT id, email, nome FROM usuarios WHERE token_recuperacao = ? AND token_expiracao > NOW()");
    $stmt->execute([$token]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario) {
        $token_valido = true;
        $usuario_id = $usuario['id'];
        $email_usuario = $usuario['email'];
        $nome_usuario = $usuario['nome'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['redefinir'])) {
            $nova_senha = $_POST['nova_senha'];
            $confirmar_senha = $_POST['confirmar_senha'];
            
            if (strlen($nova_senha) < 6) {
                $erro = 'A senha deve ter pelo menos 6 caracteres.';
            } elseif ($nova_senha !== $confirmar_senha) {
                $erro = 'As senhas não coincidem.';
            } else {
                // Atualizar senha e limpar token
                $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE usuarios SET senha_hash = ?, token_recuperacao = NULL, token_expiracao = NULL WHERE id = ?");
                
                if ($stmt->execute([$senha_hash, $usuario_id])) {
                    // Enviar e-mail de confirmação usando a função
                    enviarEmailConfirmacaoSenha($email_usuario, $nome_usuario);
                    
                    $mensagem = 'Senha redefinida com sucesso! Você já pode fazer login com sua nova senha.';
                    $token_valido = false; // Token já foi usado
                } else {
                    $erro = 'Erro ao redefinir senha. Tente novamente.';
                }
            }
        }
    } else {
        $erro = 'Link inválido ou expirado. Solicite uma nova recuperação de senha.';
    }
} else {
    $erro = 'Token não fornecido.';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha - FinanceControl</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .redefinir-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .redefinir-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        
        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .voltar-login {
            text-align: center;
            margin-top: 20px;
        }
        
        .user-info {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #58cc02;
        }
    </style>
</head>
<body>

<header class="header-green">
    <div class="container header-content">
        <div class="logo">
            <a href="index.php">
                <img src="img/FC4.png" alt="FinanceControl Logo">
            </a>
        </div>
        <div class="user-actions">
            <a href="index.php" class="btn btn-outline">Voltar ao Login</a>
        </div>
    </div>
</header>

<div class="container">
    <div class="redefinir-container">
        <h2>Redefinir Senha</h2>
        
        <?php if ($mensagem): ?>
            <div class="alert alert-success">
                <?php echo $mensagem; ?>
            </div>
            <div class="user-info">
                <p><strong>✅ Senha alterada para:</strong> <?php echo htmlspecialchars($email_usuario); ?></p>
            </div>
            <div class="voltar-login">
                <a href="index.php" class="btn btn-primary">Fazer Login</a>
            </div>
        <?php elseif ($erro): ?>
            <div class="alert alert-danger">
                <?php echo $erro; ?>
            </div>
            <div class="voltar-login">
                <a href="recuperar_senha.php" class="btn btn-outline">Solicitar Nova Recuperação</a>
            </div>
        <?php elseif ($token_valido): ?>
            <div class="user-info">
                <p><strong>👤 Redefinindo senha para:</strong> <?php echo htmlspecialchars($email_usuario); ?></p>
            </div>
            
            <?php if ($erro): ?>
                <div class="alert alert-danger">
                    <?php echo $erro; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div style="margin-bottom: 15px;">
                    <label for="nova_senha">Nova Senha:</label>
                    <input type="password" id="nova_senha" name="nova_senha" required 
                           style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 6px;"
                           placeholder="Mínimo 6 caracteres" minlength="6">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label for="confirmar_senha">Confirmar Nova Senha:</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required 
                           style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 6px;"
                           placeholder="Digite a senha novamente">
                </div>
                
                <button type="submit" name="redefinir" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-key"></i> Redefinir Senha
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

</body>
</html>