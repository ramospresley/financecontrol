<?php
session_start();
require_once 'conexao.php';
require_once 'funcoes.php';

$mensagem = '';
$erro = '';
$link_desenvolvimento = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recuperar'])) {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    
    if (!$email) {
        $erro = 'Por favor, insira um e-mail válido.';
    } else {
        // Verificar se o e-mail existe
        $stmt = $pdo->prepare("SELECT id, nome FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario) {
            // Gerar token de recuperação
            $token = bin2hex(random_bytes(32));
            $expiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Salvar token no banco
            $stmt = $pdo->prepare("UPDATE usuarios SET token_recuperacao = ?, token_expiracao = ? WHERE email = ?");
            $stmt->execute([$token, $expiracao, $email]);
            
            // MODO DESENVOLVIMENTO: Mostrar o link na tela
            $link_recuperacao = "http://" . $_SERVER['HTTP_HOST'] . "/financecontrol/redefinir_senha.php?token=" . $token;
            $link_desenvolvimento = $link_recuperacao;
            
            $mensagem = "✅ Link de recuperação gerado com sucesso!";
            
            // COMENTE a linha abaixo para não tentar enviar e-mail
            // enviarEmailRecuperacao($email, $usuario['nome'], $token);
            
        } else {
            $erro = 'E-mail não encontrado em nosso sistema.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha - FinanceControl</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .recuperar-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .recuperar-container h2 {
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
        
        .link-desenvolvimento {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #58cc02;
            word-break: break-all;
        }
        
        .link-desenvolvimento a {
            color: #58cc02;
            font-weight: bold;
            text-decoration: none;
        }
        
        .link-desenvolvimento a:hover {
            text-decoration: underline;
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
    <div class="recuperar-container">
        <h2>Recuperar Senha</h2>
        
        <?php if ($mensagem): ?>
            <div class="alert alert-success">
                <?php echo $mensagem; ?>
            </div>
            
            <?php if ($link_desenvolvimento): ?>
                <div class="link-desenvolvimento">
                    <p><strong>🔗 Link de Recuperação (Modo Desenvolvimento):</strong></p>
                    <a href="<?php echo $link_desenvolvimento; ?>" target="_blank">
                        <?php echo $link_desenvolvimento; ?>
                    </a>
                    <p style="margin-top: 10px; font-size: 14px; color: #666;">
                        <strong>Nota:</strong> Em produção, este link seria enviado por e-mail automaticamente.
                        <br>Clique no link acima para redefinir sua senha.
                    </p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if ($erro): ?>
            <div class="alert alert-danger">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!$mensagem): ?>
            <form method="POST">
                <div style="margin-bottom: 15px;">
                    <label for="email">Digite seu e-mail cadastrado:</label>
                    <input type="email" id="email" name="email" required 
                           style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 6px;"
                           placeholder="seu@email.com">
                </div>
                
                <button type="submit" name="recuperar" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-paper-plane"></i> Gerar Link de Recuperação
                </button>
            </form>
        <?php endif; ?>
        
        <div class="voltar-login">
            <a href="index.php">← Voltar para o login</a>
        </div>
    </div>
</div>

</body>
</html>