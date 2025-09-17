<?php

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contas De Free Fire</title>
  <link rel="stylesheet" href="../css/freefire.css">
</head>
<body>

  <header class="topo">
    <h1>Contas de Free Fire </h1>
    <input type="search" placeholder="Buscar conta, jogador, CV..." />
    <button class="btn-anunciar">Anunciar</button>
  </header>

  <main class="conteudo">
    <aside class="filtros">
      <h3>Categorias</h3>
      <ul>
        <li><a href="#">Ver todas</a></li>
        <li><a href="#">Full Upadas</a></li>
      </ul>

      <h3>Filtros</h3>
      <label>Preço mínimo:</label>
      <input type="number" placeholder="R$ 0,00" />

      <label>Preço máximo:</label>
      <input type="number" placeholder="R$ 999,99" />

      <button class="btn-aplicar">Aplicar Filtro</button>
    </aside>

    <section class="produtos">
  <div class="produto-card">
  <img src="../img/clash.jpeg" alt="Descrição" class="imagem-clash">
    <h4>[PROMOÇÃO] Conta com skins raras</h4>
    <a href="detalhes.php" class="btn-detalhes">Ver Detalhes</a>
  </div>
</section>

      <div class="produto-card">
        <img src="img/coc2.jpg" alt="Conta COC 2">
        <h4>Clash of Clans CV15 full upado</h4>
        <p class="preco">R$ 349,99+</p>
       
      </div>

      <div class="produto-card">
        <img src="img/coc3.jpg" alt="Conta COC 3">
        <h4>Conta rara com skins exclusivas</h4>
        <p class="preco">R$ 11,19+</p>
      
      </div>

      <!-- Adicione mais cards conforme necessário -->
    </section>
  </main>

</body>
</html>
