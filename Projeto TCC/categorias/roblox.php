<?php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MaxAcess - Roblox</title>
  <link rel="stylesheet" href="../css/categoria.css" />
</head>
<body>
  <header>
    <nav>
      <ul>
        <li><a href="../index.php">Início</a></li>
        <li><a href="#">Categorias</a></li>
        <li><a href="#">Blog</a></li>
        <li><a href="#">Anunciar</a></li>
      </ul>
    </nav>
  </header>

  <section class="banner">
    <h1>Comprar e Vender Itens de Roblox</h1>
    <div class="search">
      <input type="text" placeholder="Buscar produto..." class="search-input" />
      <button class="search-btn">Buscar</button>
    </div>
  </section>

  <section class="content">
    <aside class="sidebar">
      <h3>Categorias</h3>
      <ul>
        <li><a href="#">Contas Roblox</a></li>
        <li><a href="#">Robux</a></li>
        <li><a href="#">Itens Especiais</a></li>
      </ul>

      <h3>Filtros</h3>
      <form>
        <label for="min-price">Preço mínimo:</label>
        <input type="number" id="min-price" name="min-price" placeholder="R$" />

        <label for="max-price">Preço máximo:</label>
        <input type="number" id="max-price" name="max-price" placeholder="R$" />

        <button type="submit" class="apply-filter-btn">Aplicar filtro</button>
      </form>
    </aside>

    <div class="content-area">
      <div class="product-list">
        <div class="product-card">
          <img src="../jogos/robux.png" alt="Robux" />
          <h4>1000 Robux</h4>
          <span>R$ 29,99</span>
          <a href="detalhes.php?id=1" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/conta-roblox.png" alt="Conta Roblox" />
          <h4>Conta Roblox Premium</h4>
          <p>Com benefícios exclusivos</p>
          <span>R$ 99,99</span>
          <a href="detalhes.php?id=2" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/itens-especiais.png" alt="Itens Especiais" />
          <h4>Itens Especiais Roblox</h4>
          <p>Inclui skins raras e acessórios</p>
          <span>R$ 69,99</span>
          <a href="detalhes.php?id=3" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/robux-promo.png" alt="Robux Promo" />
          <h4>Promoção Robux</h4>
          <p>Ganhe bônus com a compra de Robux</p>
          <span>R$ 49,99</span>
          <a href="detalhes.php?id=4" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/avatar-roblox.png" alt="Avatar Roblox" />
          <h4>Avatar Roblox Exclusivo</h4>
          <p>Crie seu avatar único</p>
          <span>R$ 79,99</span>
          <a href="detalhes.php?id=5" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/robux.png" alt="Robux" />
          <h4>5000 Robux</h4>
          <span>R$ 149,99</span>
          <a href="detalhes.php?id=6" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Mais produtos podem ser adicionados aqui -->
      </div>
    </div>
  </section>

  <footer>
  </footer>
</body>
</html>
