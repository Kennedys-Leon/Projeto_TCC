<?php
session_start();
include '../cadastro/conexao.php';

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
</head>
<body class="dark-mode">

    <!-- HEADER / SIDEBAR (igual ao index.php) -->
    <?php include '../header_sidebar.php'; ?>

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

                    <button class="btn-preco" onclick="addToCart(<?php echo $produto['idproduto']; ?>)">
                        Adicionar ao Carrinho
                    </button>
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
