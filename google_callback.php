<?php
session_start();
require_once 'conexao.php';

// Verifica se o código de autorização foi recebido
if (!isset($_GET['code'])) {
    die('Código de autorização ausente.');
}

$code = $_GET['code'];

// Carregar credenciais do arquivo JSON
$credenciais = json_decode(file_get_contents(__DIR__ . '/client_secret_2_672165073469-06j44lan63o8vrsagiv6ko2ksctibc1f.apps.googleusercontent.com.json'), true);

$client_id = $credenciais['web']['client_id'];
$client_secret = $credenciais['web']['client_secret'];
$redirect_uri = $credenciais['web']['redirect_uris'][0];

// ======== ETAPA 1: OBTER O TOKEN =========
$token_url = "https://oauth2.googleapis.com/token";
$data = [
    'code' => $code,
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'redirect_uri' => $redirect_uri,
    'grant_type' => 'authorization_code'
];

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

$response = curl_exec($ch);
curl_close($ch);

$token_data = json_decode($response, true);

if (!isset($token_data['access_token'])) {
    die("Falha ao obter o token de acesso. Resposta: " . $response);
}

// ======== ETAPA 2: OBTER DADOS DO USUÁRIO =========
$access_token = $token_data['access_token'];
$user_info_url = "https://www.googleapis.com/oauth2/v2/userinfo?access_token=" . $access_token;

$user_info = json_decode(file_get_contents($user_info_url), true);

if (!isset($user_info['email'])) {
    die("Não foi possível obter informações do usuário. Resposta: " . json_encode($user_info));
}

$nome = $user_info['name'] ?? 'Usuário Google';
$email = $user_info['email'];
$google_id = $user_info['id'] ?? null;

// ======== ETAPA 3: VERIFICAR OU INSERIR NO BANCO =========
$stmt = $pdo->prepare("SELECT id, nome FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    $usuario_id = $usuario['id'];
    $nome = $usuario['nome'];
} else {
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, google_id) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $email, $google_id]);
    $usuario_id = $pdo->lastInsertId();
}

// ======== ETAPA 4: CRIAR SESSÃO =========
$_SESSION['usuario_logado'] = true;
$_SESSION['nome_usuario'] = $nome;
$_SESSION['usuario_id'] = $usuario_id;
$_SESSION['email_google'] = $email;
$_SESSION['google_access_token'] = $access_token;
$_SESSION['usuario_admin'] = ($usuario['tipo_usuario'] === 'admin');

// ======== ETAPA 5: REDIRECIONAR =========
header('Location: dashboard.php');
exit;
?>
