<?php
include '../conexao.php';

// Buscar apenas produtos da categoria "Call of Duty"
$sql = "SELECT p.*, i.imagem 
        FROM produto p
        LEFT JOIN imagens i ON p.idproduto = i.idproduto
        WHERE p.categoria = 'Call of Duty'
        GROUP BY p.idproduto
        ORDER BY p.data_pub DESC";
$stmt = $pdo->query($sql);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MaxAcess - Call of Duty</title>
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
    <h1>Produtos Call of Duty</h1>
    <div class="search">
      <input type="text" id="search-input" placeholder="Buscar produto..." class="search-input" />
      <button class="search-btn" onclick="buscarProduto()">Buscar</button>
    </div>
  </section>

  <section class="content">
    <aside class="sidebar">
      <h3>Categorias</h3>
      <ul>
        <li><a href="#">Call of Duty: Modern Warfare</a></li>
        <li><a href="#">Call of Duty: Warzone</a></li>
        <li><a href="#">Call of Duty: Black Ops</a></li>
      </ul>
    </aside>

    <div class="content-area">
      <div class="product-list">
        <?php if (count($produtos) > 0): ?>
          <?php foreach ($produtos as $produto): ?>
            <div class="product-card">
              <?php if (!empty($produto['imagem'])): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
              <?php else: ?>
                <img src="../img/sem-imagem.png" alt="Sem imagem disponível">
              <?php endif; ?>
              <h4><?= htmlspecialchars($produto['nome']) ?></h4>
              <p><?= htmlspecialchars($produto['descricao']) ?></p>
              <span class="price">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="text-align:center; font-size:18px; color:#888; margin-top:40px;">
            Nenhum produto Call of Duty foi adicionado ainda. 😅<br>
            Em breve novos produtos estarão disponíveis!
          </p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 MaxAcess. Todos os direitos reservados.</p>
  </footer>

  <script>
  function buscarProduto() {
    const termo = document.getElementById("search-input").value.toLowerCase();
    const produtos = document.querySelectorAll(".product-card");

    produtos.forEach(produto => {
      const titulo = produto.querySelector("h4").textContent.toLowerCase();
      const descricao = produto.querySelector("p")?.textContent.toLowerCase() || '';
      produto.style.display = (titulo.includes(termo) || descricao.includes(termo)) ? "block" : "none";
    });
  }
  </script>
</body>
<div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
        <div class="vw-plugin-top-wrapper"></div>
    </div>
</div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
      new window.VLibras.Widget('https://vlibras.gov.br/app');
  </script>
</html>
