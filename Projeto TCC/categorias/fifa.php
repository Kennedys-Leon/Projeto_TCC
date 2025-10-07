<?php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MaxAcess - FIFA</title>
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
    <h1>Comprar e Vender Contas de FIFA</h1>
    <div class="search">
      <input type="text" placeholder="Buscar produto..." class="search-input" />
      <button class="search-btn">Buscar</button>
    </div>
  </section>

  <section class="content">
    <aside class="sidebar">
      <h3>Categorias</h3>
      <ul>
        <li><a href="#">Contas FIFA</a></li>
        <li><a href="#">Coins FIFA</a></li>
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
          <img src="../jogos/fifa.png" alt="Conta FIFA Ultimate" />
          <h4>Conta FIFA Ultimate Team</h4>
          <span>R$ 99,99</span>
          <a href="detalhes.php?id=1" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/coins.png" alt="Coins FIFA" />
          <h4>100.000 Coins FIFA</h4>
          <p>Entrega automática</p>
          <span>R$ 49,99</span>
          <a href="detalhes.php?id=2" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/kit.png" alt="Kit FIFA Ultimate" />
          <h4>Kit FIFA Ultimate</h4>
          <p>Inclui jogadores especiais e pacotes</p>
          <span>R$ 69,99</span>
          <a href="detalhes.php?id=3" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/conta.png" alt="Item raro FIFA" />
          <h4>Item Raro FIFA</h4>
          <p>Jogadores e itens exclusivos</p>
          <span>R$ 120,00</span>
          <a href="detalhes.php?id=4" class="buy-btn">Ver Detalhes</a>
        </div>

        <div class="product-card">
          <img src="../jogos/lendas.png" alt="Lenda FIFA" />
          <h4>Lenda FIFA</h4>
          <p>Jogadores lendários de todas as edições</p>
          <span>R$ 199,99</span>
          <a href="detalhes.php?id=5" class="buy-btn">Ver Detalhes</a>
        </div>

        
        <div class="product-card">
          <img src="../jogos/fifas.png" alt="Lenda FIFA" />
          <h4>Lenda FIFA</h4>
          <p>Jogadores lendários de todas as edições</p>
          <span>R$ 199,99</span>
          <a href="detalhes.php?id=5" class="buy-btn">Ver Detalhes</a>
        </div>


        
        <div class="product-card">
          <img src="../jogos/mobiles.png" alt="Lenda FIFA" />
          <h4>Lenda FIFA</h4>
          <p>Jogadores lendários de todas as edições</p>
          <span>R$ 199,99</span>
          <a href="detalhes.php?id=5" class="buy-btn">Ver Detalhes</a>
        </div>


        
        <div class="product-card">
          <img src="../jogos/mobiles.png" alt="Lenda FIFA" />
          <h4>Lenda FIFA</h4>
          <p>Jogadores lendários de todas as edições</p>
          <span>R$ 199,99</span>
          <a href="detalhes.php?id=5" class="buy-btn">Ver Detalhes</a>
        </div>


        
        <div class="product-card">
          <img src="../jogos/fife.png" alt="Lenda FIFA" />
          <h4>Lenda FIFA</h4>
          <p>Jogadores lendários de todas as edições</p>
          <span>R$ 199,99</span>
          <a href="detalhes.php?id=5" class="buy-btn">Ver Detalhes</a>
        </div>


        
        <div class="product-card">
          <img src="../jogos/lendas.png" alt="Lenda FIFA" />
          <h4>Lenda FIFA</h4>
          <p>Jogadores lendários de todas as edições</p>
          <span>R$ 199,99</span>
          <a href="detalhes.php?id=5" class="buy-btn">Ver Detalhes</a>
        </div>


        
        <div class="product-card">
          <img src="../jogos/lendas.png" alt="Lenda FIFA" />
          <h4>Lenda FIFA</h4>
          <p>Jogadores lendários de todas as edições</p>
          <span>R$ 199,99</span>
          <a href="detalhes.php?id=5" class="buy-btn">Ver Detalhes</a>
        </div>


        
        <div class="product-card">
          <img src="../jogos/lendas.png" alt="Lenda FIFA" />
          <h4>Lenda FIFA</h4>
          <p>Jogadores lendários de todas as edições</p>
          <span>R$ 199,99</span>
          <a href="detalhes.php?id=5" class="buy-btn">Ver Detalhes</a>
        </div>

        <!-- Mais produtos podem ser adicionados aqui -->
      </div>
    </div>
  </section>

  <footer>
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
