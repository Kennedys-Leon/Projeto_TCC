<?php
session_start();
include '../conexao.php';

// Verifica se foi passado um ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?msg=produto_nao_encontrado");
    exit;
}

$idproduto = intval($_GET['id']);

// Buscar dados do produto
$stmt = $pdo->prepare("SELECT p.*, v.nome AS vendedor_nome, v.email AS vendedor_email 
                       FROM produto p
                       JOIN vendedor v ON p.idvendedor = v.idvendedor
                       WHERE p.idproduto = ?");
$stmt->execute([$idproduto]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    header("Location: index.php?msg=produto_nao_encontrado");
    exit;
}

// Buscar imagens do produto
$stmtImg = $pdo->prepare("SELECT imagem FROM imagens WHERE idproduto = ?");
$stmtImg->execute([$idproduto]);
$imagens = $stmtImg->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($produto['nome']); ?> - MaxAcess</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/style.css">

<style>
body {
  font-family: "Segoe UI", Arial, sans-serif;
  background: #f7f9fc;
  color: #333;
  margin: 0;
  padding: 0;
}

/* Container principal */
.container {
  max-width: 1200px;
  margin: 30px auto;
  padding: 20px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* Título do produto */
.container h2 {
  font-size: 28px;
  margin-bottom: 25px;
  color: rgb(21, 55, 100);
}

/* Layout principal de detalhes */
.detalhes-produto {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 25px;
  align-items: start;
}

/* Galeria de imagens */
.galeria-imagens {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.galeria-imagens .img-detalhe {
  width: 100%;
  max-width: 450px;
  border-radius: 10px;
  margin-bottom: 15px;
  object-fit: cover;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.galeria-imagens img {
  transition: transform 0.2s ease-in-out;
}

.galeria-imagens img:hover {
  transform: scale(1.03);
}

/* Informações do produto */
.info-produto {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.info-produto p {
  font-size: 16px;
  line-height: 1.5;
}

.info-produto hr {
  margin: 15px 0;
  border: none;
  border-top: 1px solid #ddd;
}

.info-produto h4 {
  font-size: 20px;
  margin-bottom: 10px;
  color: rgb(21, 55, 100);
}

/* Botão de comprar */
.btn-preco {
  background-color: rgb(21, 55, 100);
  color: #fff;
  border: none;
  padding: 14px 25px;
  font-size: 18px;
  border-radius: 8px;
  cursor: pointer;
  transition: background 0.3s, transform 0.2s;
  margin-top: 10px;
}

.btn-preco:hover {
  background: #004a99;
  transform: scale(1.03);
}

/* Rodapé */
.rodape {
  background: rgb(21, 55, 100);
  color: #fff;
  text-align: center;
  padding: 15px 0;
  margin-top: 40px;
  font-size: 14px;
  border-top-left-radius: 8px;
  border-top-right-radius: 8px;
}

/* Responsividade */
@media (max-width: 900px) {
  .detalhes-produto {
    grid-template-columns: 1fr;
  }

  .galeria-imagens .img-detalhe {
    max-width: 100%;
  }
}


</style>

</head>
<body class="dark-mode">

    <!-- HEADER / SIDEBAR (igual ao index.php) -->


    <main class="conteudo">
        <div class="container">
            <h2><?php echo htmlspecialchars($produto['nome']); ?></h2>

            <div class="detalhes-produto">
                <div class="galeria-imagens">
                    <?php if (count($imagens) > 0): ?>
                        <?php foreach ($imagens as $img): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($img['imagem']); ?>" 
                                 alt="<?php echo htmlspecialchars($produto['nome']); ?>" 
                                 class="img-detalhe">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <img src="img/sem-imagem.png" alt="Sem imagem" class="img-detalhe">
                    <?php endif; ?>
                </div>

                <div class="info-produto">
                    <p><strong>Preço:</strong> R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                    <p><strong>Categoria:</strong> <?php echo htmlspecialchars($produto['categoria']); ?></p>
                    <p><strong>Quantidade disponível:</strong> <?php echo intval($produto['quantidade_estoque']); ?></p>
                    <p><strong>Publicado em:</strong> <?php echo date("d/m/Y", strtotime($produto['data_pub'])); ?></p>
                    <p><strong>Descrição:</strong> <?php echo nl2br(htmlspecialchars($produto['descricao'])); ?></p>

                    <hr>
                    <h4>Vendedor</h4>
                    <p><strong>Nome:</strong> <?php echo htmlspecialchars($produto['vendedor_nome']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($produto['vendedor_email']); ?></p>

                    <a href="../carrinho/checkout.php?id=<?php echo $produto['idproduto']; ?>" class="btn-preco">
Adicionar ao Carrinho                       
     </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="rodape">
        <p>&copy; 2025 MaxAcess. Todos os direitos reservados.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
