


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Serviços</title>
    <style>
        body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f4f6f8;
    margin: 0;
    padding: 0;
}

.tutorial-container {
    display: flex;
    justify-content: center;
    padding: 40px 20px;
}

.card {
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    padding: 30px;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.card-title {
    font-size: 28px;
    margin-bottom: 20px;
    color: #2c3e50;
}

.card-text {
    font-size: 16px;
    line-height: 1.6;
    color: #34495e;
    margin-bottom: 20px;
}

.card-list {
    list-style: none;
    padding-left: 0;
    margin-bottom: 20px;
}

.card-list li {
    background-color: #ecf0f1;
    margin-bottom: 10px;
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 15px;
    color: #2c3e50;
}

.card-image {
    width: 100%;
    border-radius: 8px;
    margin-top: 20px;
}
    </style>
</head>

<body>
    <?php include 'header_servicosgerais.php'; ?>


    <!-- DASHBOARD -->
    <div class="tutorial-container" id="dashboard">
        <div class="card">
            <h2 class="card-title"> Tela Dashboard</h2>
            <p class="card-text">
                A tela Dashboard é o principal painel de análise financeira do usuário, oferecendo uma visão clara e objetiva das receitas, despesas, investimentos e do saldo atual. Logo no topo, há um menu de navegação com três botões — Dashboard, Carteira e Aprender — além do perfil do usuário com uma saudação personalizada e a opção de encerrar a sessão. Em seguida, o título “Seu Dashboard financeiro” é exibido junto ao botão de seleção de mês, permitindo visualizar os dados do período escolhido. Há também um botão para acessar relatórios detalhados em outra página, permitindo que sejam baixados em PDF ou Excel. Abaixo, o usuário encontra um resumo financeiro com os valores de receita, despesa, investimento e saldo do mês selecionado. Complementando a análise, três gráficos de pizza mostram a distribuição percentual das movimentações financeiras, seguidos por uma lista das transações mais recentes.
            </p>

            <img src="img/Dashboard_Atual.jpg" alt="Dashboard Atual" class="card-image">

            <!-- Botão voltar -->
            <button class="btn-topo" onclick="voltarAoTopo()">Voltar ao topo</button>
        </div>
    </div>

    <!-- Carteira -->
    <div class="tutorial-container" id="carteira">
        <div class="card">
            <h2 class="card-title"> Tela Carteira</h2>
            <p class="card-text">
                Nesta tela, o usuário pode cadastrar novas transações financeiras e visualizar um relatório das movimentações registradas. A área principal é dedicada ao registro de transações e à exibição do relatório. Na seção “Registrar Transação”, o usuário escolhe o tipo da movimentação por meio de um menu suspenso com as opções: Receita, Despesa ou Investimento. Conforme a escolha, são exibidas categorias específicas: para Despesa, incluem Alimentação, Compras Pessoais, Contas Domésticas, Educação, Financiamento, Gastos Eventuais, Lazer, Moradia, Saúde, Transporte e Outros; para Investimento, as opções são Reserva de Emergência, Renda Fixa, Renda Variável e Outros; e para Receita, as categorias disponíveis são Dividendos, Proventos, Salário e Outros. Após selecionar o tipo e a categoria, o usuário preenche os campos de descrição, valor e data, e finaliza o registro com o botão “Salvar”. Logo abaixo, um gráfico de pizza colorido exibe a distribuição percentual das transações por categoria, com cada cor representando uma categoria e acompanhada de legenda explicativa. E uma lista organizada com as últimas movimentações registradas, contendo as colunas: tipo, categoria, descrição, valor, data e ações (para excluir)

            </p>

            <img src="img/Carteira_ServicosGerais.jpg" alt="Carteira" class="card-image">

            <!-- Botão voltar -->
            <button class="btn-topo" onclick="voltarAoTopo()">Voltar ao topo</button>
        </div>
    </div>

    <!-- Aprender -->

    <div class="tutorial-container" id="aprender">
        <div class="card">
            <h2 class="card-title"> Tela Aprender</h2>
            <p class="card-text">
                Nesta tela, são apresentados 6 tópicos sobre educação financeira, Fundamentos das finanças pessoais, Metas e planejamento financeiro, Reserva de emergência, Consumo consciente, Renda fixa, Renda variável, Trader e Certificações profissionais. Cada tema recebe um botão de iniciar trilha, levando o usuário a acessar uma "trilha de aprendizado"através de um jogo interativo estilo Quiz.
            </p>

            <img src="img/Aprender.jpg" alt="Aprender" class="card-image">

            <h4 style="margin-top: 30px;"> Quiz</h4>
            <p class="card-text">
                Aqui o usuário encontra vídeos educativos a respeito do tema. E aprende, em formato de perguntas e respostas, sobre o tema selecionado na sessão "Aprender".
            </p>


            <img src="img/Aprender_Quiz.jpg" alt="Quiz" class="card-image" style="margin-top: 20px">

            <!-- Botão voltar -->
            <button class="btn-topo" onclick="voltarAoTopo()">Voltar ao topo</button>
            <script>
                // Função simples para rolar suavemente até o topo
                function voltarAoTopo() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            </script>


        </div>
    </div>



</body>

</html>