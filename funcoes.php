<?php
// Função para calcular vidas recuperadas com base no tempo
function calcularVidas($vidas, $ultimaVidaPerdida) {
    if ($vidas >= 5 || !$ultimaVidaPerdida) return $vidas;

    $agora = new DateTime();
    $ultima = new DateTime($ultimaVidaPerdida);
    $intervalo = $ultima->diff($agora);
    $horasPassadas = $intervalo->h + ($intervalo->days * 24);

    $vidasRecuperadas = floor($horasPassadas / 2);
    return min(5, $vidas + $vidasRecuperadas);
}

// Função para gerar conquistas com base no progresso
function gerarConquistas($faseConcluida, $xp, $totalFases, $trilhaPaga) {
    $conquistas = [];

    if ($faseConcluida >= 1) $conquistas[] = "🎯 Primeira fase concluída";
    if ($xp >= 500) $conquistas[] = "💎 500 XP acumulados";
    if ($faseConcluida >= $totalFases) $conquistas[] = "🏁 Trilha concluída";
    if ($xp >= 200) $conquistas[] = "❤️ Recuperou uma vida com XP";
    if ($trilhaPaga && $faseConcluida >= $totalFases) $conquistas[] = "🔓 Concluiu trilha premium";

    return $conquistas;
}

// Função para enviar e-mail (versão simplificada - apenas para produção)
function enviarEmail($para, $assunto, $mensagem, $de = 'noreply@financecontrol.com') {
    // EM DESENVOLVIMENTO LOCAL, RETORNA FALSE PARA EVITAR ERROS
    return false;
    
    /* 
    // DESCOMENTE ESTE CÓDIGO APENAS EM PRODUÇÃO:
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: FinanceControl <{$de}>" . "\r\n";
    $headers .= "Reply-To: contato@financecontrol.com" . "\r\n";
    
    return mail($para, $assunto, $mensagem, $headers);
    */
}

// Função para enviar e-mail de recuperação de senha
function enviarEmailRecuperacao($email, $nome, $token) {
    // Em desenvolvimento, sempre retorna false
    return false;
}

// Função para enviar e-mail de confirmação de alteração de senha
function enviarEmailConfirmacaoSenha($email, $nome) {
    // Em desenvolvimento, sempre retorna false
    return false;
}
?>