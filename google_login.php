<?php
session_start();

// Captura o token antes de destruir a sessão
$googleToken = $_SESSION['google_access_token'] ?? null;

// Limpa sessão local
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
    $revoke_url = 'https://accounts.google.com/o/oauth2/revoke?token=' . $googleToken;
    @file_get_contents($revoke_url);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Saindo...</title>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      padding-top: 100px;
      background-color: #f9f9f9;
    }
    .logout-message {
      font-size: 1.2em;
      color: #333;
    }
  </style>
</head>
<body>
  <div class="logout-message">Encerrando sessão, aguarde...</div>

  <script>
    function logoutGoogle() {
      try {
        google.accounts.id.disableAutoSelect();
        google.accounts.id.revoke(localStorage.getItem('email'), done => {
          console.log('Consentimento revogado para: ' + localStorage.getItem('email'));
        });
      } catch (e) {
        console.warn('Google revoke falhou:', e);
      }

      localStorage.removeItem('google_token');
      localStorage.removeItem('email');
      sessionStorage.clear();

      window.location.href = 'https://accounts.google.com/Logout?continue=https://appengine.google.com/_ah/logout?continue=' + encodeURIComponent(window.location.origin);
    }

    setTimeout(() => {
      logoutGoogle();
      setTimeout(() => {
        window.location.href = 'index.php';
      }, 1500);
    }, 500);
  </script>
</body>
</html>
