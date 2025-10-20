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
/* ==================== GERAL ==================== */
body {
  font-family: "Poppins", "Segoe UI", Arial, sans-serif;
  background: #f4f6fb;
  color: #2c3e50;
  margin: 0;
  padding: 0;
}

/* Container principal */
.container {
  max-width: 1100px;
  margin: 50px auto;
  padding: 30px 40px;
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.container:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

/* ==================== TÍTULO ==================== */
.container h2 {
  font-size: 32px;
  text-align: center;
  color: #163b72;
  margin-bottom: 30px;
  font-weight: 600;
  letter-spacing: 0.5px;
  border-bottom: 3px solid #163b72;
  display: inline-block;
  padding-bottom: 5px;
}

/* ==================== LAYOUT PRINCIPAL ==================== */
.detalhes-produto {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
  align-items: start;
}

/* ==================== GALERIA DE IMAGENS ==================== */
.galeria-imagens {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
}

.galeria-imagens .img-detalhe {
  width: 100%;
  max-width: 480px;
  border-radius: 14px;
  object-fit: cover;
  box-shadow: 0 3px 15px rgba(0, 0, 0, 0.12);
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.galeria-imagens .img-detalhe:hover {
  transform: scale(1.05);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.18);
}

/* ==================== INFO PRODUTO ==================== */
.info-produto {
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.info-produto p {
  font-size: 17px;
  line-height: 1.6;
  color: #34495e;
}

.info-produto strong {
  color: #163b72;
}

.info-produto hr {
  margin: 20px 0;
  border: none;
  border-top: 1px solid #e0e0e0;
}

.info-produto h4 {
  font-size: 22px;
  color: #163b72;
  font-weight: 600;
  margin-bottom: 8px;
}

/* ==================== BOTÃO DE COMPRA ==================== */
.btn-preco {
  background: linear-gradient(135deg, #163b72, #2a6fcf);
  color: #fff;
  border: none;
  padding: 14px 28px;
  font-size: 17px;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  transition: background 0.3s, transform 0.25s, box-shadow 0.25s;
  align-self: flex-start;
}
.btn-preco:hover {
  background: linear-gradient(135deg, #2a6fcf, #163b72);
  transform: translateY(-2px);
  box-shadow: 0 6px 15px rgba(42, 111, 207, 0.3);
}
.btn-preco:active {
  transform: scale(0.98);
}

/* ==================== BOTÃO VOLTAR ==================== */
.btn-voltar {
  display: inline-block;
  background: #e5e9f2;
  color: #163b72;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 500;
  text-decoration: none;
  transition: background 0.25s, color 0.25s;
  margin-bottom: 25px;
}
.btn-voltar:hover {
  background: #163b72;
  color: #fff;
}

/* ==================== RODAPÉ ==================== */
.rodape {
  background: #163b72;
  color: #fff;
  text-align: center;
  padding: 18px 0;
  margin-top: 60px;
  font-size: 14px;
  border-top-left-radius: 12px;
  border-top-right-radius: 12px;
  letter-spacing: 0.3px;
  box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.08);
}

/* ==================== RESPONSIVIDADE ==================== */
@media (max-width: 950px) {
  .detalhes-produto {
    grid-template-columns: 1fr;
    gap: 30px;
  }
  .container {
    padding: 25px;
  }
  .container h2 {
    font-size: 26px;
  }
  .btn-preco {
    width: 100%;
    text-align: center;
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

                    <button class="btn-preco add-to-cart"
    data-id="<?= $produto['idproduto'] ?>"
    data-nome="<?= htmlspecialchars($produto['nome']) ?>"
    data-preco="<?= number_format($produto['preco'], 2, '.', '') ?>">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const botoes = document.querySelectorAll('.add-to-cart');

    botoes.forEach(botao => {
        botao.addEventListener('click', function () {
            const id = this.dataset.id;
            const nome = this.dataset.nome;
            const preco = parseFloat(this.dataset.preco);

            let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

            const existente = carrinho.find(item => item.id === id);
            if (existente) {
                existente.quantidade += 1;
            } else {
                carrinho.push({
                    id,
                    nome,
                    preco,
                    quantidade: 1
                });
            }

            localStorage.setItem('carrinho', JSON.stringify(carrinho));

            Swal.fire({
                icon: 'success',
                title: 'Adicionado ao carrinho!',
                text: `"${nome}" foi adicionado com sucesso.`,
                timer: 1800,
                showConfirmButton: false
            });

            atualizarResumoCarrinho(); // Se quiser atualizar o total no carrinho em tempo real
        });
    });

    function atualizarResumoCarrinho() {
        let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
        const total = carrinho.reduce((acc, item) => acc + (item.preco * item.quantidade), 0);
        const cartTotalEl = document.getElementById('cart-total-price');
        if (cartTotalEl) {
            cartTotalEl.textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
        }
    }
});
</script>

</body>
<div vw-plugin-wrapper>
        <div class="vw-plugin-top-wrapper"></div>
    </div>
</div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
      new window.VLibras.Widget('https://vlibras.gov.br/app');
      
</html>
