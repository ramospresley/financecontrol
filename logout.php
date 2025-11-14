<?php
session_start();

// ======== ETAPA 1: REVOGAR TOKEN DO GOOGLE (se existir) =========
if (isset($_SESSION['google_access_token'])) {
    $access_token = $_SESSION['google_access_token'];
    
    // URL para revogar o token do Google
    $revoke_url = "https://accounts.google.com/o/oauth2/revoke?token=" . $access_token;
    
    // Fazer requisição para revogar o token
    $ch = curl_init($revoke_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_exec($ch);
    curl_close($ch);
}

// ======== ETAPA 2: LIMPAR VARIÁVEIS DE SESSÃO =========
$_SESSION = [];

// ======== ETAPA 3: REMOVER COOKIES DE SESSÃO =========
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// ======== ETAPA 4: DESTRUIR SESSÃO =========
session_destroy();

// ======== ETAPA 5: REDIRECIONAR =========
header('Location: index.php');
exit;
?>