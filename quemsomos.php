<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioLogado = isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'];
$nomeUsuario = $usuarioLogado ? $_SESSION['nome_usuario'] : 'Visitante';
$paginaAtual = basename($_SERVER['SCRIPT_NAME'], '.php');
$tipoUsuario = $_SESSION['tipo_usuario'] ?? null;
?>
<header class="header-green">
    <div class="container header-content">
      <div class="logo">
        <img src="img/FC4.png" alt="logo">
      </div>
      <div class="user-actions">
        <a href="index.php" class="btn btn-outline">Voltar</a>
      </div>
    </div>
</header>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Quem Somos - Finance Control</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Nunito', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f6f8;
    }
  </style>
</head>
<body>



<div class="quem-somos-container">
  <div class="arrow arrow-left" onclick="prevSlide()"><i class="fas fa-chevron-left"></i></div>
  
  <div class="slide-content">
    <div class="slide" id="slide">
      <!-- Conteúdo será inserido via JavaScript -->
    </div>
  </div>
  
  <div class="arrow arrow-right" onclick="nextSlide()"><i class="fas fa-chevron-right"></i></div>
</div>

<script>
  const integrantes = [
    {
      nome: "Jonathan Jose",
      imagem: "img/Jonathan.png",
      bio: "Profissional da área da saúde, atua como Terapeuta Ocupacional com especialização na reabilitação neurológica e ortopédica. Atualmente, cursa o último semestre de Análise e Desenvolvimento de Sistemas na Etec, Com uma formação multidisciplinar, busca ampliar seus conhecimentos e integrar a tecnologia ao cotidiano terapêutico afim de desenvolver ferramentas que apoiem profissionais da saúde e ampliem as possibilidades de cuidado, especialmente em ambientes hospitalares, clínicas e atendimentos domiciliares."
    },
    {
      nome: "Danielly Soares Vieira",
      imagem: "img/Danielly.jpg",
      bio: "Integrante do Time de Desenvolvimento, contribuindo diretamente na construção e implementação das funcionalidades do sistema. Ingressou na área de TI através de uma oportunidade de trabalho por meio de um familiar e atualmente cursa o último semestre de Análise e Desenvolvimento de Sistemas, na Etec. Busca expandir seus conhecimentos na área, para desenvolvimento de plataformas que possam ajudar pessoas e contribuir para uma sociedade melhor. Apaixonada pela vida e pelo Autor dela, livros, música e sapatos haha"
    },
    {
      nome: "Leticia Mara Bandeira",
      imagem: "img/Leticia.jpg",
      bio: "Integrante do Time de Desenvolvimento, contribuindo com análises, implementação e testes das soluções propostas. Graduada em biotecnologia e quase formada em Desenvolvimento de Sistemas na ETEC. Apaixonada pela natureza, fotografia, aprender novos idiomas e culturas. E como divertimento, tocar piano. "
    },
    {
      nome: "Kaique Rampazzo Bini",
      imagem: "img/Kaique.jpg",
      bio: "Profissional da area da saúde, Formado em Tecnólogo em Jogos Digitais, atualmente cursando Análise e Desenvolvimento de Sistemas na ETEC. Buscando conhecimentos para desenvolver melhorias em sistemas hospitalares e melhorar o atendimento de maneira geral. Um apaixonado por jogos, tecnologia e música, em especial instrumentos como guitarra, viola caipira e o violão.",        
    },
    {
      nome: "Elvis Presley Ramos Miranda da Conceição",
      imagem: "img/Elvys.jpg",
      bio: "Exerce a função de Scrum Master, sendo responsável por facilitar os processos, remover impedimentos e garantir que a equipe siga os princípios do Scrum. Graduado em filosofia, matemática e graduando em ciências de dados, curte literatura relacionada a ficção científica, cultura oriental e viajar. Pratica MMA e xadrez.",        
    }
  ];

  let currentIndex = 0;

  function updateSlide() {
    const slide = document.getElementById("slide");
    slide.innerHTML = `
      <div class="member-image">
        <img src="${integrantes[currentIndex].imagem}" alt="${integrantes[currentIndex].nome}" class="fade-in">
      </div>
      <div class="bio fade-in-delayed">
        <h2>${integrantes[currentIndex].nome}</h2>
        <p>${integrantes[currentIndex].bio}</p>
      </div>
    `;
  }

  function nextSlide() {
    currentIndex = (currentIndex + 1) % integrantes.length;
    updateSlide();
  }

  function prevSlide() {
    currentIndex = (currentIndex - 1 + integrantes.length) % integrantes.length;
    updateSlide();
  }

  document.addEventListener("DOMContentLoaded", updateSlide);
</script>

</body>
</html>