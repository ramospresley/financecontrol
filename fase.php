<?php
session_start();
require_once 'conexao.php';

$usuarioId = $_SESSION['usuario_id'] ?? null;
if (!$usuarioId) {
    header('Location: index.php');
    exit;
}

$trilhaId = $_GET['trilha'] ?? null;
$fase = $_GET['fase'] ?? 1;

if (!is_numeric($trilhaId) || !is_numeric($fase) || $trilhaId < 1 || $fase < 1) {
    header('Location: aprender.php');
    exit;
}

$trilhaId = (int)$trilhaId;
$fase = (int)$fase;

// Buscar trilha
$stmt = $pdo->prepare("SELECT id, titulo, total_fases, trilha_paga FROM trilha_conhecimento WHERE id = ?");
$stmt->execute([$trilhaId]);
$trilha = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trilha) {
    echo "<script>alert('Trilha não encontrada!'); window.location.href = 'aprender.php';</script>";
    exit;
}

// Buscar ou criar progresso
$stmt = $pdo->prepare("SELECT fase_concluida, xp, vidas, ultima_vida_perdida 
                       FROM progresso_aprendizado 
                       WHERE usuario_id = ? AND trilha_id = ?");
$stmt->execute([$usuarioId, $trilhaId]);
$progresso = $stmt->fetch(PDO::FETCH_ASSOC);

// Se não existe progresso, criar um
if (!$progresso) {
    $stmt = $pdo->prepare("
        INSERT INTO progresso_aprendizado (usuario_id, trilha_id, fase_concluida, xp, vidas, ultima_vida_perdida) 
        VALUES (?, ?, 0, 0, 5, NULL)
    ");
    $stmt->execute([$usuarioId, $trilhaId]);
    
    $progresso = [
        'fase_concluida' => 0, 
        'xp' => 0, 
        'vidas' => 5, 
        'ultima_vida_perdida' => null
    ];
}

// Função de cálculo de vidas
function calcularVidas($vidas, $ultimaVidaPerdida) {
    if ($vidas >= 5 || !$ultimaVidaPerdida) return $vidas;
    $agora = new DateTime();
    $ultima = new DateTime($ultimaVidaPerdida);
    $intervalo = $ultima->diff($agora);
    $horasPassadas = $intervalo->h + ($intervalo->days * 24);
    $vidasRecuperadas = floor($horasPassadas / 2);
    return min(5, $vidas + $vidasRecuperadas);
}

$vidas = calcularVidas((int)$progresso['vidas'], $progresso['ultima_vida_perdida']);
$xp = (int)$progresso['xp'];

// Verificação simplificada - todas as fases desbloqueadas
$acessoLiberado = true;
$fasePermitida = ($fase <= $trilha['total_fases']);

if (!$acessoLiberado || $vidas <= 0 || !$fasePermitida) {
    echo "<script>alert('Acesso não permitido!'); window.location.href = 'aprender.php';</script>";
    exit;
}

// Buscar conteúdo
$stmt = $pdo->prepare("SELECT * FROM conteudo_fases WHERE trilha_id = ? AND fase_numero = ?");
$stmt->execute([$trilhaId, $fase]);
$conteudo = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar quiz
$stmt = $pdo->prepare("SELECT * FROM quiz_fases WHERE trilha_id = ? AND fase_numero = ?");
$stmt->execute([$trilhaId, $fase]);
$quiz = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Processar envio
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acertos = 0;
    $erros = 0;

    foreach ($quiz as $index => $q) {
        $resposta = $_POST['resposta_' . $index] ?? '';
        if ($resposta === $q['resposta_correta']) {
            $acertos++;
        } else {
            $erros++;
        }
    }

    // XP e vidas
    $xpGanhos = $acertos * 50;
    $vidasPerdidas = $erros;
    $vidasRestantes = max(0, $vidas - $vidasPerdidas);

    $xpTotal = $xp + $xpGanhos;
    $vidasRecuperadas = floor($xpTotal / 200);
    $vidasFinal = min(5, $vidasRestantes + $vidasRecuperadas);
    $xpFinal = $xpTotal % 200;

    // Atualização de fase - SEMPRE avança se acertar todas
    $novaFase = $progresso['fase_concluida'];
    if ($acertos === count($quiz) && $fase >= $progresso['fase_concluida']) {
        $novaFase = $fase;
    }

    $stmt = $pdo->prepare("
        UPDATE progresso_aprendizado 
        SET fase_concluida = ?, xp = ?, vidas = ?, ultima_vida_perdida = ?
        WHERE usuario_id = ? AND trilha_id = ?
    ");
    $stmt->execute([
        $novaFase,
        $xpFinal,
        $vidasFinal,
        $vidasFinal < $vidas ? date('Y-m-d H:i:s') : $progresso['ultima_vida_perdida'],
        $usuarioId,
        $trilhaId
    ]);

    // Redireciona para a próxima fase se completou o quiz
    if ($acertos === count($quiz) && $fase < $trilha['total_fases']) {
        header("Location: fase.php?trilha=$trilhaId&fase=" . ($fase + 1));
    } else {
        header("Location: aprender.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Fase <?php echo $fase; ?> - <?php echo htmlspecialchars($trilha['titulo']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'includes_header.php'; ?>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="container">
    <h2>Fase <?php echo $fase; ?> - 
        <?php echo htmlspecialchars($conteudo['titulo_fase'] ?? 'Sem título'); ?>
    </h2>

    <div class="status-atual">
        <span class="xp-badge">💎 <?php echo $xp; ?> XP</span>
        <span class="vidas-badge">
            <?php for ($i = 0; $i < 5; $i++): ?>
                <span style="font-size: 20px; color:<?php echo $i < $vidas ? '#e63946' : '#ccc'; ?>;">❤️</span>
            <?php endfor; ?>
        </span>
    </div>

    <div class="card-box">
        <p><?php echo nl2br(htmlspecialchars($conteudo['texto_principal'] ?? 'Conteúdo indisponível para esta fase.')); ?></p>
    </div>

    <?php if (!empty($conteudo['video_url'])): ?>
    <div class="card-box">
        
        <?php
        $videoUrl = $conteudo['video_url'];
        
        // Verificar se é URL do YouTube
        if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false) {
            // Converter URL do YouTube para embed
            if (strpos($videoUrl, 'youtu.be') !== false) {
                $videoId = substr(parse_url($videoUrl, PHP_URL_PATH), 1);
            } else {
                parse_str(parse_url($videoUrl, PHP_URL_QUERY), $params);
                $videoId = $params['v'] ?? '';
            }
            if (!empty($videoId)) {
                $embedUrl = "https://www.youtube.com/embed/{$videoId}";
                ?>
                <iframe src="<?php echo htmlspecialchars($embedUrl); ?>" 
                        style="width:100%; height:400px;" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen></iframe>
                <?php
            }
        } 
        // Verificar se é URL do Vimeo
        elseif (strpos($videoUrl, 'vimeo.com') !== false) {
            ?>
            <iframe src="<?php echo htmlspecialchars($videoUrl); ?>" 
                    style="width:100%; height:400px;" 
                    frameborder="0" 
                    allow="autoplay; fullscreen; picture-in-picture" 
                    allowfullscreen></iframe>
            <?php
        }
        // Verificar se é vídeo local
        elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($videoUrl, '/'))) {
            ?>
            <video controls style="width:100%; max-width:600px;">
                <source src="<?php echo htmlspecialchars($videoUrl); ?>" type="video/mp4">
                Seu navegador não suporta o elemento de vídeo.
            </video>
            <?php
        }
        // Se não for nenhum dos acima, tentar exibir diretamente
        else {
            ?>
            <video controls style="width:100%; max-width:600px;">
                <source src="<?php echo htmlspecialchars($videoUrl); ?>" type="video/mp4">
                Seu navegador não suporta o elemento de vídeo.
            </video>
            <?php
        }
        ?>
    </div>
    <?php endif; ?>

    <?php if ($quiz): ?>
    <form method="POST">
        <h3>Quiz da Fase (<?php echo count($quiz); ?> perguntas)</h3>
        <?php foreach ($quiz as $index => $q): 
            $opcoes = json_decode($q['opcoes'], true) ?? [];
            shuffle($opcoes); // embaralha as alternativas a cada acesso
        ?>
        <div class="card-box">
            <p><strong>Pergunta <?php echo $index + 1; ?>: <?php echo htmlspecialchars($q['pergunta']); ?></strong></p>
            <?php if (!empty($opcoes)): ?>
                <?php foreach ($opcoes as $opcao): ?>
                    <label style="display: block; margin: 5px 0;">
                        <input type="radio" 
                               name="resposta_<?php echo $index; ?>" 
                               value="<?php echo htmlspecialchars($opcao); ?>"
                               required>
                        <?php echo htmlspecialchars($opcao); ?>
                    </label>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Opções não disponíveis para esta pergunta.</p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary">Enviar Respostas e Avançar</button>
    </form>
    <?php else: ?>
        <div class="card-box">
            <p>Nenhum quiz disponível para esta fase.</p>
            <a href="aprender.php" class="btn btn-primary">Voltar para Trilhas</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes_footer.php'; ?>
</body>
</html>