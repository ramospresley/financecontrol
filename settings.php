<?php
session_start();
require_once 'conexao.php';

// Verificar se usuário está logado
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {
    header('Location: index.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$mensagem = '';
$erro = '';

// Buscar dados do usuário
$stmt = $pdo->prepare("SELECT nome, email, avatar_url FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header('Location: logout.php');
    exit;
}

// Inicializar valores padrão para evitar warnings
$usuario_nome = $usuario['nome'] ?? '';
$usuario_email = $usuario['email'] ?? '';
$usuario_avatar_url = $usuario['avatar_url'] ?? '';

// Processar atualização do perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['atualizar_perfil'])) {
        $novo_nome = trim($_POST['nome']);
        
        if (empty($novo_nome)) {
            $erro = 'O nome não pode estar vazio.';
        } else {
            // Processar upload de imagem
            $avatar_url = $usuario_avatar_url; // Mantém o atual por padrão
            
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $arquivo = $_FILES['avatar'];
                
                // Verificar se é uma imagem
                $check = getimagesize($arquivo["tmp_name"]);
                if ($check === false) {
                    $erro = 'O arquivo não é uma imagem válida.';
                } else {
                    // Verificar tamanho do arquivo (máximo 2MB)
                    if ($arquivo["size"] > 2097152) {
                        $erro = 'A imagem deve ter no máximo 2MB.';
                    } else {
                        // Verificar tipo do arquivo
                        $extensao = strtolower(pathinfo($arquivo["name"], PATHINFO_EXTENSION));
                        $extensoes_permitidas = array("jpg", "jpeg", "png", "gif");
                        
                        if (!in_array($extensao, $extensoes_permitidas)) {
                            $erro = 'Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.';
                        } else {
                            // Criar diretório de uploads se não existir
                            $diretorio_uploads = "uploads/avatars/";
                            if (!is_dir($diretorio_uploads)) {
                                mkdir($diretorio_uploads, 0777, true);
                            }
                            
                            // Gerar nome único para o arquivo
                            $nome_arquivo = "avatar_" . $usuario_id . "_" . time() . "." . $extensao;
                            $caminho_arquivo = $diretorio_uploads . $nome_arquivo;
                            
                            // Mover arquivo para o diretório de uploads
                            if (move_uploaded_file($arquivo["tmp_name"], $caminho_arquivo)) {
                                // Se houver um avatar antigo, excluí-lo
                                if (!empty($usuario_avatar_url) && file_exists($usuario_avatar_url) && strpos($usuario_avatar_url, 'uploads/avatars/') !== false) {
                                    unlink($usuario_avatar_url);
                                }
                                $avatar_url = $caminho_arquivo;
                                $mensagem_upload = 'Imagem enviada com sucesso!';
                            } else {
                                $erro = 'Erro ao fazer upload da imagem.';
                            }
                        }
                    }
                }
            }
            
            if (empty($erro)) {
                $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, avatar_url = ? WHERE id = ?");
                if ($stmt->execute([$novo_nome, $avatar_url, $usuario_id])) {
                    $_SESSION['nome_usuario'] = $novo_nome;
                    $_SESSION['avatar_usuario'] = $avatar_url;
                    $usuario_nome = $novo_nome;
                    $usuario_avatar_url = $avatar_url;
                    $mensagem = 'Perfil atualizado com sucesso!' . (isset($mensagem_upload) ? ' ' . $mensagem_upload : '');
                } else {
                    $erro = 'Erro ao atualizar perfil.';
                }
            }
        }
    }
    
    // Processar troca de senha
    if (isset($_POST['trocar_senha'])) {
        $senha_atual = $_POST['senha_atual'];
        $nova_senha = $_POST['nova_senha'];
        $confirmar_senha = $_POST['confirmar_senha'];
        
        // Verificar senha atual
        $stmt = $pdo->prepare("SELECT senha_hash FROM usuarios WHERE id = ?");
        $stmt->execute([$usuario_id]);
        $dados_usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!password_verify($senha_atual, $dados_usuario['senha_hash'])) {
            $erro = 'Senha atual incorreta.';
        } elseif (strlen($nova_senha) < 6) {
            $erro = 'A nova senha deve ter pelo menos 6 caracteres.';
        } elseif ($nova_senha !== $confirmar_senha) {
            $erro = 'As senhas não coincidem.';
        } else {
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE usuarios SET senha_hash = ? WHERE id = ?");
            if ($stmt->execute([$nova_senha_hash, $usuario_id])) {
                $mensagem = 'Senha alterada com sucesso!';
            } else {
                $erro = 'Erro ao alterar senha.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Configurações - FinanceControl</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .settings-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .settings-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .settings-card h2 {
            color: #333;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #58cc02;
        }
        
        .avatar-preview {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .avatar-img {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #58cc02;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #58cc02;
        }
        
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        
        .file-input-wrapper input[type=file] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }
        
        .file-input-label {
            display: block;
            padding: 12px;
            background: #f8f9fa;
            border: 2px dashed #ddd;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-input-label:hover {
            border-color: #58cc02;
            background: #e8f5e8;
        }
        
        .file-info {
            margin-top: 8px;
            font-size: 12px;
            color: #666;
        }
        
        .current-avatar-info {
            background: #e8f5e8;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
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
        
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include 'includes_header.php'; ?>
    <!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <div class="settings-container">
        <h1 style="text-align: center; margin-bottom: 30px; color: #333;">Configurações da Conta</h1>
        
        <?php if ($mensagem): ?>
            <div class="alert alert-success">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($erro): ?>
            <div class="alert alert-danger">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <!-- Card de Perfil -->
        <div class="settings-card">
            <h2><i class="fas fa-user"></i> Perfil do Usuário</h2>
            
            <div class="avatar-preview">
                <?php if (!empty($usuario_avatar_url) && file_exists($usuario_avatar_url)): ?>
                    <img src="<?php echo htmlspecialchars($usuario_avatar_url); ?>" 
                         alt="Avatar" class="avatar-img" id="avatarPreview">
                <?php else: ?>
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($usuario_nome); ?>&background=58cc02&color=fff&size=150" 
                         alt="Avatar" class="avatar-img" id="avatarPreview">
                <?php endif; ?>
                <div>
                    <h3><?php echo htmlspecialchars($usuario_nome); ?></h3>
                    <p><?php echo htmlspecialchars($usuario_email); ?></p>
                </div>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario_nome); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="avatar">Alterar Avatar</label>
                    
                    <?php if (!empty($usuario_avatar_url) && file_exists($usuario_avatar_url)): ?>
                    <div class="current-avatar-info">
                        <strong>Avatar atual:</strong> <?php echo basename($usuario_avatar_url); ?>
                        <br><small>Selecione uma nova imagem para substituir</small>
                    </div>
                    <?php endif; ?>
                    
                    <div class="file-input-wrapper">
                        <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/gif">
                        <label for="avatar" class="file-input-label">
                            <i class="fas fa-cloud-upload-alt"></i> 
                            Clique para selecionar uma imagem ou arraste aqui
                        </label>
                    </div>
                    <div class="file-info">
                        <strong>Formatos aceitos:</strong> JPG, PNG, GIF (máximo 2MB)<br>
                        <strong>Tamanho recomendado:</strong> Imagem quadrada com tamanho máximo de 75x75 pixels<br>
                        <strong>Dica:</strong> Use imagens quadradas para melhor resultado
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" name="atualizar_perfil" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                    <a href="dashboard.php" class="btn btn-outline">Cancelar</a>
                </div>
            </form>
        </div>

        <!-- Card de Segurança -->
        <div class="settings-card">
            <h2><i class="fas fa-lock"></i> Segurança</h2>
            
            <form method="POST">
                <div class="form-group">
                    <label for="senha_atual">Senha Atual</label>
                    <input type="password" id="senha_atual" name="senha_atual" required>
                </div>
                
                <div class="form-group">
                    <label for="nova_senha">Nova Senha</label>
                    <input type="password" id="nova_senha" name="nova_senha" required minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Nova Senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required>
                </div>

                <div class="btn-group">
                    <button type="submit" name="trocar_senha" class="btn btn-primary">
                        <i class="fas fa-key"></i> Alterar Senha
                    </button>
                </div>
            </form>
        </div>

        <!-- Card de Informações -->
        <div class="settings-card">
            <h2><i class="fas fa-info-circle"></i> Informações da Conta</h2>
            
            <div class="form-group">
                <label>ID do Usuário</label>
                <input type="text" value="<?php echo $usuario_id; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" value="<?php echo htmlspecialchars($usuario_email); ?>" readonly>
                <small>Para alterar o e-mail, entre em contato com o suporte.</small>
            </div>
            
            <div class="form-group">
                <label>Data de Cadastro</label>
                <input type="text" value="Informação não disponível" readonly>
            </div>
        </div>
    </div>

    <script>
        // Preview da imagem selecionada
        document.getElementById('avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Drag and drop
        const fileInput = document.getElementById('avatar');
        const fileLabel = document.querySelector('.file-input-label');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileLabel.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            fileLabel.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            fileLabel.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            fileLabel.style.borderColor = '#58cc02';
            fileLabel.style.background = '#e8f5e8';
        }
        
        function unhighlight() {
            fileLabel.style.borderColor = '#ddd';
            fileLabel.style.background = '#f8f9fa';
        }
        
        fileLabel.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            
            // Disparar evento change para mostrar preview
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    </script>
</body>
</html>