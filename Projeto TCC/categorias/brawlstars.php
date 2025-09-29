<?php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MaxAcess - Comprar e Vender Brawl Stars</title>
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
    <h1>Comprar e Vender Contas e Itens de Brawl Stars</h1>
    <div class="search">
      <input type="text" placeholder="Buscar produto..." class="search-input" />
      <button class="search-btn">Buscar</button>
    </div>
  </section>

  <section class="content">
    <aside class="sidebar">
      <h3>Categorias</h3>
      <ul>
        <li><a href="#">Contas Brawl Stars</a></li>
        <li><a href="#">Gemas Brawl Stars</a></li>
        <li><a href="#">Skins e Brawlers</a></li>
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
          <img src="../jogos/brawl.png" alt="Passaporte Brawl Stars" />
          <h4>Conta De Brawl Stars De 63 mil trofeus</h4>
          <span>R$ 150,97</span>
          <a href="detalhes.php?id=1" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/rian.png" alt="Conta Brawl Stars LVL 30" />
          <h4>Conta De Brawl Stars de 34 mil trofeus</h4>
          <p>Com 20 Brawlers No Mil</p>
          <span>R$ 50,00</span>
          <a href="detalhes.php?id=2" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/gemas_brawl.png" alt="Gemas Brawl Stars" />
          <h4>500 Gemas Brawl Stars</h4>
          <span>R$ 24,99</span>
          <a href="detalhes.php?id=3" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/skin_brawler.png" alt="Skin Exclusiva Brawler" />
          <h4>Skin Exclusiva Brawler</h4>
          <p>Para o Brawler Shelly</p>
          <span>R$ 14,99</span>
          <a href="detalhes.php?id=4" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/skin_legendary.png" alt="Skin Lendária Brawl Stars" />
          <h4>Skin Lendária Brawler</h4>
          <p>Exclusiva para o Brawler Leon</p>
          <span>R$ 29,99</span>
          <a href="detalhes.php?id=5" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/gemas_brawl_1000.png" alt="1000 Gemas Brawl Stars" />
          <h4>1000 Gemas Brawl Stars</h4>
          <span>R$ 49,99</span>
          <a href="detalhes.php?id=6" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/brawler_sandy.png" alt="Brawler Sandy" />
          <h4>Brawler Sandy</h4>
          <p>Brawler Lendário</p>
          <span>R$ 19,99</span>
          <a href="detalhes.php?id=7" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/skin_amber.png" alt="Skin Amber Brawl Stars" />
          <h4>Skin Amber - Brawl Stars</h4>
          <p>Completamente exclusiva</p>
          <span>R$ 22,99</span>
          <a href="detalhes.php?id=8" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/skins_legendary.png" alt="Skins Lendárias" />
          <h4>Skins Lendárias</h4>
          <p>Escolha a sua favorita</p>
          <span>R$ 34,99</span>
          <a href="detalhes.php?id=9" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Outros produtos podem ser adicionados aqui -->
      </div>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 GGMax. Todos os direitos reservados.</p>
  </footer>
</body>
</html>
