<?php
$host = 'localhost';
$usuario = 'root'; // ajuste conforme seu MySQL
$senha = '';       // ajuste conforme sua senha
$banco = 'financecontrol';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8mb4", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
