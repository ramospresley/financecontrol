-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/10/2025 às 04:36
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `financecontrol`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `assinaturas`
--

CREATE TABLE `assinaturas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `conteudo_fases`
--

CREATE TABLE `conteudo_fases` (
  `id` int(11) NOT NULL,
  `trilha_id` int(11) NOT NULL,
  `fase_numero` int(11) NOT NULL,
  `titulo_fase` varchar(255) DEFAULT NULL,
  `texto_principal` text DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `atividade_pratica` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `conteudo_fases`
--

INSERT INTO `conteudo_fases` (`id`, `trilha_id`, `fase_numero`, `titulo_fase`, `texto_principal`, `video_url`, `atividade_pratica`) VALUES
(1, 1, 1, 'Entendendo o Básico do Orçamento', 'O primeiro passo para a liberdade financeira é saber para onde seu dinheiro está indo. Um orçamento é simplesmente um plano de gastos. Ele não serve para te restringir, mas sim para te dar controle total sobre suas finanças. Classifique seus gastos em receitas, despesas fixas (aluguel, contas) e despesas variáveis (lazer, alimentação).', 'https://www.youtube.com/embed/simulacao-video', 'Desafio: Liste todas as suas receitas e despesas do último mês. Use o painel \"Carteira Financeira\" para categorizar 10 transações recentes!'),
(2, 1, 2, 'Dívidas e Crédito: Como Lidar', 'Entender suas dívidas é essencial para manter sua saúde financeira. Priorize dívidas com juros altos e evite o uso excessivo do crédito rotativo. Conheça seu score e negocie sempre que possível.', 'https://www.youtube.com/embed/simulacao-video2', 'Desafio: Liste suas dívidas atuais e classifique por taxa de juros. Crie um plano de pagamento.'),
(3, 1, 3, 'Reserva de Emergência', 'Uma reserva de emergência protege você de imprevistos. O ideal é guardar de 3 a 6 meses de despesas essenciais. Comece com metas pequenas e consistentes.', 'https://www.youtube.com/embed/simulacao-video3', 'Desafio: Calcule quanto você precisa para uma reserva de 3 meses. Crie uma meta mensal para alcançá-la.'),
(4, 1, 4, 'Investimentos Básicos', 'Investir é fazer o dinheiro trabalhar para você. Conheça os principais tipos: renda fixa, ações, fundos. Comece com o que você entende e respeite seu perfil de risco.', 'https://www.youtube.com/embed/simulacao-video4', 'Desafio: Escolha um investimento de baixo risco e simule um aporte mensal por 12 meses.'),
(5, 1, 5, 'Planejamento de Longo Prazo', 'Planejar o futuro inclui aposentadoria, grandes compras e sonhos. Use metas SMART e revise seu plano anualmente. O tempo é seu maior aliado.', 'https://www.youtube.com/embed/simulacao-video5', 'Desafio: Crie uma meta de longo prazo e defina os passos para alcançá-la. Use o painel de metas.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `progresso_aprendizado`
--

CREATE TABLE `progresso_aprendizado` (
  `usuario_id` int(11) NOT NULL,
  `trilha_id` int(11) NOT NULL,
  `fase_concluida` int(11) DEFAULT 0,
  `xp` int(11) DEFAULT 0,
  `vidas` int(11) DEFAULT 5,
  `ultima_vida_perdida` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `progresso_aprendizado`
--

INSERT INTO `progresso_aprendizado` (`usuario_id`, `trilha_id`, `fase_concluida`, `xp`, `vidas`, `ultima_vida_perdida`) VALUES
(3, 1, 0, 0, 5, NULL),
(3, 2, 0, 0, 5, NULL),
(3, 6, 0, 0, 5, NULL),
(3, 7, 0, 0, 5, NULL),
(3, 8, 0, 0, 5, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `quiz_fases`
--

CREATE TABLE `quiz_fases` (
  `id` int(11) NOT NULL,
  `trilha_id` int(11) NOT NULL,
  `fase_numero` int(11) NOT NULL,
  `pergunta` text NOT NULL,
  `opcoes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`opcoes`)),
  `resposta_correta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `quiz_fases`
--

INSERT INTO `quiz_fases` (`id`, `trilha_id`, `fase_numero`, `pergunta`, `opcoes`, `resposta_correta`) VALUES
(6, 1, 1, 'O que é orçamento pessoal?', '[\"Planejamento de gastos e receitas\", \"Investimento em ações\", \"Compra parcelada\", \"Uso de cartão de crédito\"]', 'Planejamento de gastos e receitas'),
(7, 1, 1, 'Qual é a função do controle financeiro?', '[\"Evitar dívidas\", \"Aumentar impostos\", \"Reduzir salário\", \"Ignorar despesas\"]', 'Evitar dívidas'),
(8, 1, 1, 'Qual é o primeiro passo para organizar suas finanças?', '[\"Registrar todos os gastos\", \"Investir em ações\", \"Pedir empréstimo\", \"Ignorar dívidas\"]', 'Registrar todos os gastos'),
(9, 1, 1, 'O que é uma despesa fixa?', '[\"Gasto recorrente como aluguel\", \"Compra de roupas\", \"Viagem de férias\", \"Jantar fora\"]', 'Gasto recorrente como aluguel'),
(10, 1, 1, 'Qual é a importância de guardar notas fiscais?', '[\"Controlar gastos\", \"Ganhar cashback\", \"Evitar impostos\", \"Receber salário\"]', 'Controlar gastos'),
(11, 1, 2, 'O que é uma receita variável?', '[\"Comissão ou bônus\", \"Salário fixo\", \"Aluguel\", \"Conta de luz\"]', 'Comissão ou bônus'),
(12, 1, 2, 'Qual ferramenta ajuda no controle financeiro?', '[\"Planilha de gastos\", \"Cartão de crédito\", \"Cheque especial\", \"Empréstimo pessoal\"]', 'Planilha de gastos'),
(13, 1, 2, 'O que significa viver dentro do orçamento?', '[\"Gastar menos do que ganha\", \"Usar todo o limite do cartão\", \"Pedir empréstimos\", \"Ignorar dívidas\"]', 'Gastar menos do que ganha'),
(14, 1, 2, 'Qual é o impacto de não controlar os gastos?', '[\"Endividamento\", \"Aumento de patrimônio\", \"Redução de impostos\", \"Ganhos inesperados\"]', 'Endividamento'),
(15, 1, 2, 'O que é uma meta financeira?', '[\"Objetivo de poupar ou investir\", \"Despesas mensais\", \"Salário fixo\", \"Conta bancária\"]', 'Objetivo de poupar ou investir'),
(16, 2, 1, 'O que é uma meta financeira SMART?', '[\"Específica, Mensurável, Alcançável, Relevante, Temporal\", \"Simples, Móvel, Atingível, Realista, Técnica\", \"Segura, Mensal, Atual, Rápida, Transparente\", \"Sólida, Média, Acelerada, Rentável, Total\"]', 'Específica, Mensurável, Alcançável, Relevante, Temporal'),
(17, 2, 1, 'Qual é o benefício de definir metas financeiras?', '[\"Direcionar o uso do dinheiro\", \"Evitar pagar impostos\", \"Aumentar dívidas\", \"Ignorar orçamento\"]', 'Direcionar o uso do dinheiro'),
(18, 2, 1, 'Qual ferramenta ajuda a acompanhar metas?', '[\"Planilha de metas\", \"Cartão de crédito\", \"Cheque especial\", \"Conta corrente\"]', 'Planilha de metas'),
(19, 2, 1, 'O que é uma meta de curto prazo?', '[\"Objetivo para até 1 ano\", \"Objetivo para 10 anos\", \"Meta para aposentadoria\", \"Investimento em imóveis\"]', 'Objetivo para até 1 ano'),
(20, 2, 1, 'Qual é um exemplo de meta de longo prazo?', '[\"Comprar uma casa\", \"Pagar o almoço\", \"Comprar roupas\", \"Recarregar celular\"]', 'Comprar uma casa'),
(21, 2, 2, 'Por que é importante revisar metas periodicamente?', '[\"Para ajustar ao cenário atual\", \"Para esquecer dívidas\", \"Para gastar mais\", \"Para evitar investimentos\"]', 'Para ajustar ao cenário atual'),
(22, 2, 2, 'O que significa priorizar metas?', '[\"Definir ordem de importância\", \"Ignorar metas menores\", \"Focar só em dívidas\", \"Evitar planejamento\"]', 'Definir ordem de importância'),
(23, 2, 2, 'Qual é o impacto de metas mal definidas?', '[\"Desmotivação e desorganização\", \"Aumento de salário\", \"Redução de impostos\", \"Ganhos inesperados\"]', 'Desmotivação e desorganização'),
(24, 2, 2, 'O que é uma meta mensurável?', '[\"Pode ser acompanhada por números\", \"É subjetiva\", \"Não tem prazo\", \"É emocional\"]', 'Pode ser acompanhada por números'),
(25, 2, 2, 'Qual é o papel do planejamento financeiro?', '[\"Organizar metas e recursos\", \"Evitar metas\", \"Ignorar orçamento\", \"Aumentar dívidas\"]', 'Organizar metas e recursos'),
(26, 2, 3, 'Como metas financeiras ajudam na tomada de decisão?', '[\"Guiam escolhas com base em objetivos\", \"Eliminam a necessidade de pensar\", \"Aumentam gastos\", \"Reduzem salário\"]', 'Guiam escolhas com base em objetivos'),
(27, 2, 3, 'Qual é um exemplo de meta de médio prazo?', '[\"Fazer uma viagem internacional\", \"Comprar um café\", \"Pagar o aluguel\", \"Recarregar celular\"]', 'Fazer uma viagem internacional'),
(28, 2, 3, 'O que é uma meta relevante?', '[\"Importante para seus valores e objetivos\", \"Popular entre amigos\", \"Baseada em modismos\", \"Sem impacto pessoal\"]', 'Importante para seus valores e objetivos'),
(29, 2, 3, 'Como metas financeiras influenciam o comportamento?', '[\"Promovem disciplina e foco\", \"Causam ansiedade\", \"Geram impulsividade\", \"Eliminam controle\"]', 'Promovem disciplina e foco'),
(30, 2, 3, 'Qual é o risco de não ter metas?', '[\"Desorganização financeira\", \"Aumento de patrimônio\", \"Redução de dívidas\", \"Ganhos inesperados\"]', 'Desorganização financeira'),
(31, 2, 4, 'Como metas ajudam a evitar dívidas?', '[\"Direcionam o uso consciente do dinheiro\", \"Aumentam limite do cartão\", \"Eliminam boletos\", \"Ignoram gastos\"]', 'Direcionam o uso consciente do dinheiro'),
(32, 2, 4, 'Qual é o papel do prazo nas metas?', '[\"Define tempo para alcançar\", \"Elimina metas\", \"Ignora planejamento\", \"Aumenta dívidas\"]', 'Define tempo para alcançar'),
(33, 2, 4, 'O que é uma meta alcançável?', '[\"Realista dentro da sua realidade\", \"Baseada em sonhos impossíveis\", \"Sem critérios\", \"Muito distante\"]', 'Realista dentro da sua realidade'),
(34, 2, 4, 'Como metas financeiras afetam o orçamento?', '[\"Organizam prioridades de gastos\", \"Aumentam despesas\", \"Ignoram receitas\", \"Eliminam controle\"]', 'Organizam prioridades de gastos'),
(35, 2, 4, 'Qual é o impacto de metas bem definidas?', '[\"Maior controle e motivação\", \"Desorganização\", \"Aumento de dívidas\", \"Redução de salário\"]', 'Maior controle e motivação'),
(36, 2, 5, 'Como metas financeiras se relacionam com investimentos?', '[\"Definem objetivos para aplicar recursos\", \"Eliminam a necessidade de investir\", \"Ignoram riscos\", \"Aumentam dívidas\"]', 'Definem objetivos para aplicar recursos'),
(37, 2, 5, 'O que é uma meta temporal?', '[\"Tem prazo definido\", \"É emocional\", \"Não tem data\", \"É subjetiva\"]', 'Tem prazo definido'),
(38, 2, 5, 'Como metas ajudam na disciplina financeira?', '[\"Criam foco e constância\", \"Eliminam controle\", \"Ignoram orçamento\", \"Aumentam impulsividade\"]', 'Criam foco e constância'),
(39, 2, 5, 'Qual é o papel das metas na construção de patrimônio?', '[\"Guiam decisões de poupança e investimento\", \"Eliminam ativos\", \"Ignoram renda\", \"Reduzem ganhos\"]', 'Guiam decisões de poupança e investimento'),
(40, 2, 5, 'Como metas financeiras afetam a motivação?', '[\"Aumentam o engajamento com objetivos\", \"Causam desânimo\", \"Ignoram conquistas\", \"Eliminam foco\"]', 'Aumentam o engajamento com objetivos'),
(41, 3, 1, 'O que é uma reserva de emergência?', '[\"Dinheiro guardado para imprevistos\", \"Investimento em ações\", \"Gasto com lazer\", \"Compra parcelada\"]', 'Dinheiro guardado para imprevistos'),
(42, 3, 1, 'Qual é o valor ideal de uma reserva de emergência?', '[\"De 3 a 6 meses de despesas\", \"1 mês de salário\", \"Valor do aluguel\", \"Metade do cartão de crédito\"]', 'De 3 a 6 meses de despesas'),
(43, 3, 2, 'Onde guardar a reserva de emergência?', '[\"Em aplicações seguras e líquidas\", \"Em ações voláteis\", \"Em criptomoedas\", \"Em bens físicos\"]', 'Em aplicações seguras e líquidas'),
(44, 3, 2, 'Qual é o objetivo da reserva de emergência?', '[\"Cobrir gastos inesperados\", \"Comprar carro novo\", \"Investir em imóveis\", \"Pagar festas\"]', 'Cobrir gastos inesperados'),
(45, 3, 3, 'Quando usar a reserva de emergência?', '[\"Em caso de desemprego ou urgência\", \"Para pagar compras parceladas\", \"Para investir em ações\", \"Para lazer\"]', 'Em caso de desemprego ou urgência'),
(46, 3, 3, 'Qual é a principal característica da reserva?', '[\"Alta liquidez\", \"Alto risco\", \"Rentabilidade agressiva\", \"Baixa segurança\"]', 'Alta liquidez'),
(47, 3, 4, 'Por que não investir a reserva em renda variável?', '[\"Porque há risco de perda\", \"Porque rende mais\", \"Porque é moda\", \"Porque é obrigatório\"]', 'Porque há risco de perda'),
(48, 3, 4, 'Como calcular o valor da reserva?', '[\"Multiplicando despesas mensais por 6\", \"Somando salário com bônus\", \"Calculando o limite do cartão\", \"Dividindo o aluguel por 2\"]', 'Multiplicando despesas mensais por 6'),
(49, 3, 5, 'O que fazer após usar a reserva?', '[\"Repor o valor o quanto antes\", \"Ignorar o gasto\", \"Investir em ações\", \"Pedir empréstimo\"]', 'Repor o valor o quanto antes'),
(50, 3, 5, 'Qual é o erro comum ao montar a reserva?', '[\"Misturar com dinheiro de lazer\", \"Guardar em conta corrente\", \"Investir em previdência\", \"Usar para pagar dívidas antigas\"]', 'Misturar com dinheiro de lazer'),
(51, 4, 1, 'O que é consumo consciente?', '[\"Comprar com responsabilidade e necessidade\", \"Comprar por impulso\", \"Gastar sem planejamento\", \"Ignorar orçamento\"]', 'Comprar com responsabilidade e necessidade'),
(52, 4, 1, 'Qual é o impacto do consumo consciente?', '[\"Redução de desperdício e dívidas\", \"Aumento de gastos\", \"Descontrole financeiro\", \"Endividamento\"]', 'Redução de desperdício e dívidas'),
(53, 4, 1, 'Como identificar uma compra por impulso?', '[\"Não foi planejada e não é urgente\", \"Está em promoção\", \"Foi recomendada por amigos\", \"Está parcelada\"]', 'Não foi planejada e não é urgente'),
(54, 4, 1, 'Qual é uma prática de consumo consciente?', '[\"Comparar preços antes de comprar\", \"Comprar o mais caro\", \"Ignorar orçamento\", \"Usar todo o limite do cartão\"]', 'Comparar preços antes de comprar'),
(55, 4, 1, 'Por que evitar compras parceladas sem necessidade?', '[\"Podem gerar dívidas futuras\", \"Aumentam o limite do cartão\", \"Reduzem o score\", \"Eliminam o orçamento\"]', 'Podem gerar dívidas futuras'),
(56, 4, 2, 'Como o marketing influencia o consumo?', '[\"Estimula compras não planejadas\", \"Reduz preços\", \"Aumenta salário\", \"Controla orçamento\"]', 'Estimula compras não planejadas'),
(57, 4, 2, 'Qual é o papel da educação financeira no consumo?', '[\"Promover escolhas conscientes\", \"Aumentar gastos\", \"Ignorar metas\", \"Estimular parcelamentos\"]', 'Promover escolhas conscientes'),
(58, 4, 2, 'O que é consumo sustentável?', '[\"Compra que respeita o meio ambiente\", \"Compra por impulso\", \"Compra parcelada\", \"Compra sem pesquisa\"]', 'Compra que respeita o meio ambiente'),
(59, 4, 2, 'Como evitar o endividamento no consumo?', '[\"Planejar antes de comprar\", \"Usar cheque especial\", \"Ignorar orçamento\", \"Comprar por impulso\"]', 'Planejar antes de comprar'),
(60, 4, 2, 'Qual é o impacto de comprar sem necessidade?', '[\"Acúmulo de dívidas e desperdício\", \"Aumento de patrimônio\", \"Redução de impostos\", \"Ganhos inesperados\"]', 'Acúmulo de dívidas e desperdício'),
(61, 4, 3, 'Como o consumo consciente afeta o orçamento?', '[\"Ajuda a manter equilíbrio financeiro\", \"Aumenta dívidas\", \"Ignora metas\", \"Reduz salário\"]', 'Ajuda a manter equilíbrio financeiro'),
(62, 4, 3, 'O que é uma compra planejada?', '[\"Compra feita com base em orçamento e necessidade\", \"Compra por impulso\", \"Compra parcelada\", \"Compra emocional\"]', 'Compra feita com base em orçamento e necessidade'),
(63, 4, 3, 'Qual é o papel da reflexão antes da compra?', '[\"Evitar decisões impulsivas\", \"Aumentar gastos\", \"Ignorar metas\", \"Reduzir controle\"]', 'Evitar decisões impulsivas'),
(64, 4, 3, 'Como o consumo consciente contribui para a sociedade?', '[\"Reduz impactos ambientais e sociais\", \"Aumenta o consumo\", \"Ignora sustentabilidade\", \"Promove desperdício\"]', 'Reduz impactos ambientais e sociais'),
(65, 4, 3, 'Por que evitar compras motivadas por status?', '[\"Podem gerar dívidas desnecessárias\", \"Aumentam autoestima\", \"Reduzem impostos\", \"Promovem investimentos\"]', 'Podem gerar dívidas desnecessárias'),
(66, 4, 4, 'Como o consumo consciente afeta o meio ambiente?', '[\"Reduz o uso de recursos naturais\", \"Aumenta o lixo\", \"Promove desperdício\", \"Ignora reciclagem\"]', 'Reduz o uso de recursos naturais'),
(67, 4, 4, 'O que é consumo colaborativo?', '[\"Compartilhamento de bens e serviços\", \"Compra parcelada\", \"Consumo por impulso\", \"Compra em excesso\"]', 'Compartilhamento de bens e serviços'),
(68, 4, 4, 'Como o consumo consciente ajuda no planejamento financeiro?', '[\"Evita gastos desnecessários\", \"Aumenta dívidas\", \"Ignora orçamento\", \"Reduz metas\"]', 'Evita gastos desnecessários'),
(69, 4, 4, 'Qual é o impacto de avaliar a real necessidade de uma compra?', '[\"Evita desperdício e endividamento\", \"Aumenta consumo\", \"Reduz patrimônio\", \"Ignora metas\"]', 'Evita desperdício e endividamento'),
(70, 4, 4, 'Como o consumo consciente pode ser ensinado?', '[\"Por meio da educação financeira\", \"Por meio de propagandas\", \"Por meio de parcelamentos\", \"Por meio de compras impulsivas\"]', 'Por meio da educação financeira'),
(71, 4, 5, 'Qual é o papel da consciência na hora da compra?', '[\"Evitar decisões impulsivas\", \"Aumentar gastos\", \"Ignorar orçamento\", \"Reduzir metas\"]', 'Evitar decisões impulsivas'),
(72, 4, 5, 'Como o consumo consciente afeta a saúde financeira?', '[\"Promove equilíbrio e controle\", \"Causa endividamento\", \"Ignora metas\", \"Reduz salário\"]', 'Promove equilíbrio e controle'),
(73, 4, 5, 'O que é uma compra consciente?', '[\"Compra planejada e necessária\", \"Compra por impulso\", \"Compra emocional\", \"Compra parcelada\"]', 'Compra planejada e necessária'),
(74, 4, 5, 'Por que evitar compras motivadas por emoção?', '[\"Podem gerar arrependimento e dívidas\", \"Aumentam autoestima\", \"Reduzem impostos\", \"Promovem investimentos\"]', 'Podem gerar arrependimento e dívidas'),
(75, 4, 5, 'Como o consumo consciente contribui para o futuro?', '[\"Preserva recursos e evita dívidas\", \"Aumenta consumo\", \"Ignora sustentabilidade\", \"Promove desperdício\"]', 'Preserva recursos e evita dívidas'),
(76, 5, 1, 'O que é um investimento em renda fixa?', '[\"Aplicação com retorno previsível\", \"Compra de ações\", \"Investimento em criptomoedas\", \"Gasto com cartão de crédito\"]', 'Aplicação com retorno previsível'),
(77, 5, 1, 'Qual é um exemplo de renda fixa?', '[\"Tesouro Direto\", \"Ações da bolsa\", \"Fundos imobiliários\", \"Criptomoedas\"]', 'Tesouro Direto'),
(78, 5, 1, 'O que significa liquidez?', '[\"Facilidade de resgatar o dinheiro\", \"Rentabilidade alta\", \"Prazo longo\", \"Risco elevado\"]', 'Facilidade de resgatar o dinheiro'),
(79, 5, 1, 'Qual é o risco da renda fixa?', '[\"Baixo\", \"Alto\", \"Muito alto\", \"Inexistente\"]', 'Baixo'),
(80, 5, 1, 'O que é CDB?', '[\"Certificado de Depósito Bancário\", \"Conta de débito bancária\", \"Cartão de débito\", \"Crédito direto bancário\"]', 'Certificado de Depósito Bancário'),
(81, 5, 1, 'Qual é a função do Tesouro Selic?', '[\"Proteger contra variações da taxa básica\", \"Investir em ações\", \"Comprar imóveis\", \"Reduzir impostos\"]', 'Proteger contra variações da taxa básica'),
(82, 5, 1, 'O que é rentabilidade?', '[\"Retorno financeiro do investimento\", \"Valor do imposto\", \"Custo da aplicação\", \"Taxa de administração\"]', 'Retorno financeiro do investimento'),
(83, 5, 1, 'Qual é o prazo típico de um CDB?', '[\"Pode variar de dias a anos\", \"Sempre 1 mês\", \"Sempre 10 anos\", \"Nunca tem prazo\"]', 'Pode variar de dias a anos'),
(84, 5, 1, 'O que é o FGC?', '[\"Fundo Garantidor de Créditos\", \"Fundo Geral de Consumo\", \"Fator de Garantia de Compra\", \"Fundo de Gestão Corporativa\"]', 'Fundo Garantidor de Créditos'),
(85, 5, 1, 'Qual é a vantagem do Tesouro Direto?', '[\"Segurança e acessibilidade\", \"Alto risco\", \"Rentabilidade incerta\", \"Liquidez nula\"]', 'Segurança e acessibilidade'),
(86, 5, 2, 'O que é uma LCI?', '[\"Letra de Crédito Imobiliário\", \"Limite de Crédito Individual\", \"Liquidez de Conta Investida\", \"Lista de Crédito Institucional\"]', 'Letra de Crédito Imobiliário'),
(87, 5, 2, 'A LCI é isenta de qual imposto?', '[\"Imposto de Renda para pessoa física\", \"IOF\", \"ISS\", \"IPTU\"]', 'Imposto de Renda para pessoa física'),
(88, 5, 2, 'O que é uma LCA?', '[\"Letra de Crédito do Agronegócio\", \"Limite de Crédito Automático\", \"Liquidez de Conta Ativa\", \"Lista de Crédito Agrário\"]', 'Letra de Crédito do Agronegócio'),
(89, 5, 2, 'Qual é o risco de uma LCI/LCA?', '[\"Baixo, com garantia do FGC\", \"Alto\", \"Muito alto\", \"Sem garantia\"]', 'Baixo, com garantia do FGC'),
(90, 5, 2, 'Qual é o prazo mínimo de uma LCI?', '[\"90 dias\", \"1 dia\", \"1 ano\", \"10 anos\"]', '90 dias'),
(91, 5, 2, 'O que é a taxa Selic?', '[\"Taxa básica de juros da economia\", \"Taxa de câmbio\", \"Taxa de inflação\", \"Taxa de crédito pessoal\"]', 'Taxa básica de juros da economia'),
(92, 5, 2, 'Qual é o papel da inflação na renda fixa?', '[\"Pode reduzir o ganho real\", \"Aumenta o rendimento\", \"Não afeta\", \"Elimina o risco\"]', 'Pode reduzir o ganho real'),
(93, 5, 2, 'O que é um título prefixado?', '[\"Tem rentabilidade definida no momento da compra\", \"Varia com a inflação\", \"Depende da Selic\", \"Não tem rendimento\"]', 'Tem rentabilidade definida no momento da compra'),
(94, 5, 2, 'O que é um título pós-fixado?', '[\"Rentabilidade varia conforme um índice\", \"Rentabilidade fixa\", \"Sem rendimento\", \"Rentabilidade negativa\"]', 'Rentabilidade varia conforme um índice'),
(95, 5, 2, 'Qual é o índice mais usado nos pós-fixados?', '[\"Taxa Selic\", \"IPCA\", \"IGP-M\", \"CDI\"]', 'CDI'),
(96, 5, 3, 'O que é IPCA?', '[\"Índice de Preços ao Consumidor Amplo\", \"Imposto sobre Produtos de Consumo\", \"Índice de Poupança de Crédito\", \"Indicador de Patrimônio Consolidado\"]', 'Índice de Preços ao Consumidor Amplo'),
(97, 5, 3, 'Qual título do Tesouro é atrelado ao IPCA?', '[\"Tesouro IPCA+\", \"Tesouro Selic\", \"Tesouro Prefixado\", \"Tesouro Direto\"]', 'Tesouro IPCA+'),
(98, 5, 3, 'O que é um título híbrido?', '[\"Combina rentabilidade fixa e variável\", \"Não tem rendimento\", \"É apenas pós-fixado\", \"É apenas prefixado\"]', 'Combina rentabilidade fixa e variável'),
(99, 5, 3, 'Qual é o risco de um título prefixado?', '[\"Perder rentabilidade se a taxa subir\", \"Não ter liquidez\", \"Não ter garantia\", \"Ser confiscado\"]', 'Perder rentabilidade se a taxa subir'),
(100, 5, 3, 'O que é marcação a mercado?', '[\"Atualização diária do valor do título\", \"Definição do vencimento\", \"Cálculo de imposto\", \"Avaliação de crédito\"]', 'Atualização diária do valor do título'),
(101, 5, 3, 'Qual é o impacto da taxa de juros na renda fixa?', '[\"Afeta diretamente a rentabilidade\", \"Não tem impacto\", \"Reduz o prazo\", \"Elimina o risco\"]', 'Afeta diretamente a rentabilidade'),
(102, 5, 3, 'O que é vencimento de um título?', '[\"Data final do investimento\", \"Data de compra\", \"Data de liquidez\", \"Data de imposto\"]', 'Data final do investimento'),
(103, 5, 3, 'Qual é o papel do CDI?', '[\"Referência para rentabilidade de CDBs\", \"Índice de inflação\", \"Taxa de câmbio\", \"Imposto sobre investimentos\"]', 'Referência para rentabilidade de CDBs'),
(104, 5, 3, 'O que é uma aplicação automática?', '[\"Investimento programado em renda fixa\", \"Compra de ações\", \"Transferência bancária\", \"Pagamento de boletos\"]', 'Investimento programado em renda fixa'),
(105, 5, 3, 'Qual é a vantagem da renda fixa para iniciantes?', '[\"Segurança e previsibilidade\", \"Alto risco\", \"Rentabilidade instável\", \"Liquidez nula\"]', 'Segurança e previsibilidade'),
(106, 5, 4, 'O que é uma debênture?', '[\"Título emitido por empresas\", \"Título do governo\", \"Crédito pessoal\", \"Cartão de crédito\"]', 'Título emitido por empresas'),
(107, 5, 4, 'Debêntures têm garantia do FGC?', '[\"Não\", \"Sim\", \"Depende do valor\", \"Apenas em bancos públicos\"]', 'Não'),
(108, 5, 4, 'O que são debêntures incentivadas?', '[\"Isentas de imposto de renda\", \"Com garantia do governo\", \"Com liquidez diária\", \"Emitidas por bancos\"]', 'Isentas de imposto de renda'),
(109, 5, 4, 'Qual é o risco das debêntures?', '[\"Maior que CDBs\", \"Menor que Tesouro\", \"Inexistente\", \"Igual ao FGC\"]', 'Maior que CDBs'),
(110, 5, 4, 'O que é uma NTN-B?', '[\"Título do Tesouro atrelado ao IPCA\", \"Título prefixado\", \"CDB bancário\", \"LCI\"]', 'Título do Tesouro atrelado ao IPCA'),
(111, 5, 4, 'Qual é o prazo típico de uma debênture?', '[\"Médio a longo prazo\", \"1 dia\", \"30 dias\", \"Sem prazo\"]', 'Médio a longo prazo'),
(112, 5, 4, 'O que é risco de crédito?', '[\"Possibilidade de não receber o valor investido\", \"Variação da taxa Selic\", \"Mudança na inflação\", \"Liquidez baixa\"]', 'Possibilidade de não receber o valor investido'),
(113, 5, 4, 'Como avaliar uma debênture?', '[\"Analisando a empresa emissora\", \"Verificando o FGC\", \"Consultando o Tesouro\", \"Ouvindo amigos\"]', 'Analisando a empresa emissora'),
(114, 5, 4, 'Qual é o papel da corretora na renda fixa?', '[\"Intermediar a compra de títulos\", \"Emitir títulos\", \"Definir taxas\", \"Garantir rentabilidade\"]', 'Intermediar a compra de títulos'),
(115, 5, 4, 'O que é uma NTN-F?', '[\"Título prefixado do Tesouro\", \"Título pós-fixado\", \"CDB bancário\", \"LCI\"]', 'Título prefixado do Tesouro'),
(116, 5, 5, 'Qual é o imposto sobre renda fixa?', '[\"Imposto de Renda regressivo\", \"IPTU\", \"ISS\", \"IOF fixo\"]', 'Imposto de Renda regressivo'),
(117, 5, 5, 'O que é tabela regressiva de IR?', '[\"Quanto maior o prazo, menor o imposto\", \"Quanto menor o prazo, menor o imposto\", \"Imposto fixo\", \"Sem imposto\"]', 'Quanto maior o prazo, menor o imposto'),
(118, 5, 5, 'Qual é o prazo mínimo para isenção de IOF?', '[\"30 dias\", \"1 dia\", \"90 dias\", \"180 dias\"]', '30 dias'),
(119, 5, 5, 'Como calcular o rendimento bruto?', '[\"Valor final menos valor investido\", \"Valor investido vezes taxa\", \"Valor final vezes imposto\", \"Valor investido dividido por prazo\"]', 'Valor final menos valor investido'),
(120, 5, 5, 'O que é rendimento líquido?', '[\"Rendimento após impostos\", \"Rendimento antes de impostos\", \"Valor bruto\", \"Valor investido\"]', 'Rendimento após impostos'),
(121, 5, 5, 'Qual é a vantagem de investir direto pelo Tesouro?', '[\"Menores taxas e maior controle\", \"Maior risco\", \"Menor rentabilidade\", \"Liquidez nula\"]', 'Menores taxas e maior controle'),
(122, 5, 5, 'O que é uma plataforma de investimentos?', '[\"Ambiente online para aplicar recursos\", \"Banco físico\", \"Agência de crédito\", \"Loja de ações\"]', 'Ambiente online para aplicar recursos'),
(123, 5, 5, 'Como comparar investimentos em renda fixa?', '[\"Analisando rentabilidade, liquidez e risco\", \"Ouvindo amigos\", \"Vendo propagandas\", \"Escolhendo o mais caro\"]', 'Analisando rentabilidade, liquidez e risco'),
(124, 5, 5, 'Qual é o papel da diversificação?', '[\"Reduzir riscos e equilibrar retornos\", \"Aumentar risco\", \"Ignorar metas\", \"Focar em um único ativo\"]', 'Reduzir riscos e equilibrar retornos'),
(125, 5, 5, 'O que é uma carteira conservadora?', '[\"Com foco em renda fixa e baixo risco\", \"Com foco em ações\", \"Com foco em criptomoedas\", \"Com foco em imóveis\"]', 'Com foco em renda fixa e baixo risco'),
(126, 6, 1, 'O que é renda variável?', '[\"Investimentos com retorno incerto\", \"Investimentos com retorno fixo\", \"Aplicações garantidas pelo governo\", \"Depósitos bancários\"]', 'Investimentos com retorno incerto'),
(127, 6, 1, 'Qual é um exemplo de ativo de renda variável?', '[\"Ações\", \"CDB\", \"Tesouro Selic\", \"LCI\"]', 'Ações'),
(128, 6, 1, 'O que é uma ação?', '[\"Parte do capital de uma empresa\", \"Título público\", \"Crédito bancário\", \"Imposto sobre consumo\"]', 'Parte do capital de uma empresa'),
(129, 6, 1, 'O que é dividendos?', '[\"Lucros distribuídos aos acionistas\", \"Taxas de corretagem\", \"Impostos sobre ações\", \"Despesas operacionais\"]', 'Lucros distribuídos aos acionistas'),
(130, 6, 1, 'O que é uma corretora?', '[\"Instituição que intermedia compra e venda de ativos\", \"Banco estatal\", \"Empresa de crédito\", \"Agência de seguros\"]', 'Instituição que intermedia compra e venda de ativos'),
(131, 6, 1, 'O que é B3?', '[\"Bolsa de valores brasileira\", \"Banco central\", \"Empresa estatal\", \"Índice de inflação\"]', 'Bolsa de valores brasileira'),
(132, 6, 1, 'O que é mercado primário?', '[\"Onde ativos são emitidos pela primeira vez\", \"Onde ações são revendidas\", \"Onde títulos vencem\", \"Onde há liquidação\"]', 'Onde ativos são emitidos pela primeira vez'),
(133, 6, 1, 'O que é mercado secundário?', '[\"Onde ativos são negociados entre investidores\", \"Onde ações são lançadas\", \"Onde há IPOs\", \"Onde há dividendos\"]', 'Onde ativos são negociados entre investidores'),
(134, 6, 1, 'O que é IPO?', '[\"Oferta pública inicial de ações\", \"Imposto sobre patrimônio\", \"Índice de preços oficial\", \"Investimento privado obrigatório\"]', 'Oferta pública inicial de ações'),
(135, 6, 1, 'O que é liquidez na renda variável?', '[\"Facilidade de vender um ativo\", \"Rentabilidade garantida\", \"Prazo fixo\", \"Taxa de imposto\"]', 'Facilidade de vender um ativo'),
(136, 6, 2, 'O que é volatilidade?', '[\"Oscilação de preços dos ativos\", \"Rentabilidade fixa\", \"Liquidez garantida\", \"Prazo de vencimento\"]', 'Oscilação de preços dos ativos'),
(137, 6, 2, 'O que é análise fundamentalista?', '[\"Avaliação dos fundamentos da empresa\", \"Estudo de gráficos\", \"Análise de risco\", \"Cálculo de imposto\"]', 'Avaliação dos fundamentos da empresa'),
(138, 6, 2, 'O que é análise técnica?', '[\"Estudo de gráficos e padrões\", \"Avaliação de balanços\", \"Estudo de mercado\", \"Cálculo de dividendos\"]', 'Estudo de gráficos e padrões'),
(139, 6, 2, 'O que é uma carteira de ações?', '[\"Conjunto de ativos de renda variável\", \"Conta bancária\", \"Plano de previdência\", \"Lista de dívidas\"]', 'Conjunto de ativos de renda variável'),
(140, 6, 2, 'O que é diversificação?', '[\"Distribuição de investimentos para reduzir risco\", \"Compra de um único ativo\", \"Venda de ações\", \"Investimento em renda fixa\"]', 'Distribuição de investimentos para reduzir risco'),
(141, 6, 2, 'O que é risco sistêmico?', '[\"Risco que afeta todo o mercado\", \"Risco individual de uma empresa\", \"Risco de liquidez\", \"Risco de crédito\"]', 'Risco que afeta todo o mercado'),
(142, 6, 2, 'O que é risco específico?', '[\"Risco ligado a um ativo ou empresa\", \"Risco de inflação\", \"Risco de juros\", \"Risco de mercado\"]', 'Risco ligado a um ativo ou empresa'),
(143, 6, 2, 'O que é stop loss?', '[\"Ordem de venda para limitar perdas\", \"Ordem de compra automática\", \"Limite de crédito\", \"Taxa de corretagem\"]', 'Ordem de venda para limitar perdas'),
(144, 6, 2, 'O que é stop gain?', '[\"Ordem de venda para garantir lucros\", \"Ordem de compra automática\", \"Limite de crédito\", \"Taxa de corretagem\"]', 'Ordem de venda para garantir lucros'),
(145, 6, 2, 'O que é home broker?', '[\"Plataforma online para negociar ativos\", \"Agência bancária\", \"Sistema de crédito\", \"Conta poupança\"]', 'Plataforma online para negociar ativos'),
(146, 6, 3, 'O que é um fundo de ações?', '[\"Fundo que investe majoritariamente em ações\", \"Fundo de renda fixa\", \"Fundo imobiliário\", \"Fundo cambial\"]', 'Fundo que investe majoritariamente em ações'),
(147, 6, 3, 'O que é um ETF?', '[\"Fundo negociado em bolsa\", \"Título público\", \"Conta bancária\", \"Plano de previdência\"]', 'Fundo negociado em bolsa'),
(148, 6, 3, 'O que é um índice de ações?', '[\"Indicador do desempenho de um grupo de ações\", \"Taxa de câmbio\", \"Valor do imposto\", \"Rentabilidade fixa\"]', 'Indicador do desempenho de um grupo de ações'),
(149, 6, 3, 'O que é Ibovespa?', '[\"Principal índice da bolsa brasileira\", \"Taxa de juros\", \"Imposto sobre ações\", \"Fundo de crédito\"]', 'Principal índice da bolsa brasileira'),
(150, 6, 3, 'O que é uma small cap?', '[\"Empresa de pequeno porte na bolsa\", \"Empresa estatal\", \"Empresa sem ações\", \"Empresa de grande porte\"]', 'Empresa de pequeno porte na bolsa'),
(151, 6, 3, 'O que é uma blue chip?', '[\"Empresa consolidada e de grande valor\", \"Empresa nova\", \"Empresa sem lucro\", \"Empresa de risco elevado\"]', 'Empresa consolidada e de grande valor'),
(152, 6, 3, 'O que é uma ação ordinária?', '[\"Dá direito a voto\", \"Não dá direito a voto\", \"É preferencial\", \"É estatal\"]', 'Dá direito a voto'),
(153, 6, 3, 'O que é uma ação preferencial?', '[\"Dá prioridade no recebimento de dividendos\", \"Dá direito a voto\", \"É estatal\", \"É de risco elevado\"]', 'Dá prioridade no recebimento de dividendos'),
(154, 6, 3, 'O que é uma empresa listada?', '[\"Empresa com ações negociadas na bolsa\", \"Empresa privada\", \"Empresa estatal\", \"Empresa sem capital\"]', 'Empresa com ações negociadas na bolsa'),
(155, 6, 3, 'O que é um investidor pessoa física?', '[\"Indivíduo que investe com CPF\", \"Empresa que investe\", \"Banco estatal\", \"Corretora de valores\"]', 'Indivíduo que investe com CPF'),
(156, 6, 4, 'O que é um swing trade?', '[\"Compra e venda de ações em poucos dias\", \"Investimento de longo prazo\", \"Compra mensal de ações\", \"Venda de ações por impulso\"]', 'Compra e venda de ações em poucos dias'),
(157, 6, 4, 'O que é um day trade?', '[\"Compra e venda de ações no mesmo dia\", \"Compra mensal de ações\", \"Investimento de longo prazo\", \"Venda de ações por impulso\"]', 'Compra e venda de ações no mesmo dia'),
(158, 6, 4, 'O que é uma ordem limitada?', '[\"Compra ou venda com preço definido\", \"Compra automática\", \"Venda sem controle\", \"Compra por impulso\"]', 'Compra ou venda com preço definido'),
(159, 6, 4, 'O que é uma ordem a mercado?', '[\"Compra ou venda imediata ao preço atual\", \"Compra parcelada\", \"Venda programada\", \"Compra com desconto\"]', 'Compra ou venda imediata ao preço atual'),
(160, 6, 4, 'O que é um candle?', '[\"Representação gráfica de preço\", \"Tipo de ação\", \"Taxa de imposto\", \"Valor de liquidez\"]', 'Representação gráfica de preço'),
(161, 6, 4, 'O que é resistência em análise técnica?', '[\"Nível onde o preço tende a parar de subir\", \"Nível de suporte\", \"Valor de liquidez\", \"Taxa de imposto\"]', 'Nível onde o preço tende a parar de subir'),
(162, 6, 4, 'O que é suporte em análise técnica?', '[\"Nível onde o preço tende a parar de cair\", \"Nível de resistência\", \"Valor de liquidez\", \"Taxa de imposto\"]', 'Nível onde o preço tende a parar de cair'),
(163, 6, 4, 'O que é um gráfico de linha?', '[\"Representa a variação de preço ao longo do tempo\", \"Mostra volume de negociação\", \"Indica dividendos\", \"Exibe taxas de juros\"]', 'Representa a variação de preço ao longo do tempo'),
(164, 6, 4, 'O que é volume financeiro?', '[\"Valor total negociado de um ativo\", \"Quantidade de ações\", \"Preço unitário\", \"Taxa de imposto\"]', 'Valor total negociado de um ativo'),
(165, 6, 4, 'O que é uma tendência de alta?', '[\"Sequência de preços ascendentes\", \"Queda de preços\", \"Estabilidade\", \"Oscilação lateral\"]', 'Sequência de preços ascendentes'),
(166, 6, 5, 'O que é uma tendência de baixa?', '[\"Sequência de preços descendentes\", \"Alta de preços\", \"Estabilidade\", \"Oscilação lateral\"]', 'Sequência de preços descendentes'),
(167, 6, 5, 'O que é um rompimento de resistência?', '[\"Preço supera um nível de barreira\", \"Preço cai abaixo do suporte\", \"Volume reduzido\", \"Liquidez nula\"]', 'Preço supera um nível de barreira'),
(168, 6, 5, 'O que é um pullback?', '[\"Retorno temporário após rompimento\", \"Queda definitiva\", \"Alta contínua\", \"Liquidez nula\"]', 'Retorno temporário após rompimento'),
(169, 6, 5, 'O que é um candle de alta?', '[\"Representa fechamento acima da abertura\", \"Representa queda de preço\", \"Representa estabilidade\", \"Representa volume\"]', 'Representa fechamento acima da abertura'),
(170, 6, 5, 'O que é um candle de baixa?', '[\"Representa fechamento abaixo da abertura\", \"Representa alta de preço\", \"Representa estabilidade\", \"Representa volume\"]', 'Representa fechamento abaixo da abertura'),
(171, 6, 5, 'O que é um gap?', '[\"Espaço entre preços de fechamento e abertura\", \"Volume de negociação\", \"Taxa de imposto\", \"Valor de liquidez\"]', 'Espaço entre preços de fechamento e abertura'),
(172, 6, 5, 'O que é um gráfico de barras?', '[\"Representa variação de preço e volume\", \"Mostra apenas volume\", \"Indica dividendos\", \"Exibe taxas de juros\"]', 'Representa variação de preço e volume'),
(173, 6, 5, 'O que é um indicador técnico?', '[\"Ferramenta para análise de tendência e força\", \"Taxa de imposto\", \"Valor de liquidez\", \"Preço unitário\"]', 'Ferramenta para análise de tendência e força'),
(174, 6, 5, 'O que é uma média móvel?', '[\"Indicador que suaviza variações de preço\", \"Valor de liquidez\", \"Taxa de imposto\", \"Preço unitário\"]', 'Indicador que suaviza variações de preço'),
(175, 6, 5, 'O que é um investidor de longo prazo?', '[\"Mantém ativos por anos visando valorização\", \"Compra e vende no mesmo dia\", \"Opera com margem\", \"Investe em renda fixa\"]', 'Mantém ativos por anos visando valorização'),
(176, 7, 1, 'O que é trader?', '[\"Pessoa que compra e vende ativos com frequência\", \"Investidor de longo prazo\", \"Corretor de imóveis\", \"Gestor de fundos\"]', 'Pessoa que compra e vende ativos com frequência'),
(177, 7, 1, 'O que é análise técnica?', '[\"Estudo de gráficos e padrões de preço\", \"Análise de balanços\", \"Estudo de fundamentos\", \"Cálculo de impostos\"]', 'Estudo de gráficos e padrões de preço'),
(178, 7, 1, 'O que é scalping?', '[\"Operações rápidas com poucos minutos\", \"Investimento em imóveis\", \"Compra mensal de ações\", \"Venda de ativos por impulso\"]', 'Operações rápidas com poucos minutos'),
(179, 7, 1, 'O que é alavancagem?', '[\"Uso de capital emprestado para ampliar operações\", \"Compra à vista\", \"Investimento conservador\", \"Venda de ativos fixos\"]', 'Uso de capital emprestado para ampliar operações'),
(180, 7, 1, 'O que é margem de garantia?', '[\"Valor exigido para operar alavancado\", \"Lucro líquido\", \"Taxa de corretagem\", \"Valor do imposto\"]', 'Valor exigido para operar alavancado'),
(181, 7, 1, 'O que é um candle?', '[\"Representação gráfica de preço\", \"Tipo de ação\", \"Taxa de imposto\", \"Valor de liquidez\"]', 'Representação gráfica de preço'),
(182, 7, 1, 'O que é um gráfico de barras?', '[\"Representa variação de preço e volume\", \"Mostra apenas volume\", \"Indica dividendos\", \"Exibe taxas de juros\"]', 'Representa variação de preço e volume'),
(183, 7, 1, 'O que é uma tendência de alta?', '[\"Sequência de preços ascendentes\", \"Queda de preços\", \"Estabilidade\", \"Oscilação lateral\"]', 'Sequência de preços ascendentes'),
(184, 7, 1, 'O que é uma tendência de baixa?', '[\"Sequência de preços descendentes\", \"Alta de preços\", \"Estabilidade\", \"Oscilação lateral\"]', 'Sequência de preços descendentes'),
(185, 7, 1, 'O que é um pullback?', '[\"Retorno temporário após rompimento\", \"Queda definitiva\", \"Alta contínua\", \"Liquidez nula\"]', 'Retorno temporário após rompimento'),
(186, 7, 2, 'O que é stop loss?', '[\"Ordem de venda para limitar perdas\", \"Ordem de compra automática\", \"Limite de crédito\", \"Taxa de corretagem\"]', 'Ordem de venda para limitar perdas'),
(187, 7, 2, 'O que é stop gain?', '[\"Ordem de venda para garantir lucros\", \"Ordem de compra automática\", \"Limite de crédito\", \"Taxa de corretagem\"]', 'Ordem de venda para garantir lucros'),
(188, 7, 2, 'O que é um setup de trade?', '[\"Conjunto de regras para operar\", \"Valor de imposto\", \"Tipo de ação\", \"Plano de previdência\"]', 'Conjunto de regras para operar'),
(189, 7, 2, 'O que é gerenciamento de risco?', '[\"Controle de perdas e exposição\", \"Aumento de alavancagem\", \"Compra por impulso\", \"Venda sem análise\"]', 'Controle de perdas e exposição'),
(190, 7, 2, 'O que é um backtest?', '[\"Teste de estratégia com dados passados\", \"Compra de ações\", \"Venda de ativos\", \"Cálculo de imposto\"]', 'Teste de estratégia com dados passados'),
(191, 7, 2, 'O que é um indicador técnico?', '[\"Ferramenta para análise de tendência e força\", \"Taxa de imposto\", \"Valor de liquidez\", \"Preço unitário\"]', 'Ferramenta para análise de tendência e força'),
(192, 7, 2, 'O que é média móvel?', '[\"Indicador que suaviza variações de preço\", \"Valor de liquidez\", \"Taxa de imposto\", \"Preço unitário\"]', 'Indicador que suaviza variações de preço'),
(193, 7, 2, 'O que é RSI?', '[\"Índice de força relativa\", \"Taxa de juros\", \"Valor de liquidez\", \"Preço unitário\"]', 'Índice de força relativa'),
(194, 7, 2, 'O que é MACD?', '[\"Indicador de convergência e divergência de médias\", \"Taxa de imposto\", \"Valor de liquidez\", \"Preço unitário\"]', 'Indicador de convergência e divergência de médias'),
(195, 7, 2, 'O que é volume financeiro?', '[\"Valor total negociado de um ativo\", \"Quantidade de ações\", \"Preço unitário\", \"Taxa de imposto\"]', 'Valor total negociado de um ativo'),
(196, 7, 3, 'O que é um candle de alta?', '[\"Fechamento acima da abertura\", \"Fechamento abaixo da abertura\", \"Volume reduzido\", \"Preço estável\"]', 'Fechamento acima da abertura'),
(197, 7, 3, 'O que é um candle de baixa?', '[\"Fechamento abaixo da abertura\", \"Fechamento acima da abertura\", \"Volume aumentado\", \"Preço estável\"]', 'Fechamento abaixo da abertura'),
(198, 7, 3, 'O que é um gap de alta?', '[\"Abertura acima do fechamento anterior\", \"Queda abrupta\", \"Volume nulo\", \"Liquidez baixa\"]', 'Abertura acima do fechamento anterior'),
(199, 7, 3, 'O que é um gap de baixa?', '[\"Abertura abaixo do fechamento anterior\", \"Alta abrupta\", \"Volume nulo\", \"Liquidez baixa\"]', 'Abertura abaixo do fechamento anterior'),
(200, 7, 3, 'O que é um rompimento falso?', '[\"Movimento que ultrapassa resistência e volta\", \"Queda definitiva\", \"Alta contínua\", \"Liquidez nula\"]', 'Movimento que ultrapassa resistência e volta'),
(201, 7, 3, 'O que é um candle martelo?', '[\"Indica possível reversão de baixa\", \"Indica continuidade de alta\", \"Indica estabilidade\", \"Indica volume alto\"]', 'Indica possível reversão de baixa'),
(202, 7, 3, 'O que é um candle estrela cadente?', '[\"Indica possível reversão de alta\", \"Indica queda contínua\", \"Indica volume baixo\", \"Indica estabilidade\"]', 'Indica possível reversão de alta'),
(203, 7, 3, 'O que é um candle doji?', '[\"Indecisão entre compradores e vendedores\", \"Alta forte\", \"Queda forte\", \"Volume elevado\"]', 'Indecisão entre compradores e vendedores'),
(204, 7, 3, 'O que é um candle engolfo de alta?', '[\"Candle que cobre totalmente o anterior de baixa\", \"Candle pequeno\", \"Candle estável\", \"Candle com volume baixo\"]', 'Candle que cobre totalmente o anterior de baixa'),
(205, 7, 3, 'O que é um candle engolfo de baixa?', '[\"Candle que cobre totalmente o anterior de alta\", \"Candle pequeno\", \"Candle estável\", \"Candle com volume baixo\"]', 'Candle que cobre totalmente o anterior de alta'),
(206, 7, 4, 'O que é um trader institucional?', '[\"Opera grandes volumes para empresas\", \"Pessoa física com CPF\", \"Corretor autônomo\", \"Investidor de longo prazo\"]', 'Opera grandes volumes para empresas'),
(207, 7, 4, 'O que é um trader retail?', '[\"Pessoa física que opera com recursos próprios\", \"Banco estatal\", \"Empresa de crédito\", \"Gestor de fundos\"]', 'Pessoa física que opera com recursos próprios'),
(208, 7, 4, 'O que é um robô de trade?', '[\"Sistema automatizado de operações\", \"Corretor humano\", \"Banco digital\", \"Investidor passivo\"]', 'Sistema automatizado de operações'),
(209, 7, 4, 'O que é uma estratégia de rompimento?', '[\"Compra após ultrapassar resistência\", \"Venda por impulso\", \"Compra por emoção\", \"Venda sem análise\"]', 'Compra após ultrapassar resistência'),
(210, 7, 4, 'O que é uma estratégia de reversão?', '[\"Compra após sinal de mudança de tendência\", \"Venda por impulso\", \"Compra por emoção\", \"Venda sem análise\"]', 'Compra após sinal de mudança de tendência'),
(211, 7, 4, 'O que é um trade de notícia?', '[\"Operação baseada em eventos econômicos\", \"Compra por impulso\", \"Venda sem análise\", \"Investimento passivo\"]', 'Operação baseada em eventos econômicos'),
(212, 7, 4, 'O que é um trade de fluxo?', '[\"Operação baseada em volume e agressão de ordens\", \"Compra por emoção\", \"Venda sem análise\", \"Investimento passivo\"]', 'Operação baseada em volume e agressão de ordens'),
(213, 7, 4, 'O que é tape reading?', '[\"Leitura do fluxo de ordens em tempo real\", \"Análise de balanços\", \"Estudo de gráficos\", \"Cálculo de imposto\"]', 'Leitura do fluxo de ordens em tempo real'),
(214, 7, 4, 'O que é book de ofertas?', '[\"Lista de ordens de compra e venda\", \"Lista de ativos\", \"Lista de impostos\", \"Lista de corretoras\"]', 'Lista de ordens de compra e venda'),
(215, 7, 4, 'O que é agressão de ordem?', '[\"Execução imediata contra o melhor preço\", \"Ordem limitada\", \"Ordem passiva\", \"Ordem de stop\"]', 'Execução imediata contra o melhor preço'),
(216, 7, 5, 'O que é um trader scalper?', '[\"Opera com rapidez e pequenos lucros\", \"Opera com grandes volumes\", \"Investe a longo prazo\", \"Opera sem análise\"]', 'Opera com rapidez e pequenos lucros'),
(217, 7, 5, 'O que é um trader swing?', '[\"Mantém posições por dias ou semanas\", \"Opera no mesmo dia\", \"Opera sem análise\", \"Investe em renda fixa\"]', 'Mantém posições por dias ou semanas'),
(218, 7, 5, 'O que é um trader position?', '[\"Mantém posições por semanas ou meses\", \"Opera em segundos\", \"Opera sem análise\", \"Investe em renda fixa\"]', 'Mantém posições por semanas ou meses'),
(219, 7, 5, 'O que é um trader técnico?', '[\"Baseia decisões em gráficos e indicadores\", \"Opera por emoção\", \"Investe sem análise\", \"Opera por notícias\"]', 'Baseia decisões em gráficos e indicadores'),
(220, 7, 5, 'O que é um trader fundamentalista?', '[\"Baseia decisões em fundamentos econômicos\", \"Opera por emoção\", \"Investe sem análise\", \"Opera por gráficos\"]', 'Baseia decisões em fundamentos econômicos'),
(221, 7, 5, 'O que é um trader emocional?', '[\"Opera com base em sentimentos\", \"Opera com base em análise técnica\", \"Opera com robôs\", \"Opera com fundamentos\"]', 'Opera com base em sentimentos'),
(222, 7, 5, 'O que é um plano de trade?', '[\"Estratégia definida antes de operar\", \"Compra por impulso\", \"Venda sem análise\", \"Investimento passivo\"]', 'Estratégia definida antes de operar'),
(223, 7, 5, 'O que é disciplina no trading?', '[\"Seguir regras e estratégias com consistência\", \"Operar por emoção\", \"Ignorar análise\", \"Investir sem metas\"]', 'Seguir regras e estratégias com consistência'),
(224, 7, 5, 'O que é consistência no trading?', '[\"Manter resultados positivos ao longo do tempo\", \"Ganhar muito em um dia\", \"Operar sem controle\", \"Investir sem metas\"]', 'Manter resultados positivos ao longo do tempo'),
(225, 7, 5, 'O que é controle emocional no trading?', '[\"Capacidade de manter decisões racionais\", \"Operar por impulso\", \"Investir sem análise\", \"Vender por medo\"]', 'Capacidade de manter decisões racionais'),
(226, 8, 1, 'O que é a certificação CPA-10?', '[\"Certificação básica para profissionais do mercado financeiro\", \"Certificação para corretores de imóveis\", \"Certificação de TI\", \"Certificação de inglês\"]', 'Certificação básica para profissionais do mercado financeiro'),
(227, 8, 1, 'Qual instituição aplica a CPA-10?', '[\"ANBIMA\", \"CVM\", \"BACEN\", \"B3\"]', 'ANBIMA'),
(228, 8, 1, 'O que é a certificação CPA-20?', '[\"Certificação avançada para profissionais que atuam com investidores qualificados\", \"Certificação para advogados\", \"Certificação de seguros\", \"Certificação de contabilidade\"]', 'Certificação avançada para profissionais que atuam com investidores qualificados'),
(229, 8, 1, 'Qual é o foco da certificação CEA?', '[\"Consultoria de investimentos\", \"Gestão de fundos\", \"Auditoria contábil\", \"Análise técnica\"]', 'Consultoria de investimentos'),
(230, 8, 1, 'O que significa ANBIMA?', '[\"Associação Brasileira das Entidades dos Mercados Financeiro e de Capitais\", \"Agência Nacional de Bancos e Investimentos\", \"Associação Nacional de Bancos e Imóveis\", \"Administração Brasileira de Investimentos e Mercado\"]', 'Associação Brasileira das Entidades dos Mercados Financeiro e de Capitais'),
(231, 8, 1, 'Qual certificação é exigida para atuar como agente autônomo?', '[\"AAI\", \"CPA-10\", \"CEA\", \"CNPI\"]', 'AAI'),
(232, 8, 1, 'O que é a certificação CFP?', '[\"Certificação internacional para planejadores financeiros\", \"Certificação de fundos imobiliários\", \"Certificação de previdência\", \"Certificação de crédito pessoal\"]', 'Certificação internacional para planejadores financeiros'),
(233, 8, 1, 'Qual entidade emite a CFP no Brasil?', '[\"Planejar\", \"ANBIMA\", \"CVM\", \"BACEN\"]', 'Planejar'),
(234, 8, 1, 'O que é a certificação CNPI?', '[\"Certificação para analistas de investimentos\", \"Certificação de crédito pessoal\", \"Certificação de seguros\", \"Certificação de imóveis\"]', 'Certificação para analistas de investimentos'),
(235, 8, 1, 'Qual é o órgão regulador do CNPI?', '[\"APIMEC\", \"ANBIMA\", \"CVM\", \"BACEN\"]', 'APIMEC'),
(236, 8, 1, 'Quantas áreas de atuação existem no CNPI?', '[\"Fundamentalista, técnico e pleno\", \"Básico e avançado\", \"Investidor e consultor\", \"Gestor e analista\"]', 'Fundamentalista, técnico e pleno'),
(237, 8, 1, 'Qual é o foco da certificação CGA?', '[\"Gestão de recursos de terceiros\", \"Consultoria jurídica\", \"Auditoria contábil\", \"Planejamento tributário\"]', 'Gestão de recursos de terceiros'),
(238, 8, 1, 'Quem pode prestar a CGA?', '[\"Profissionais que atuam como gestores\", \"Estudantes de economia\", \"Corretores de imóveis\", \"Contadores autônomos\"]', 'Profissionais que atuam como gestores'),
(239, 8, 1, 'Qual é a validade da CPA-10?', '[\"5 anos\", \"1 ano\", \"Indeterminada\", \"2 anos\"]', '5 anos'),
(240, 8, 1, 'Qual é a validade da CPA-20?', '[\"5 anos\", \"1 ano\", \"Indeterminada\", \"2 anos\"]', '5 anos'),
(241, 8, 1, 'Qual é a validade da CEA?', '[\"5 anos\", \"1 ano\", \"Indeterminada\", \"2 anos\"]', '5 anos'),
(242, 8, 1, 'Qual é a validade da CFP?', '[\"2 anos com renovação\", \"5 anos\", \"Indeterminada\", \"1 ano\"]', '2 anos com renovação'),
(243, 8, 1, 'Qual é a validade da CNPI?', '[\"2 anos com atualização\", \"5 anos\", \"Indeterminada\", \"1 ano\"]', '2 anos com atualização'),
(244, 8, 1, 'Qual é a validade da CGA?', '[\"2 anos com atualização\", \"5 anos\", \"Indeterminada\", \"1 ano\"]', '2 anos com atualização'),
(245, 8, 1, 'Qual certificação é voltada para quem deseja atuar com planejamento financeiro pessoal?', '[\"CFP\", \"CPA-10\", \"CGA\", \"CNPI\"]', 'CFP'),
(246, 8, 1, 'Qual certificação é exigida para atuar com produtos de investimento em agências bancárias?', '[\"CPA-10\", \"CGA\", \"CNPI\", \"CFP\"]', 'CPA-10'),
(247, 8, 1, 'Qual certificação é exigida para atuar com clientes de alta renda?', '[\"CPA-20\", \"CPA-10\", \"CGA\", \"CNPI\"]', 'CPA-20'),
(248, 8, 1, 'Qual certificação permite recomendar produtos de investimento?', '[\"CEA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CEA'),
(249, 8, 1, 'Qual certificação é reconhecida internacionalmente?', '[\"CFP\", \"CPA-20\", \"CGA\", \"CNPI\"]', 'CFP'),
(250, 8, 1, 'Qual certificação é voltada para analistas técnicos?', '[\"CNPI-T\", \"CPA-10\", \"CFP\", \"CGA\"]', 'CNPI-T'),
(251, 8, 2, 'Qual certificação é voltada para quem deseja atuar como consultor de investimentos?', '[\"CEA\", \"CPA-10\", \"CNPI\", \"CGA\"]', 'CEA'),
(252, 8, 2, 'Qual certificação é exigida para atuar como gestor de recursos?', '[\"CGA\", \"CPA-20\", \"CFP\", \"CNPI\"]', 'CGA'),
(253, 8, 2, 'Qual certificação é voltada para analistas fundamentalistas?', '[\"CNPI\", \"CPA-10\", \"CEA\", \"CFP\"]', 'CNPI'),
(254, 8, 2, 'Qual certificação é voltada para analistas técnicos?', '[\"CNPI-T\", \"CPA-20\", \"CGA\", \"CFP\"]', 'CNPI-T'),
(255, 8, 2, 'Qual certificação exige experiência profissional para obtenção?', '[\"CFP\", \"CPA-10\", \"CNPI\", \"CEA\"]', 'CFP'),
(256, 8, 2, 'Qual certificação permite atuar com planejamento financeiro familiar?', '[\"CFP\", \"CPA-20\", \"CNPI\", \"CGA\"]', 'CFP'),
(257, 8, 2, 'Qual certificação é reconhecida internacionalmente e exige prova em inglês?', '[\"CFP\", \"CPA-10\", \"CNPI\", \"CEA\"]', 'CFP'),
(258, 8, 2, 'Qual certificação é voltada para profissionais que atuam com clientes de alta renda?', '[\"CPA-20\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CPA-20'),
(259, 8, 2, 'Qual certificação é voltada para profissionais que atuam em agências bancárias?', '[\"CPA-10\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CPA-10'),
(260, 8, 2, 'Qual certificação exige atualização periódica com cursos ou provas?', '[\"CNPI\", \"CFP\", \"CGA\", \"CPA-10\"]', 'CNPI'),
(261, 8, 2, 'Qual certificação exige comprovação de experiência e ética?', '[\"CFP\", \"CPA-20\", \"CNPI\", \"CEA\"]', 'CFP'),
(262, 8, 2, 'Qual certificação é voltada para profissionais que recomendam produtos de investimento?', '[\"CEA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CEA'),
(263, 8, 2, 'Qual certificação é exigida para atuar como analista de valores mobiliários?', '[\"CNPI\", \"CPA-20\", \"CFP\", \"CGA\"]', 'CNPI'),
(264, 8, 2, 'Qual certificação é voltada para quem deseja atuar com gestão de fundos?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(265, 8, 2, 'Qual certificação exige prova aplicada pela ANBIMA?', '[\"CPA-10\", \"CPA-20\", \"CEA\", \"Todas as anteriores\"]', 'Todas as anteriores'),
(266, 8, 2, 'Qual certificação exige prova aplicada pela Planejar?', '[\"CFP\", \"CPA-10\", \"CNPI\", \"CGA\"]', 'CFP'),
(267, 8, 2, 'Qual certificação exige prova aplicada pela APIMEC?', '[\"CNPI\", \"CPA-10\", \"CFP\", \"CEA\"]', 'CNPI'),
(268, 8, 2, 'Qual certificação exige prova aplicada pela ANBIMA e permite atuar como consultor?', '[\"CEA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CEA'),
(269, 8, 2, 'Qual certificação exige prova aplicada pela ANBIMA e permite atuar com clientes de alta renda?', '[\"CPA-20\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CPA-20');
INSERT INTO `quiz_fases` (`id`, `trilha_id`, `fase_numero`, `pergunta`, `opcoes`, `resposta_correta`) VALUES
(270, 8, 2, 'Qual certificação exige prova aplicada pela ANBIMA e permite atuar com produtos básicos?', '[\"CPA-10\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CPA-10'),
(271, 8, 2, 'Qual certificação exige prova aplicada pela ANBIMA e permite atuar com planejamento financeiro?', '[\"CEA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CEA'),
(272, 8, 2, 'Qual certificação exige prova aplicada pela Planejar e permite atuar com planejamento financeiro pessoal?', '[\"CFP\", \"CPA-10\", \"CNPI\", \"CGA\"]', 'CFP'),
(273, 8, 2, 'Qual certificação exige prova aplicada pela APIMEC e permite atuar como analista técnico?', '[\"CNPI-T\", \"CPA-10\", \"CFP\", \"CGA\"]', 'CNPI-T'),
(274, 8, 2, 'Qual certificação exige prova aplicada pela APIMEC e permite atuar como analista fundamentalista?', '[\"CNPI\", \"CPA-10\", \"CFP\", \"CGA\"]', 'CNPI'),
(275, 8, 2, 'Qual certificação exige prova aplicada pela ANBIMA e permite atuar como gestor?', '[\"CGA\", \"CPA-10\", \"CFP\", \"CNPI\"]', 'CGA'),
(276, 8, 3, 'Qual certificação é voltada para profissionais que atuam com gestão de carteiras?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(277, 8, 3, 'Qual certificação exige conhecimento em produtos financeiros e perfil de investidor?', '[\"CEA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CEA'),
(278, 8, 3, 'Qual certificação é voltada para profissionais que atuam com planejamento tributário?', '[\"CFP\", \"CPA-10\", \"CNPI\", \"CGA\"]', 'CFP'),
(279, 8, 3, 'Qual certificação exige domínio de ética profissional?', '[\"CFP\", \"CPA-20\", \"CNPI\", \"CEA\"]', 'CFP'),
(280, 8, 3, 'Qual certificação exige conhecimento em fundos de investimento?', '[\"CPA-20\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CPA-20'),
(281, 8, 3, 'Qual certificação exige conhecimento em derivativos?', '[\"CPA-20\", \"CGA\", \"CNPI\", \"CFP\"]', 'CGA'),
(282, 8, 3, 'Qual certificação exige conhecimento em previdência privada?', '[\"CPA-10\", \"CPA-20\", \"CFP\", \"CNPI\"]', 'CFP'),
(283, 8, 3, 'Qual certificação exige conhecimento em renda fixa e variável?', '[\"CPA-20\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CPA-20'),
(284, 8, 3, 'Qual certificação exige conhecimento em suitability?', '[\"CEA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CEA'),
(285, 8, 3, 'Qual certificação exige conhecimento em perfil de investidor?', '[\"CEA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CEA'),
(286, 8, 3, 'Qual certificação exige conhecimento em alocação de ativos?', '[\"CFP\", \"CPA-20\", \"CNPI\", \"CGA\"]', 'CFP'),
(287, 8, 3, 'Qual certificação exige conhecimento em planejamento sucessório?', '[\"CFP\", \"CPA-10\", \"CNPI\", \"CEA\"]', 'CFP'),
(288, 8, 3, 'Qual certificação exige conhecimento em governança corporativa?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(289, 8, 3, 'Qual certificação exige conhecimento em compliance?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(290, 8, 3, 'Qual certificação exige conhecimento em risco de crédito?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(291, 8, 3, 'Qual certificação exige conhecimento em risco de mercado?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(292, 8, 3, 'Qual certificação exige conhecimento em risco de liquidez?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(293, 8, 3, 'Qual certificação exige conhecimento em risco operacional?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(294, 8, 3, 'Qual certificação exige conhecimento em regulação do mercado financeiro?', '[\"CPA-20\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CPA-20'),
(295, 8, 3, 'Qual certificação exige conhecimento em produtos estruturados?', '[\"CPA-20\", \"CGA\", \"CNPI\", \"CFP\"]', 'CGA'),
(296, 8, 3, 'Qual certificação exige conhecimento em fundos exclusivos?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(297, 8, 3, 'Qual certificação exige conhecimento em fundos multimercado?', '[\"CPA-20\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CPA-20'),
(298, 8, 3, 'Qual certificação exige conhecimento em fundos de ações?', '[\"CPA-20\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CPA-20'),
(299, 8, 3, 'Qual certificação exige conhecimento em fundos de renda fixa?', '[\"CPA-10\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CPA-10'),
(300, 8, 3, 'Qual certificação exige conhecimento em fundos de previdência?', '[\"CPA-20\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CPA-20'),
(301, 8, 4, 'Qual certificação exige conhecimento em ética e responsabilidade fiduciária?', '[\"CFP\", \"CPA-10\", \"CNPI\", \"CGA\"]', 'CFP'),
(302, 8, 4, 'Qual certificação exige conhecimento em planejamento de aposentadoria?', '[\"CFP\", \"CPA-20\", \"CNPI\", \"CEA\"]', 'CFP'),
(303, 8, 4, 'Qual certificação exige conhecimento em seguros e proteção?', '[\"CFP\", \"CPA-10\", \"CNPI\", \"CGA\"]', 'CFP'),
(304, 8, 4, 'Qual certificação exige conhecimento em sucessão patrimonial?', '[\"CFP\", \"CPA-20\", \"CNPI\", \"CEA\"]', 'CFP'),
(305, 8, 4, 'Qual certificação exige conhecimento em investimentos internacionais?', '[\"CFP\", \"CPA-10\", \"CNPI\", \"CGA\"]', 'CFP'),
(306, 8, 4, 'Qual certificação exige conhecimento em planejamento fiscal?', '[\"CFP\", \"CPA-20\", \"CNPI\", \"CEA\"]', 'CFP'),
(307, 8, 4, 'Qual certificação exige conhecimento em gestão de riscos?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(308, 8, 4, 'Qual certificação exige conhecimento em compliance e controles internos?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(309, 8, 4, 'Qual certificação exige conhecimento em governança de fundos?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(310, 8, 4, 'Qual certificação exige conhecimento em regulação da CVM?', '[\"CNPI\", \"CPA-20\", \"CFP\", \"CGA\"]', 'CNPI'),
(311, 8, 4, 'Qual certificação exige conhecimento em regulação do BACEN?', '[\"CPA-20\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CPA-20'),
(312, 8, 4, 'Qual certificação exige conhecimento em fundos de índice?', '[\"CPA-20\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CPA-20'),
(313, 8, 4, 'Qual certificação exige conhecimento em fundos de crédito privado?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(314, 8, 4, 'Qual certificação exige conhecimento em fundos de debêntures?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(315, 8, 4, 'Qual certificação exige conhecimento em fundos de infraestrutura?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(316, 8, 4, 'Qual certificação exige conhecimento em fundos de investimento em direitos creditórios?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(317, 8, 4, 'Qual certificação exige conhecimento em fundos de investimento em participações?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(318, 8, 4, 'Qual certificação exige conhecimento em fundos de investimento em cotas?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(319, 8, 4, 'Qual certificação exige conhecimento em fundos de investimento em ações?', '[\"CPA-20\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CPA-20'),
(320, 8, 4, 'Qual certificação exige conhecimento em fundos de investimento em renda fixa?', '[\"CPA-10\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CPA-10'),
(321, 8, 4, 'Qual certificação exige conhecimento em fundos de investimento em previdência?', '[\"CPA-20\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CPA-20'),
(322, 8, 4, 'Qual certificação exige conhecimento em fundos de investimento em multimercado?', '[\"CPA-20\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CPA-20'),
(323, 8, 4, 'Qual certificação exige conhecimento em fundos de investimento em ETFs?', '[\"CPA-20\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CPA-20'),
(324, 8, 4, 'Qual certificação exige conhecimento em fundos de investimento em FIDCs?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(325, 8, 4, 'Qual certificação exige conhecimento em fundos de investimento em FIPs?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(326, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em cotas de fundos?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(327, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos internacionais?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(328, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos estruturados?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(329, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos de crédito?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(330, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos de renda variável?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(331, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos de renda fixa?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(332, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos de liquidez?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(333, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos de risco?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(334, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos de longo prazo?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(335, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos de curto prazo?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(336, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos de crédito privado?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(337, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos de crédito público?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(338, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos de infraestrutura?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(339, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos de debêntures incentivadas?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(340, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos de FIDCs?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(341, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos de FIPs?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(342, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos de ETFs?', '[\"CGA\", \"CPA-20\", \"CNPI\", \"CFP\"]', 'CGA'),
(343, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos de commodities?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(344, 8, 5, 'Qual certificação exige conhecimento em fundos de investimento em ativos de derivativos?', '[\"CGA\", \"CPA-10\", \"CNPI\", \"CFP\"]', 'CGA'),
(345, 1, 1, 'O que é orçamento pessoal?', '[\"Planejamento de gastos e receitas\", \"Investimento em ações\", \"Compra parcelada\", \"Uso de cartão de crédito\"]', 'Planejamento de gastos e receitas'),
(346, 1, 1, 'Qual é a função do controle financeiro?', '[\"Evitar dívidas\", \"Aumentar impostos\", \"Reduzir salário\", \"Ignorar despesas\"]', 'Evitar dívidas'),
(347, 1, 1, 'Qual é o primeiro passo para organizar suas finanças?', '[\"Registrar todos os gastos\", \"Investir em ações\", \"Pedir empréstimo\", \"Ignorar dívidas\"]', 'Registrar todos os gastos'),
(348, 1, 1, 'O que é uma despesa fixa?', '[\"Gasto recorrente como aluguel\", \"Compra de roupas\", \"Viagem de férias\", \"Jantar fora\"]', 'Gasto recorrente como aluguel'),
(349, 1, 1, 'Qual é a importância de guardar notas fiscais?', '[\"Controlar gastos\", \"Ganhar cashback\", \"Evitar impostos\", \"Receber salário\"]', 'Controlar gastos'),
(350, 1, 2, 'O que é uma receita variável?', '[\"Comissão ou bônus\", \"Salário fixo\", \"Aluguel\", \"Conta de luz\"]', 'Comissão ou bônus'),
(351, 1, 2, 'Qual ferramenta ajuda no controle financeiro?', '[\"Planilha de gastos\", \"Cartão de crédito\", \"Cheque especial\", \"Empréstimo pessoal\"]', 'Planilha de gastos'),
(352, 1, 2, 'O que significa viver dentro do orçamento?', '[\"Gastar menos do que ganha\", \"Usar todo o limite do cartão\", \"Pedir empréstimos\", \"Ignorar dívidas\"]', 'Gastar menos do que ganha'),
(353, 1, 2, 'Qual é o impacto de não controlar os gastos?', '[\"Endividamento\", \"Aumento de patrimônio\", \"Redução de impostos\", \"Ganhos inesperados\"]', 'Endividamento'),
(354, 1, 2, 'O que é uma meta financeira?', '[\"Objetivo de poupar ou investir\", \"Despesas mensais\", \"Salário fixo\", \"Conta bancária\"]', 'Objetivo de poupar ou investir'),
(355, 2, 1, 'O que é uma meta financeira SMART?', '[\"Específica, Mensurável, Alcançável, Relevante, Temporal\", \"Simples, Móvel, Atingível, Realista, Técnica\", \"Segura, Mensal, Atual, Rápida, Transparente\", \"Sólida, Média, Acelerada, Rentável, Total\"]', 'Específica, Mensurável, Alcançável, Relevante, Temporal'),
(356, 2, 1, 'Qual é o benefício de definir metas financeiras?', '[\"Direcionar o uso do dinheiro\", \"Evitar pagar impostos\", \"Aumentar dívidas\", \"Ignorar orçamento\"]', 'Direcionar o uso do dinheiro'),
(357, 2, 1, 'Qual ferramenta ajuda a acompanhar metas?', '[\"Planilha de metas\", \"Cartão de crédito\", \"Cheque especial\", \"Conta corrente\"]', 'Planilha de metas'),
(358, 2, 1, 'O que é uma meta de curto prazo?', '[\"Objetivo para até 1 ano\", \"Objetivo para 10 anos\", \"Meta para aposentadoria\", \"Investimento em imóveis\"]', 'Objetivo para até 1 ano'),
(359, 2, 1, 'Qual é um exemplo de meta de longo prazo?', '[\"Comprar uma casa\", \"Pagar o almoço\", \"Comprar roupas\", \"Recarregar celular\"]', 'Comprar uma casa'),
(360, 2, 2, 'Por que é importante revisar metas periodicamente?', '[\"Para ajustar ao cenário atual\", \"Para esquecer dívidas\", \"Para gastar mais\", \"Para evitar investimentos\"]', 'Para ajustar ao cenário atual'),
(361, 2, 2, 'O que significa priorizar metas?', '[\"Definir ordem de importância\", \"Ignorar metas menores\", \"Focar só em dívidas\", \"Evitar planejamento\"]', 'Definir ordem de importância'),
(362, 2, 2, 'Qual é o impacto de metas mal definidas?', '[\"Desmotivação e desorganização\", \"Aumento de salário\", \"Redução de impostos\", \"Ganhos inesperados\"]', 'Desmotivação e desorganização'),
(363, 2, 2, 'O que é uma meta mensurável?', '[\"Pode ser acompanhada por números\", \"É subjetiva\", \"Não tem prazo\", \"É emocional\"]', 'Pode ser acompanhada por números'),
(364, 2, 2, 'Qual é o papel do planejamento financeiro?', '[\"Organizar metas e recursos\", \"Evitar metas\", \"Ignorar orçamento\", \"Aumentar dívidas\"]', 'Organizar metas e recursos'),
(365, 2, 3, 'Como metas financeiras ajudam na tomada de decisão?', '[\"Guiam escolhas com base em objetivos\", \"Eliminam a necessidade de pensar\", \"Aumentam gastos\", \"Reduzem salário\"]', 'Guiam escolhas com base em objetivos'),
(366, 2, 3, 'Qual é um exemplo de meta de médio prazo?', '[\"Fazer uma viagem internacional\", \"Comprar um café\", \"Pagar o aluguel\", \"Recarregar celular\"]', 'Fazer uma viagem internacional'),
(367, 2, 3, 'O que é uma meta relevante?', '[\"Importante para seus valores e objetivos\", \"Popular entre amigos\", \"Baseada em modismos\", \"Sem impacto pessoal\"]', 'Importante para seus valores e objetivos'),
(368, 2, 3, 'Como metas financeiras influenciam o comportamento?', '[\"Promovem disciplina e foco\", \"Causam ansiedade\", \"Geram impulsividade\", \"Eliminam controle\"]', 'Promovem disciplina e foco'),
(369, 2, 3, 'Qual é o risco de não ter metas?', '[\"Desorganização financeira\", \"Aumento de patrimônio\", \"Redução de dívidas\", \"Ganhos inesperados\"]', 'Desorganização financeira'),
(370, 2, 4, 'Como metas ajudam a evitar dívidas?', '[\"Direcionam o uso consciente do dinheiro\", \"Aumentam limite do cartão\", \"Eliminam boletos\", \"Ignoram gastos\"]', 'Direcionam o uso consciente do dinheiro'),
(371, 2, 4, 'Qual é o papel do prazo nas metas?', '[\"Define tempo para alcançar\", \"Elimina metas\", \"Ignora planejamento\", \"Aumenta dívidas\"]', 'Define tempo para alcançar'),
(372, 2, 4, 'O que é uma meta alcançável?', '[\"Realista dentro da sua realidade\", \"Baseada em sonhos impossíveis\", \"Sem critérios\", \"Muito distante\"]', 'Realista dentro da sua realidade'),
(373, 2, 4, 'Como metas financeiras afetam o orçamento?', '[\"Organizam prioridades de gastos\", \"Aumentam despesas\", \"Ignoram receitas\", \"Eliminam controle\"]', 'Organizam prioridades de gastos'),
(374, 2, 4, 'Qual é o impacto de metas bem definidas?', '[\"Maior controle e motivação\", \"Desorganização\", \"Aumento de dívidas\", \"Redução de salário\"]', 'Maior controle e motivação'),
(375, 2, 5, 'Como metas financeiras se relacionam com investimentos?', '[\"Definem objetivos para aplicar recursos\", \"Eliminam a necessidade de investir\", \"Ignoram riscos\", \"Aumentam dívidas\"]', 'Definem objetivos para aplicar recursos'),
(376, 2, 5, 'O que é uma meta temporal?', '[\"Tem prazo definido\", \"É emocional\", \"Não tem data\", \"É subjetiva\"]', 'Tem prazo definido'),
(377, 2, 5, 'Como metas ajudam na disciplina financeira?', '[\"Criam foco e constância\", \"Eliminam controle\", \"Ignoram orçamento\", \"Aumentam impulsividade\"]', 'Criam foco e constância'),
(378, 2, 5, 'Qual é o papel das metas na construção de patrimônio?', '[\"Guiam decisões de poupança e investimento\", \"Eliminam ativos\", \"Ignoram renda\", \"Reduzem ganhos\"]', 'Guiam decisões de poupança e investimento'),
(379, 2, 5, 'Como metas financeiras afetam a motivação?', '[\"Aumentam o engajamento com objetivos\", \"Causam desânimo\", \"Ignoram conquistas\", \"Eliminam foco\"]', 'Aumentam o engajamento com objetivos'),
(380, 3, 1, 'O que é uma reserva de emergência?', '[\"Dinheiro guardado para imprevistos\", \"Investimento em ações\", \"Gasto com lazer\", \"Compra parcelada\"]', 'Dinheiro guardado para imprevistos'),
(381, 3, 1, 'Qual é o valor ideal de uma reserva de emergência?', '[\"De 3 a 6 meses de despesas\", \"1 mês de salário\", \"Valor do aluguel\", \"Metade do cartão de crédito\"]', 'De 3 a 6 meses de despesas'),
(382, 3, 2, 'Onde guardar a reserva de emergência?', '[\"Em aplicações seguras e líquidas\", \"Em ações voláteis\", \"Em criptomoedas\", \"Em bens físicos\"]', 'Em aplicações seguras e líquidas'),
(383, 3, 2, 'Qual é o objetivo da reserva de emergência?', '[\"Cobrir gastos inesperados\", \"Comprar carro novo\", \"Investir em imóveis\", \"Pagar festas\"]', 'Cobrir gastos inesperados'),
(384, 3, 3, 'Quando usar a reserva de emergência?', '[\"Em caso de desemprego ou urgência\", \"Para pagar compras parceladas\", \"Para investir em ações\", \"Para lazer\"]', 'Em caso de desemprego ou urgência'),
(385, 3, 3, 'Qual é a principal característica da reserva?', '[\"Alta liquidez\", \"Alto risco\", \"Rentabilidade agressiva\", \"Baixa segurança\"]', 'Alta liquidez'),
(386, 3, 4, 'Por que não investir a reserva em renda variável?', '[\"Porque há risco de perda\", \"Porque rende mais\", \"Porque é moda\", \"Porque é obrigatório\"]', 'Porque há risco de perda'),
(387, 3, 4, 'Como calcular o valor da reserva?', '[\"Multiplicando despesas mensais por 6\", \"Somando salário com bônus\", \"Calculando o limite do cartão\", \"Dividindo o aluguel por 2\"]', 'Multiplicando despesas mensais por 6'),
(388, 3, 5, 'O que fazer após usar a reserva?', '[\"Repor o valor o quanto antes\", \"Ignorar o gasto\", \"Investir em ações\", \"Pedir empréstimo\"]', 'Repor o valor o quanto antes'),
(389, 3, 5, 'Qual é o erro comum ao montar a reserva?', '[\"Misturar com dinheiro de lazer\", \"Guardar em conta corrente\", \"Investir em previdência\", \"Usar para pagar dívidas antigas\"]', 'Misturar com dinheiro de lazer'),
(390, 8, 5, 'O que é planejamento financeiro pessoal?', '[\"Processo de ajudar o cliente a atingir objetivos de vida\", \"Cálculo de impostos\", \"Gestão de fundos\", \"Auditoria fiscal\"]', 'Processo de ajudar o cliente a atingir objetivos de vida'),
(391, 8, 5, 'O que é planejamento de aposentadoria?', '[\"Estratégia para garantir renda futura\", \"Compra de ações\", \"Gestão de fundos\", \"Auditoria fiscal\"]', 'Estratégia para garantir renda futura'),
(392, 8, 5, 'O que é planejamento tributário?', '[\"Estratégia para reduzir legalmente a carga de impostos\", \"Evitar pagamento de tributos\", \"Auditoria fiscal\", \"Gestão de fundos\"]', 'Estratégia para reduzir legalmente a carga de impostos'),
(393, 8, 5, 'O que é planejamento sucessório?', '[\"Organização da transferência de patrimônio\", \"Planejamento de aposentadoria\", \"Gestão de fundos\", \"Controle de gastos\"]', 'Organização da transferência de patrimônio'),
(394, 8, 5, 'O que é perfil de investidor?', '[\"Conjunto de características que definem tolerância a risco\", \"Valor investido\", \"Rentabilidade líquida\", \"Taxa de administração\"]', 'Conjunto de características que definem tolerância a risco'),
(395, 8, 5, 'O que é suitability?', '[\"Adequação do produto ao perfil do investidor\", \"Rentabilidade líquida\", \"Taxa de administração\", \"Prazo de vencimento\"]', 'Adequação do produto ao perfil do investidor'),
(396, 8, 5, 'O que é fundo de previdência?', '[\"Fundo voltado para aposentadoria\", \"Fundo de ações\", \"Fundo cambial\", \"Fundo de crédito privado\"]', 'Fundo voltado para aposentadoria'),
(397, 8, 5, 'O que é fundo de ações?', '[\"Fundo que investe majoritariamente em ações\", \"Fundo de renda fixa\", \"Fundo cambial\", \"Fundo de previdência\"]', 'Fundo que investe majoritariamente em ações'),
(398, 8, 5, 'O que é fundo multimercado?', '[\"Fundo que investe em diferentes classes de ativos\", \"Fundo de ações\", \"Fundo cambial\", \"Fundo de previdência\"]', 'Fundo que investe em diferentes classes de ativos'),
(399, 8, 5, 'O que é fundo de renda fixa?', '[\"Fundo que investe em títulos públicos e privados\", \"Fundo de ações\", \"Fundo cambial\", \"Fundo de previdência\"]', 'Fundo que investe em títulos públicos e privados'),
(400, 8, 5, 'O que é fundo cambial?', '[\"Fundo que investe em ativos atrelados à moeda estrangeira\", \"Fundo de ações\", \"Fundo de renda fixa\", \"Fundo de previdência\"]', 'Fundo que investe em ativos atrelados à moeda estrangeira'),
(401, 8, 5, 'O que é fundo exclusivo?', '[\"Fundo destinado a um único cotista\", \"Fundo de ações\", \"Fundo cambial\", \"Fundo de previdência\"]', 'Fundo destinado a um único cotista'),
(402, 8, 5, 'O que é alocação de ativos?', '[\"Distribuição dos investimentos entre diferentes classes\", \"Compra por impulso\", \"Venda sem análise\", \"Investimento passivo\"]', 'Distribuição dos investimentos entre diferentes classes');

-- --------------------------------------------------------

--
-- Estrutura para tabela `transacoes`
--

CREATE TABLE `transacoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` enum('receita','despesa','investimento') NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `transacoes`
--

INSERT INTO `transacoes` (`id`, `usuario_id`, `tipo`, `categoria`, `valor`, `descricao`, `data`) VALUES
(1, 3, 'receita', 'salario', 7000.00, 'Salário', '2025-10-01 00:00:00'),
(3, 3, 'despesa', 'moradia', 1500.00, 'Aluguel', '2025-10-15 00:00:00'),
(4, 3, 'despesa', 'contas_domesticas', 180.00, 'Energia elétrica', '2025-10-05 00:00:00'),
(5, 3, 'investimento', 'reserva_emergencia', 2000.00, 'CDB Inter', '2025-10-01 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `trilha_conhecimento`
--

CREATE TABLE `trilha_conhecimento` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `total_fases` int(11) DEFAULT 5,
  `trilha_paga` tinyint(1) DEFAULT 0,
  `ordem` int(11) DEFAULT 0,
  `conteudo` text DEFAULT NULL,
  `nivel` enum('iniciante','intermediario','avancado') DEFAULT 'iniciante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `trilha_conhecimento`
--

INSERT INTO `trilha_conhecimento` (`id`, `titulo`, `descricao`, `total_fases`, `trilha_paga`, `ordem`, `conteudo`, `nivel`) VALUES
(1, 'Fundamentos das Finanças Pessoais', NULL, 5, 0, 1, 'Aprenda os pilares do controle financeiro e como evitar dívidas crônicas.', 'iniciante'),
(2, 'Metas e Planejamento Financeiro', NULL, 5, 0, 2, 'Defina objetivos SMART e crie planos para realizá-los.', 'iniciante'),
(3, 'Reserva de Emergência', NULL, 5, 0, 3, 'Construa sua rede de segurança financeira.', 'iniciante'),
(4, 'Consumo Consciente', NULL, 5, 0, 4, 'Tome decisões de compra mais inteligentes.', 'iniciante'),
(5, 'Renda Fixa', NULL, 5, 0, 5, 'Explore os tipos de investimentos seguros no Brasil e no mundo.', 'intermediario'),
(6, 'Renda Variável', NULL, 5, 0, 6, 'Entenda ações, ETFs, FIIs e como investir com estratégia.', 'intermediario'),
(7, 'Traderismo', NULL, 5, 0, 7, 'Descubra o mundo dos traders e seus riscos.', 'avancado'),
(8, 'Certificações Profissionais', NULL, 5, 0, 8, 'Prepare-se para CPA20, CEA, CNPI e CFA.', 'avancado');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `tipo_usuario` enum('aluno','admin','financeiro') DEFAULT 'aluno',
  `criado_em` datetime DEFAULT current_timestamp(),
  `google_id` varchar(255) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `assinaturas`
--
ALTER TABLE `assinaturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `conteudo_fases`
--
ALTER TABLE `conteudo_fases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_conteudo_trilha` (`trilha_id`);

--
-- Índices de tabela `progresso_aprendizado`
--
ALTER TABLE `progresso_aprendizado`
  ADD PRIMARY KEY (`usuario_id`,`trilha_id`),
  ADD KEY `fk_progresso_trilha` (`trilha_id`);

--
-- Índices de tabela `quiz_fases`
--
ALTER TABLE `quiz_fases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_quiz_trilha` (`trilha_id`);

--
-- Índices de tabela `transacoes`
--
ALTER TABLE `transacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_transacoes_usuario` (`usuario_id`);

--
-- Índices de tabela `trilha_conhecimento`
--
ALTER TABLE `trilha_conhecimento`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD UNIQUE KEY `email_3` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `assinaturas`
--
ALTER TABLE `assinaturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `conteudo_fases`
--
ALTER TABLE `conteudo_fases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `quiz_fases`
--
ALTER TABLE `quiz_fases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=403;

--
-- AUTO_INCREMENT de tabela `transacoes`
--
ALTER TABLE `transacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `trilha_conhecimento`
--
ALTER TABLE `trilha_conhecimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `assinaturas`
--
ALTER TABLE `assinaturas`
  ADD CONSTRAINT `assinaturas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `conteudo_fases`
--
ALTER TABLE `conteudo_fases`
  ADD CONSTRAINT `conteudo_fases_ibfk_1` FOREIGN KEY (`trilha_id`) REFERENCES `trilha_conhecimento` (`id`),
  ADD CONSTRAINT `conteudo_fases_ibfk_2` FOREIGN KEY (`trilha_id`) REFERENCES `trilha_conhecimento` (`id`),
  ADD CONSTRAINT `fk_conteudo_trilha` FOREIGN KEY (`trilha_id`) REFERENCES `trilha_conhecimento` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `progresso_aprendizado`
--
ALTER TABLE `progresso_aprendizado`
  ADD CONSTRAINT `fk_progresso_trilha` FOREIGN KEY (`trilha_id`) REFERENCES `trilha_conhecimento` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_progresso_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `progresso_aprendizado_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `progresso_aprendizado_ibfk_2` FOREIGN KEY (`trilha_id`) REFERENCES `trilha_conhecimento` (`id`);

--
-- Restrições para tabelas `quiz_fases`
--
ALTER TABLE `quiz_fases`
  ADD CONSTRAINT `fk_quiz_trilha` FOREIGN KEY (`trilha_id`) REFERENCES `trilha_conhecimento` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_fases_ibfk_1` FOREIGN KEY (`trilha_id`) REFERENCES `trilha_conhecimento` (`id`),
  ADD CONSTRAINT `quiz_fases_ibfk_2` FOREIGN KEY (`trilha_id`) REFERENCES `trilha_conhecimento` (`id`);

--
-- Restrições para tabelas `transacoes`
--
ALTER TABLE `transacoes`
  ADD CONSTRAINT `fk_transacoes_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transacoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
