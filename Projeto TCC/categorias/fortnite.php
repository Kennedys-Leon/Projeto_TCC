<?php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MaxAcess - Comprar e Vender Contas e Itens de Fortnite</title>
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
    <h1>Comprar e Vender Contas e Itens de Fortnite</h1>
    <div class="search">
      <input type="text" placeholder="Buscar produto..." class="search-input" />
      <button class="search-btn">Buscar</button>
    </div>
  </section>

  <section class="content">
    <aside class="sidebar">
      <h3>Categorias</h3>
      <ul>
        <li><a href="#">Contas Fortnite</a></li>
        <li><a href="#">V-Bucks</a></li>
        <li><a href="#">Skins Raras</a></li>
        <li><a href="#">Pacotes e Battle Pass</a></li>
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
        <!-- Produto 1 -->
        <div class="product-card">
          <img src="../jogos/fortnite.png" alt="Conta Fortnite com 10K V-Bucks" />
          <h4>Conta Fortnite com 10K V-Bucks</h4>
          <span>R$ 400,00</span>
          <a href="detalhes.php?id=1" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 2 -->
        <div class="product-card">
          <img src="../jogos/skin.png" alt="Skin Ghoul Trooper" />
          <h4>Skin Ghoul Trooper</h4>
          <p>Uma das skins mais raras do Fortnite</p>
          <span>R$ 300,00</span>
          <a href="detalhes.php?id=2" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 3 -->
        <div class="product-card">
          <img src="../jogos/bucks.png" alt="2800 V-Bucks" />
          <h4>2800 V-Bucks</h4>
          <span>R$ 80,00</span>
          <a href="detalhes.php?id=3" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 4 -->
        <div class="product-card">
          <img src="../jogos/passes.png" alt="Battle Pass Atual" />
          <h4>Battle Pass Temporada Atual</h4>
          <p>Garanta todas as recompensas exclusivas</p>
          <span>R$ 40,00</span>
          <a href="detalhes.php?id=4" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 5 -->
        <div class="product-card">
          <img src="../jogos/renegade.png" alt="Skin Renegade Raider" />
          <h4>Skin Renegade Raider</h4>
          <p>Uma das skins lendárias mais desejadas</p>
          <span>R$ 600,00</span>
          <a href="detalhes.php?id=5" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 6 -->
        <div class="product-card">
          <img src="../jogos/rara.png" alt="Conta Fortnite com Skins Exclusivas" />
          <h4>Conta Fortnite com Skins Exclusivas</h4>
          <span>R$ 250,00</span>
          <a href="detalhes.php?id=6" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 7 -->
        <div class="product-card">
          <img src="../jogos/knight.png" alt="Skin Black Knight" />
          <h4>Skin Black Knight</h4>
          <p>Do Battle Pass da Temporada 2</p>
          <span>R$ 500,00</span>
          <a href="detalhes.php?id=7" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 8 -->
        <div class="product-card">
          <img src="../jogos/pacote.png" alt="Pacote de Itens Exclusivos" />
          <h4>Pacote de Itens Exclusivos</h4>
          <p>Inclui mochilas, picaretas e danças raras</p>
          <span>R$ 120,00</span>
          <a href="detalhes.php?id=8" class="buy-btn">Ver Detalhes</a>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 GGMax. Todos os direitos reservados.</p>
  </footer>
</body>
</html>
