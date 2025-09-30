<?php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MaxAcess - Comprar e Vender Contas e Itens de Roblox</title>
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
    <h1>Comprar e Vender Contas e Itens de Roblox</h1>
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
        <li><a href="#">Skins e Itens Exclusivos</a></li>
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
          <img src="../jogos/brainroot.png" alt="Conta Roblox com 100K Robux" />
          <h4>Conta Roblox com 100K Robux</h4>
          <span>R$ 500,00</span>
          <a href="detalhes.php?id=1" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 2 -->
        <div class="product-card">
          <img src="../jogos/blox.png" alt="Conta Roblox com 50K Robux" />
          <h4>Conta Roblox com 50K Robux</h4>
          <p>Com várias skins raras</p>
          <span>R$ 250,00</span>
          <a href="detalhes.php?id=2" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 3 -->
        <div class="product-card">
          <img src="../jogos/roblox.png" alt="1000 Robux" />
          <h4>1000 Robux</h4>
          <span>R$ 30,00</span>
          <a href="detalhes.php?id=3" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 4 -->
        <div class="product-card">
          <img src="../jogos/fruit.png" alt="Skin Exclusiva Roblox" />
          <h4>Skin Exclusiva para Roblox</h4>
          <p>Skin rara e limitada</p>
          <span>R$ 40,00</span>
          <a href="detalhes.php?id=4" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 5 -->
        <div class="product-card">
          <img src="../jogos/lendario.png" alt="Skin Lendária Roblox" />
          <h4>Skin Lendária para Roblox</h4>
          <p>Exclusiva e colecionável</p>
          <span>R$ 80,00</span>
          <a href="detalhes.php?id=5" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 6 -->
        <div class="product-card">
          <img src="../jogos/blox1.png" alt="Conta Roblox com 10K Robux" />
          <h4>Conta Roblox com 10K Robux</h4>
          <span>R$ 150,00</span>
          <a href="detalhes.php?id=6" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 7 -->
        <div class="product-card">
          <img src="../jogos/acessorio.png" alt="Skin Amber Roblox" />
          <h4>Skin Amber - Roblox</h4>
          <p>Skin exclusiva para sua conta</p>
          <span>R$ 25,00</span>
          <a href="detalhes.php?id=7" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 8 -->
        <div class="product-card">
          <img src="../jogos/premium.png" alt="Conta Roblox Premium" />
          <h4>Conta Roblox Premium</h4>
          <p>Assinatura Premium com benefícios exclusivos</p>
          <span>R$ 120,00</span>
          <a href="detalhes.php?id=8" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 9 -->
        <div class="product-card">
          <img src="../jogos/lendarios.png" alt="Skin Lendária Roblox" />
          <h4>Skin Lendária para Roblox</h4>
          <span>R$ 50,00</span>
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
