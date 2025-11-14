<?php
session_start();
require_once 'conexao.php';

$paginaAtual = 'dashboard';
$tituloPagina = 'Dashboard - FinanceControl';

$usuarioLogado = isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'];
$usuarioId = $_SESSION['usuario_id'] ?? null;
$nomeUsuario = $_SESSION['nome_usuario'] ?? 'Visitante';

if (!$usuarioLogado || !$usuarioId) {
    header('Location: index.php');
    exit;
}

// Mês selecionado
$mesSelecionado = isset($_GET['mes']) && is_numeric($_GET['mes']) ? $_GET['mes'] : date('m');
$anoAtual = date('Y');

// Buscar transações do mês
$stmt = $pdo->prepare("SELECT * FROM transacoes WHERE usuario_id = ? AND MONTH(data) = ? AND YEAR(data) = ?");
$stmt->execute([$usuarioId, $mesSelecionado, $anoAtual]);
$transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cálculos
$totalReceitas = $totalDespesas = $investimentos = 0;
$categoriasDespesas = $categoriasReceitas = $categoriasInvestimentos = [];

foreach ($transacoes as $t) {
    $cat = isset($t['categoria']) ? ucfirst(str_replace('_', ' ', $t['categoria'])) : 'Outros';
    $valor = floatval($t['valor']);

    switch ($t['tipo']) {
        case 'receita':
            $totalReceitas += $valor;
            $categoriasReceitas[$cat] = ($categoriasReceitas[$cat] ?? 0) + $valor;
            break;
        case 'despesa':
            $totalDespesas += $valor;
            $categoriasDespesas[$cat] = ($categoriasDespesas[$cat] ?? 0) + $valor;
            break;
        case 'investimento':
            $investimentos += $valor;
            $categoriasInvestimentos[$cat] = ($categoriasInvestimentos[$cat] ?? 0) + $valor;
            break;
    }
}

$saldo = $totalReceitas - ($totalDespesas + $investimentos);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($tituloPagina); ?></title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include 'includes_header.php'; ?>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <section class="dashboard">
        <div class="container">
            <h2 style="margin-top: 20px;">Olá, <?php echo htmlspecialchars($nomeUsuario); ?>!</h2>
            <h3>Seu Dashboard Financeiro</h3>

            <!-- Filtro de mês -->
            <form method="GET" class="filtro-mes">
                <label for="mes">Selecionar mês:</label>
                <select name="mes" id="mes" onchange="this.form.submit()">
                    <?php
                    $nomesMeses = [
                        1 => 'Janeiro',
                        2 => 'Fevereiro',
                        3 => 'Março',
                        4 => 'Abril',
                        5 => 'Maio',
                        6 => 'Junho',
                        7 => 'Julho',
                        8 => 'Agosto',
                        9 => 'Setembro',
                        10 => 'Outubro',
                        11 => 'Novembro',
                        12 => 'Dezembro'
                    ];
                    foreach ($nomesMeses as $num => $nome) {
                        $selected = ($num == $mesSelecionado) ? 'selected' : '';
                        echo "<option value='$num' $selected>$nome</option>";
                    }
                    ?>
                </select>
            </form>

            <!-- Botão para relatórios -->
            <div style="margin-top: 20px; margin-bottom: 20px;">
                <a href="relatorios.php" class="btn btn-outline">Ver Relatórios Detalhados</a>
            </div>

            <!-- Resumo financeiro -->
            <div class="dashboard-container">
                <div class="dashboard-summary">
                    <h2>Resumo Financeiro</h2>
                    <div class="dashboard-cards">
                        <div class="card">
                            <h3>Receitas</h3>
                            <p>R$ <?php echo number_format($totalReceitas, 2, ',', '.'); ?></p>
                        </div>
                        <div class="card">
                            <h3>Despesas</h3>
                            <p>R$ <?php echo number_format($totalDespesas, 2, ',', '.'); ?></p>
                        </div>
                        <div class="card">
                            <h3>Investimentos</h3>
                            <p>R$ <?php echo number_format($investimentos, 2, ',', '.'); ?></p>
                        </div>
                        <div class="card">
                            <h3>Saldo Atual</h3>
                            <p>R$ <?php echo number_format($saldo, 2, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="dashboard-quotes">
                    <h2>Cotações do Mercado Financeiro</h2>
                    <div id="cotacoes">
                            <h3>Cotações</h3>
                            <?php
                            // Função para buscar cotações via API (server-side)
                            function buscar_cotacoes(array $symbols, string $token = '') {
                                $query = implode(',', $symbols);
                                // Usando formato simples conforme o curl exemplo: https://brapi.dev/api/quote/PETR4,MGLU3
                                $url = "https://brapi.dev/api/quote/{$query}";

                                // Simple file-based cache
                                $cacheDir = __DIR__ . DIRECTORY_SEPARATOR . 'cache';
                                if (!is_dir($cacheDir)) {
                                    @mkdir($cacheDir, 0755, true);
                                }
                                $cacheKey = 'brapi_' . md5($url . $token);
                                $cacheFile = $cacheDir . DIRECTORY_SEPARATOR . $cacheKey . '.json';
                                $cacheTtl = 300; // 5 minutos

                                // Se cache existe e está fresco, retorne imediatamente
                                if (is_file($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
                                    $cached = file_get_contents($cacheFile);
                                    $decoded = json_decode($cached, true);
                                    if (json_last_error() === JSON_ERROR_NONE) {
                                        return ['data' => $decoded, 'from_cache' => true];
                                    }
                                }

                                // Tentativas (retries) em caso de 429 (rate limit)
                                $maxAttempts = 3;
                                $attempt = 0;
                                $lastErr = null;
                                $resp = null;
                                $httpCode = 0;

                                while ($attempt < $maxAttempts) {
                                    $attempt++;
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                                    if (!empty($token)) {
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer {$token}"]); 
                                    }
                                    $resp = curl_exec($ch);
                                    $err = curl_error($ch);
                                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                    curl_close($ch);

                                    if ($resp === false) {
                                        $lastErr = "cURL error: {$err}";
                                        // Se houver cache, pode retornar imediatamente
                                        if (is_file($cacheFile)) {
                                            $cached = file_get_contents($cacheFile);
                                            $decoded = json_decode($cached, true);
                                            if (json_last_error() === JSON_ERROR_NONE) {
                                                return ['data' => $decoded, 'from_cache' => true, 'warning' => $lastErr];
                                            }
                                        }
                                        // caso contrário, não faz retry infinito — tenta novamente
                                        sleep(1);
                                        continue;
                                    }

                                    if ($httpCode == 429) {
                                        // rate limit: tenta novamente com backoff curto
                                        $lastErr = "HTTP 429";
                                        // se cache existe, podemos usá-lo em vez de aguardar
                                        if (is_file($cacheFile)) {
                                            $cached = file_get_contents($cacheFile);
                                            $decoded = json_decode($cached, true);
                                            if (json_last_error() === JSON_ERROR_NONE) {
                                                return ['data' => $decoded, 'from_cache' => true, 'warning' => 'HTTP 429 - usando cache'];
                                            }
                                        }
                                        // backoff exponencial curto
                                        sleep($attempt);
                                        continue;
                                    }

                                    // se outro HTTP não 2xx, não tentamos novamente
                                    if ($httpCode < 200 || $httpCode >= 300) {
                                        return ['error' => "HTTP error: {$httpCode}", 'body' => $resp];
                                    }

                                    // sucesso
                                    break;
                                }

                                if ($resp === null) {
                                    return ['error' => ($lastErr ?? 'Erro desconhecido ao chamar API')];
                                }

                                $data = json_decode($resp, true);
                                if (json_last_error() !== JSON_ERROR_NONE) {
                                    return ['error' => 'JSON decode error: ' . json_last_error_msg(), 'body' => $resp];
                                }

                                // grava no cache para próximas requisições
                                @file_put_contents($cacheFile, json_encode($data));

                                return ['data' => $data];
                            }

                            // Símbolos a buscar (ajuste conforme necessário)
                            $symbols = ['PETR4', 'MGLU3', 'VALE3', 'ITUB4'];
                            // Token criptografado (base64) - será decriptado em runtime
                            // Observação: a passphrase abaixo é usada apenas como exemplo. Para maior segurança, altere-a.
                            $encTokenB64 = 'VAhjyBSp16SGfaBZ//Gk+nNGN7rOX8WtICqO7eC74sI='; // ciphertext
                            $ivB64 = 'hieq5lDe0a7XCr4UTm0d+A==';
                            $passphrase = 'change_this_api_token_key';
                            $method = 'AES-256-CBC';
                            $key = hash('sha256', $passphrase, true);
                            $decoded = base64_decode($encTokenB64);
                            $iv = base64_decode($ivB64);
                            $apiToken = '';
                            if ($decoded !== false && $iv !== false) {
                                $decrypted = @openssl_decrypt($decoded, $method, $key, OPENSSL_RAW_DATA, $iv);
                                if ($decrypted !== false) {
                                    $apiToken = $decrypted;
                                }
                            }

                            $resultado = buscar_cotacoes($symbols, $apiToken);

                            if (isset($resultado['error'])) {
                                echo '<p>Não foi possível obter as cotações: ' . htmlspecialchars($resultado['error']) . '</p>';
                            } else {
                                $payload = $resultado['data'];
                                // A API retorna um objeto com 'results' contendo cotações por símbolo
                                $results = $payload['results'] ?? $payload['stocks'] ?? null;
                                if (empty($results) && isset($payload['results'])) $results = $payload['results'];

                                if (!empty($results) && is_array($results)) {
                                    // Prepara dados para o JS (cards + gráficos)
                                    $quotesForJs = [];
                                    foreach ($results as $item) {
                                        $symbol = $item['symbol'] ?? ($item['shortName'] ?? 'N/A');
                                        // Tenta pegar último preço de fechamento
                                        $price = null;
                                        if (isset($item['regularMarketPrice'])) {
                                            $price = $item['regularMarketPrice'];
                                        } elseif (isset($item['close'])) {
                                            $price = is_array($item['close']) ? end($item['close']) : $item['close'];
                                        } elseif (isset($item['previousClose'])) {
                                            $price = $item['previousClose'];
                                        } elseif (isset($item['price'])) {
                                            $price = $item['price'];
                                        }

                                        // tenta pegar série de preços para sparkline
                                        $series = [];
                                        if (isset($item['close']) && is_array($item['close'])) {
                                            $series = $item['close'];
                                        } elseif (isset($item['historical']) && is_array($item['historical'])) {
                                            // algumas APIs retornam histórico em 'historical' como array de objetos com 'close'
                                            foreach ($item['historical'] as $h) {
                                                if (isset($h['close'])) $series[] = $h['close'];
                                            }
                                        } elseif (isset($item['chart']) && is_array($item['chart'])) {
                                            foreach ($item['chart'] as $c) {
                                                if (isset($c['close'])) $series[] = $c['close'];
                                            }
                                        }

                                        $quotesForJs[] = [
                                            'symbol' => $symbol,
                                            'price' => $price,
                                            'series' => $series
                                        ];
                                    }

                                    // Render HTML de cards e gráfico geral
                                    echo '<div class="stocks-panel">';
                                    echo '<div class="stocks-grid">';
                                    foreach ($quotesForJs as $q) {
                                        $id = preg_replace('/[^a-zA-Z0-9_]/', '_', $q['symbol']);
                                        $priceStr = $q['price'] !== null ? 'R$ ' . number_format((float)$q['price'], 2, ',', '.') : 'N/A';
                                        echo '<div class="stock-card">';
                                        echo '<div class="stock-card-header"><strong>' . htmlspecialchars($q['symbol']) . '</strong><span class="stock-price">' . htmlspecialchars($priceStr) . '</span></div>';
                                        echo '<canvas id="spark_' . $id . '" width="160" height="40"></canvas>';
                                        echo '</div>';
                                    }
                                    echo '</div>'; // stocks-grid

                                    // gráfico geral — comparação de preços atuais
                                    echo '<div class="stocks-overview">';
                                    echo '<h4>Visão Geral</h4>';
                                    echo '<canvas id="stocksOverview" width="600" height="160"></canvas>';
                                    echo '</div>';

                                    echo '</div>'; // stocks-panel

                                    // Passa dados para JS
                                    echo '<script>window.__quotesData = ' . json_encode($quotesForJs) . ';</script>';

                                    // Inclui estilos rápidos para os cards (pode mover para style.css)
                                    echo '<style>.stocks-panel{display:flex;flex-direction:column;gap:12px}.stocks-grid{display:flex;gap:12px;flex-wrap:wrap}.stock-card{background:#fff;border:1px solid #e1e1e1;border-radius:6px;padding:8px;width:180px;box-shadow:0 1px 3px rgba(0,0,0,0.04)}.stock-card-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;font-size:14px}.stock-price{color:#0b6b0b;font-weight:600}.stocks-overview{background:#fff;padding:10px;border:1px solid #e1e1e1;border-radius:6px}</style>';

                                    // Script para desenhar sparklines e overview (HEREDOC para evitar problemas de escape)
                                    $script = <<<JS
<script>
(function(){
    const quotes = window.__quotesData || [];
    // cria sparklines
    quotes.forEach(q => {
        const id = q.symbol.replace(/[^a-zA-Z0-9_]/g, "_");
        const canvas = document.getElementById("spark_"+id);
        if (!canvas) return;
        const data = (q.series && q.series.length) ? q.series.slice(-20) : [q.price || 0];
        new Chart(canvas.getContext("2d"), {
            type: "line",
            data: { labels: data.map((_,i)=>i+1), datasets:[{ data: data, borderColor: "#2b87f0", backgroundColor: "rgba(43,135,240,0.12)", fill:true, tension:0.3, pointRadius:0 }] },
            options: { responsive:false, plugins:{ legend:{display:false}}, elements:{line:{borderWidth:2}}, scales:{ x:{ display:false }, y:{ display:false } } }
        });
    });

    // overview chart
    const overviewCanvas = document.getElementById('stocksOverview');
    if (overviewCanvas) {
        const labels = quotes.map(q=>q.symbol);
        const data = quotes.map(q=>Number(q.price) || 0);
        new Chart(overviewCanvas.getContext('2d'), {
            type:'bar',
            data:{ labels: labels, datasets:[{ label:'Preço atual (R$)', data:data, backgroundColor: labels.map(()=> 'rgba(43,135,240,0.8)') }] },
            options:{ responsive:true, plugins:{ legend:{ display:false }}, scales:{ y:{ beginAtZero:true } } }
        });
    }
})();
</script>
JS;
                                    echo $script;
                                } else {
                                    echo '<p>Resposta inesperada da API.</p>';
                                    echo '<pre style="display:none;">' . htmlspecialchars(json_encode($payload)) . '</pre>';
                                }
                            }
                            ?>
                        </div>
                    <p></p>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="dashboard-charts">
                <div class="chart-group">
                    <div class="chart-box">
                        <h3>Despesas</h3><canvas id="graficoDespesas"></canvas>
                    </div>
                    <div class="chart-box">
                        <h3>Receitas</h3><canvas id="graficoReceitas"></canvas>
                    </div>
                    <div class="chart-box">
                        <h3>Investimentos</h3><canvas id="graficoInvestimentos"></canvas>
                    </div>
                </div>
            </div>

            <!-- Lista de transações -->
            <h3>Transações Recentes</h3>
            <table>
                <style>
                    table {
                        border-collapse: separate;
                        border-spacing: 0 8px;
                    }

                    td,
                    th {
                        padding: 5px 20px;
                    }
                </style>
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Categoria</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($transacoes, 0, 10) as $t): ?>
                        <tr>
                            <td><?php echo ucfirst($t['tipo']); ?></td>
                            <td><?php echo ucfirst(str_replace('_', ' ', $t['categoria'])); ?></td>
                            <td><?php echo htmlspecialchars($t['descricao']); ?></td>
                            <td>R$ <?php echo number_format($t['valor'], 2, ',', '.'); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($t['data'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <script>
        function gerarGrafico(idCanvas, titulo, labels, dados) {
            const ctx = document.getElementById(idCanvas).getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels.length ? labels : ['Sem dados'],
                    datasets: [{
                        data: dados.length ? dados : [1],
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                            '#9966FF', '#FF9F40', '#C9CBCF', '#8BC34A',
                            '#E91E63', '#00BCD4'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        title: { display: true, text: titulo }
                    }
                }
            });
        }

        gerarGrafico('graficoDespesas', 'Distribuição das Despesas',
            <?php echo json_encode(array_keys($categoriasDespesas)); ?>,
            <?php echo json_encode(array_values($categoriasDespesas)); ?>);

        gerarGrafico('graficoReceitas', 'Distribuição das Receitas',
            <?php echo json_encode(array_keys($categoriasReceitas)); ?>,
            <?php echo json_encode(array_values($categoriasReceitas)); ?>);

        gerarGrafico('graficoInvestimentos', 'Distribuição dos Investimentos',
            <?php echo json_encode(array_keys($categoriasInvestimentos)); ?>,
            <?php echo json_encode(array_values($categoriasInvestimentos)); ?>);

        const testUrl = 'https://brapi.dev/api/quote/PETR4,VALE3?range=1mo&interval=1d';
        async function fetchTestQuotes() {
            try {
                const response = await fetch(testUrl);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();
                console.log(data.results);
            } catch (error) {
                console.error('Falha ao buscar cotações de teste:', error);
            }
        }
        // Nota: a busca principal de cotações agora é feita server-side em PHP.
        // Mantemos apenas a função de teste client-side caso precise depurar via navegador.
        // Para chamadas reais use a implementação PHP acima.

    </script>


    <?php include 'includes_footer.php'; ?>
</body>

</html>