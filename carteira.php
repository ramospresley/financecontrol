<?php
session_start();
require_once 'conexao.php';

$paginaAtual = 'carteira';
$tituloPagina = 'Carteira - FinanceControl';

$usuarioLogado = isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'];
$usuarioId = $_SESSION['usuario_id'] ?? null;
$nomeUsuario = $_SESSION['nome_usuario'] ?? 'Visitante';

if (!$usuarioLogado || !$usuarioId) {
    header('Location: index.php');
    exit;
}

// Processar nova transação com redirecionamento (PRG)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar_transacao'])) {
    $tipo = $_POST['tipo'];
    $categoria = $_POST['categoria'];
    $descricao = $_POST['descricao'];
    $valor = floatval($_POST['valor']);
    $data = $_POST['data'];

    $stmt = $pdo->prepare("INSERT INTO transacoes (usuario_id, tipo, categoria, descricao, valor, data) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$usuarioId, $tipo, $categoria, $descricao, $valor, $data]);

    // Redireciona para evitar reenvio ao atualizar
    header("Location: carteira.php");
    exit;
}

// Deletar transação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletar_transacao'])) {
    $idTransacao = $_POST['id_transacao'];
    $stmt = $pdo->prepare("DELETE FROM transacoes WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$idTransacao, $usuarioId]);

    // Redireciona após exclusão
    header("Location: carteira.php");
    exit;
}

// Buscar transações do usuário
$stmt = $pdo->prepare("SELECT * FROM transacoes WHERE usuario_id = ? ORDER BY data DESC");
$stmt->execute([$usuarioId]);
$transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Preparar dados para gráfico
$categoriasResumo = [];
$totalReceitas = 0;

foreach ($transacoes as $t) {
    if ($t['tipo'] === 'receita') {
        $totalReceitas += $t['valor'];
    } elseif (in_array($t['tipo'], ['despesa', 'investimento'])) {
        $cat = ucfirst(str_replace('_', ' ', $t['categoria']));
        $categoriasResumo[$cat] = ($categoriasResumo[$cat] ?? 0) + $t['valor'];
    }
}

$porcentagens = [];
foreach ($categoriasResumo as $cat => $valor) {
    $porcentagens[$cat] = $totalReceitas > 0 ? round(($valor / $totalReceitas) * 100, 2) : 0;
}

$labels = '"' . implode('","', array_keys($porcentagens)) . '"';
$dados = implode(',', array_values($porcentagens));
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title><?php echo $tituloPagina; ?></title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include 'includes_header.php'; ?>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <section class="carteira">
        <div class="container">
            <h2 style="margin-top: 20px;">Registrar Transação</h2>
          

<form method="POST" class="form-transacao">
  <label>Tipo:</label>
  <select name="tipo" id="tipo" required>
    <option value="">Selecione</option>
    <option value="receita">Receita</option>
    <option value="despesa">Despesa</option>
    <option value="investimento">Investimento</option>
  </select>

  <label>Categoria:</label>
  <select name="categoria" id="categoria" required>
    <option value="">Selecione o tipo </option>
  </select>

  <label>Descrição:</label>
  <input type="text" name="descricao" required>

  <label>Valor:</label>
  <input type="number" name="valor" step="0.01" required placeholder="R$">

  <label>Data:</label>
  <input type="date" name="data" required>

  <button type="submit" name="adicionar_transacao" class="btn btn-primary">Salvar</button>
</form>

<script>
  const categorias = {
    receita: [
  { value: "dividendos", label: "Dividendos" },  
  { value: "proventos", label: "Proventos" },
  { value: "salario", label: "Salário" },
  { value: "outros", label: "Outros" }
],
    despesa: [  
  { value: "alimentacao", label: "Alimentação" },
  { value: "compras_pessoais", label: "Compras Pessoais" },
  { value: "contas_domesticas", label: "Contas Domésticas" },
  { value: "educacao", label: "Educação" },
  { value: "financiamento", label: "Financiamento" },
  { value: "gastos_eventuais", label: "Gastos Eventuais" },
  { value: "lazer", label: "Lazer" },  
  { value: "moradia", label: "Moradia" },
  { value: "saude", label: "Saúde" },
  { value: "transporte", label: "Transporte" },
  { value: "outros", label: "Outros" }
],
    investimento: [
      { value: "reserva_emergencia", label: "Reserva de Emergência" },
      { value: "renda_fixa", label: "Renda Fixa" },
      { value: "renda_variavel", label: "Renda Variável" },
      { value: "outros", label: "Outros" }
    ]
  };

  const tipoSelect = document.getElementById("tipo");
  const categoriaSelect = document.getElementById("categoria");

  tipoSelect.addEventListener("change", function () {
    const tipo = this.value;
    categoriaSelect.innerHTML = "";

    if (categorias[tipo]) {
      categorias[tipo].forEach(cat => {
        const option = document.createElement("option");
        option.value = cat.value;
        option.textContent = cat.label;
        categoriaSelect.appendChild(option);
      });
    } else {
      const option = document.createElement("option");
      option.textContent = "Selecione o tipo primeiro";
      categoriaSelect.appendChild(option);
    }
  });
</script>



           

            <!-- Gráfico -->
            <h3 style="margin-top: 20px;">Resumo de Despesas e Investimentos</h3>
            <canvas id="graficoPizza" style="width: 320px; height: 320px;"></canvas>

            <!-- Tabela -->
            <h3>Transações Recentes</h3>
            <table>
                <style>
                    table {
                        border-collapse: separate;
                        border-spacing: 0 8px;
                        /* espaçamento vertical entre linhas */
                    }

                    td {
                        padding-left: 0px;
                        padding-right: 20px;
                    }

                    th {
                        padding-left: opx;
                        padding-right: 20px;
                    }
                </style>



                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Categoria</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transacoes as $t): ?>
                        <tr>
                            <td><?php echo ucfirst($t['tipo']); ?></td>
                            <td><?php echo ucfirst(str_replace('_', ' ', $t['categoria'])); ?></td>
                            <td><?php echo htmlspecialchars($t['descricao']); ?></td>
                            <td>R$ <?php echo number_format($t['valor'], 2, ',', '.'); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($t['data'])); ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Deseja realmente excluir esta transação?');">
                                    <input type="hidden" name="id_transacao" value="<?php echo $t['id']; ?>">
                                    <button type="submit" name="deletar_transacao" class="btn btn-outline btn-small">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <script>
        const ctx = document.getElementById('graficoPizza').getContext('2d');

        const dados = {
            labels: [<?php echo $labels; ?>],
            datasets: [{
                data: [<?php echo $dados; ?>],
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                    '#FF9F40', '#C9CBCF', '#8BC34A', '#E91E63', '#00BCD4'
                ]
            }]
        };

        new Chart(ctx, {
            type: 'pie',
            data: dados,
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    },
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Distribuição das Transações (%) sobre Receita'
                    }
                }
            }
        });
    </script>
    
   <?php include 'includes_footer.php'; ?>
</body>

</html>