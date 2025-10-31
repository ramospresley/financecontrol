<?php
session_start();

// Captura o token do Google antes de destruir a sessão
$googleToken = $_SESSION['google_access_token'] ?? null;
$emailGoogle = $_SESSION['email_google'] ?? null;

// Limpa variáveis de sessão
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Limpa cookies do Google
setcookie('g_csrf_token', '', time() - 3600, '/');
setcookie('g_state', '', time() - 3600, '/');

// Revoga token do Google se existir
if ($googleToken) {
    $revoke_url = 'https://accounts.google.com/o/oauth2/revoke?token=' . urlencode($googleToken);
    @file_get_contents($revoke_url);
}

// Gera página silenciosa com script para desativar auto login e redirecionar
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Saindo...</title>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>
<script>
  // Desativa seleção automática
  google.accounts.id.disableAutoSelect();

  // Revoga consentimento local (se houver email armazenado)
  const email = localStorage.getItem('email');
  if (email) {
    google.accounts.id.revoke(email, done => {
      console.log('Consentimento revogado para: ' + email);
    });
  }

  // Limpa armazenamento local
  localStorage.removeItem('email');
  localStorage.removeItem('google_token');
  sessionStorage.clear();

  // Redireciona imediatamente
  window.location.href = 'index.php';
</script>
</body>
</html>
