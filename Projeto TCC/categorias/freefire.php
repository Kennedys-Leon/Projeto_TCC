<?php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MaxAcess</title>
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
    <h1>Comprar e Vender Contas De Clash Royale</h1>
    <div class="search">
      <input type="text" placeholder="Buscar produto..." class="search-input" />
      <button class="search-btn">Buscar</button>
    </div>
  </section>

  <section class="content">
    <aside class="sidebar">
      <h3>Categorias</h3>
      <ul>
        <li><a href="#">Contas Free Fire</a></li>
        <li><a href="#">Diamantes Free Fire</a></li>
        <li><a href="#">Outros</a></li>
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
  <img src="../jogos/free.jpg" alt="PASSO BOOYAH PREMIUM" />
  <h4>PASSO BOOYAH PREMIUM</h4>
  <span>R$ 4,97</span>
  <a href="detalhes.php?id=" class="buy-btn">Ver Detalhes</a>
</div>


        <div class="product-card">
        <img src="../jogos/free15.png" alt="PASSO BOOYAH PREMIUM" />
        <h4>Conta Free Fire LVL 38</h4>
          <p>Já está mestre</p>
          <span>R$ 2,99</span>
  <a href="detalhes.php?id=" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/mestre.png" alt="Gift Card Diamantes" />
          <h4>Gift Card Diamantes</h4>
          <p>Entrega automática</p>
          <span>R$ 5,19</span>
          <button class="buy-btn">Ver Detalhes</button>
        </div>
        
        <div class="product-card">
          <img src="../jogos/bandeirao.webp" alt="Gift Card Diamantes" />
          <h4>Gift Card Diamantes</h4>
          <p>Entrega automática</p>
          <span>R$ 5,19</span>
          <button class="buy-btn">Ver Detalhes</button>
        </div>
        
          <div class="product-card">
          <img src="../jogos/gemada.png" alt="Gift Card Diamantes" />
          <h4>Gift Card Diamantes</h4>
          <p>Entrega automática</p>
          <span>R$ 5,19</span>
          <button class="buy-btn">Ver Detalhes</button>
        </div>

        <div class="product-card">
          <img src="../jogos/angelical.png" alt="Gift Card Diamantes" />
          <h4>Gift Card Diamantes</h4>
          <p>Entrega automática</p>
          <span>R$ 5,19</span>
          <button class="buy-btn">Ver Detalhes</button>
        </div>

          <div class="product-card">
          <img src="../jogos/upada.png" alt="Gift Card Diamantes" />
          <h4>Gift Card Diamantes</h4>
          <p>Entrega automática</p>
          <span>R$ 5,19</span>
          <button class="buy-btn">Ver Detalhes</button>
        </div>

         <div class="product-card">
          <img src="../jogos/naruto.png" alt="Gift Card Diamantes" />
          <h4>Gift Card Diamantes</h4>
          <p>Entrega automática</p>
          <span>R$ 5,19</span>
          <button class="buy-btn">Ver Detalhes</button>
        </div>

          <div class="product-card">
          <img src="../jogos/gemada.png" alt="Gift Card Diamantes" />
          <h4>Gift Card Diamantes</h4>
          <p>Entrega automática</p>
          <span>R$ 5,19</span>
          <button class="buy-btn">Ver Detalhes</button>

        </div> 
         <div class="product-card">
          <img src="../jogos/gemada.png" alt="Gift Card Diamantes" />
          <h4>Gift Card Diamantes</h4>
          <p>Entrega automática</p>
          <span>R$ 5,19</span>
          <button class="buy-btn">Ver Detalhes</button>
        </div>

        <!-- Outros produtos podem ser adicionados aqui -->
      </div>
    </div>
  </section>

  <footer>
  </footer>
</body>
</html>
