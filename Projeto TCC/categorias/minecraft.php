<?php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MaxAcess - Comprar e Vender Contas e Itens de Minecraft</title>
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
    <h1>Comprar e Vender Contas e Itens de Minecraft</h1>
    <div class="search">
    <input type="text" id="search-input" placeholder="Buscar produto..." class="search-input" />
  <button class="search-btn" onclick="buscarProduto()">Buscar</button>
</div>
  </section>

  <section class="content">
    <aside class="sidebar">
      <h3>Categorias</h3>
      <ul>
        <li><a href="#">Contas Premium</a></li>
        <li><a href="#">Minecraft Coins</a></li>
        <li><a href="#">Capas e Skins</a></li>
        <li><a href="#">Servidores & Realms</a></li>
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
          <img src="../jogos/full.png" alt="Conta Minecraft Premium" />
          <h4>Conta Minecraft Premium Original</h4>
          <span>R$ 90,00</span>
          <a href="detalhes.php?id=1" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 2 -->
        <div class="product-card">
          <img src="../jogos/acesso.png" alt="Minecoins 1720" />
          <h4>1720 Minecoins</h4>
          <p>Para comprar no marketplace</p>
          <span>R$ 50,00</span>
          <a href="detalhes.php?id=2" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 3 -->
        <div class="product-card">
          <img src="../jogos/capa.png" alt="Capa Exclusiva" />
          <h4>Capa Exclusiva (Minecon)</h4>
          <p>Item de colecionador raro</p>
          <span>R$ 200,00</span>
          <a href="detalhes.php?id=3" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 4 -->
        <div class="product-card">
          <img src="../jogos/mineccon.png" alt="Pacote de Skins" />
          <h4>Pacote de Skins Personalizadas</h4>
          <span>R$ 30,00</span>
          <a href="detalhes.php?id=4" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 5 -->
        <div class="product-card">
          <img src="../jogos/realm.png" alt="Minecraft Realm" />
          <h4>Minecraft Realm - 3 Meses</h4>
          <p>Jogue com seus amigos em servidor privado</p>
          <span>R$ 80,00</span>
          <a href="detalhes.php?id=5" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 6 -->
        <div class="product-card">
          <img src="../jogos/ful.png" alt="Conta Full Acess" />
          <h4>Conta Minecraft Full Access</h4>
          <p>Alteração de skin, nickname e email</p>
          <span>R$ 120,00</span>
          <a href="detalhes.php?id=6" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 7 -->
        <div class="product-card">
          <img src="../jogos/plugin.png" alt="Servidor Custom" />
          <h4>Servidor Customizado (Plugins & Mods)</h4>
          <p>Hospedagem de até 20 players</p>
          <span>R$ 150,00</span>
          <a href="detalhes.php?id=7" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Produto 8 -->
        <div class="product-card">
          <img src="../jogos/purple.png" alt="Skin Pack Lendário" />
          <h4>Skin Pack Lendário</h4>
          <p>Inclui skins de mobs e heróis</p>
          <span>R$ 60,00</span>
          <a href="detalhes.php?id=8" class="buy-btn">Ver Detalhes</a>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 GGMax. Todos os direitos reservados.</p>
  </footer>
  <script>
function buscarProduto() {
  const termo = document.getElementById("search-input").value.toLowerCase();
  const produtos = document.querySelectorAll(".product-card");

  produtos.forEach(produto => {
    const titulo = produto.querySelector("h4").textContent.toLowerCase();
    const descricao = produto.querySelector("p")?.textContent.toLowerCase() || '';

    if (titulo.includes(termo) || descricao.includes(termo)) {
      produto.style.display = "block";
    } else {
      produto.style.display = "none";
    }
  });
}
</script>
</body>
</html>
