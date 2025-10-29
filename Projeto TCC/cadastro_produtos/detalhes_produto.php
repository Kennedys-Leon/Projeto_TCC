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
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="../css/style.css">

<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  background: #000; /* preto no lugar do cinza claro */
  color: #fff; /* texto principal branco para contraste */
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  overflow-x: hidden;
  line-height: 1.6;
  transition: background 0.4s ease, color 0.4s ease;
}

/* ==========================
   TITULOS E LINKS
========================== */
h2 {
  font-size: 2.4rem;
  color: #cc2b5e;
  text-align: center;
  margin: 50px 0 30px;
  text-transform: uppercase;
  text-shadow: 0 0 8px rgba(204, 43, 94, 0.25);
  letter-spacing: 0.5px;
}

a {
  color: #cc2b5e;
  transition: color 0.3s;
  text-decoration: none;
}
a:hover {
  color: #ff5c8a;
}

/* ==========================
   CONTEÚDO PRINCIPAL
========================== */
main.conteudo {
  flex: 1;
}

.container {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  padding: 50px 25px 70px;
}

/* ==========================
   DETALHES DO PRODUTO
========================== */
.detalhes-produto {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 40px;
  background: #111; /* preto suave */
  border-radius: 18px;
  padding: 40px;
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.5);
  transition: box-shadow 0.3s ease;
}
.detalhes-produto:hover {
  box-shadow: 0 10px 35px rgba(0, 0, 0, 0.6);
}

/* ==========================
   GALERIA DE IMAGENS + LUPA
========================== */
.galeria-imagens {
  position: relative;
  flex: 1;
  min-width: 320px;
  text-align: center;
  
  /* NOVO - centraliza horizontal e verticalmente */
  display: flex;
  justify-content: center;
  align-items: center;
}

.galeria-imagens img {
  width: 100%;
  max-width: 480px;
  border-radius: 14px;
  object-fit: cover;
  box-shadow: 0 4px 25px rgba(0, 0, 0, 0.5);
  transition: transform 0.4s ease, box-shadow 0.4s ease;
  cursor: zoom-in;
}


/* LUPA REAL */
.zoom-lens {
  position: absolute;
  border: 2px solid rgba(204, 43, 94, 0.6);
  width: 120px;
  height: 120px;
  border-radius: 50%;
  opacity: 0;
  pointer-events: none;
  background-repeat: no-repeat;
  background-size: 200%;
  transition: opacity 0.2s;
}

/* ==========================
   INFORMAÇÕES DO PRODUTO
========================== */
.info-produto {
  flex: 1;
  min-width: 320px;
  background: #000;
  border: 1px solid #222;
  border-radius: 14px;
  padding: 35px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  color: #fff;
}

.info-produto:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.6);
}

.info-produto p {
  margin-bottom: 16px;
  font-size: 1.05rem;
  color: #eee;
}

.info-produto strong {
  color: #cc2b5e;
  font-weight: 600;
}

/* ==========================
   BOTÕES
========================== */
.btn-preco {
  background: linear-gradient(135deg, #cc2b5e, #ff758c);
  color: #fff;
  border: none;
  padding: 14px 28px;
  border-radius: 10px;
  font-size: 1.05rem;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(204, 43, 94, 0.3);
  text-transform: uppercase;
  letter-spacing: 1px;
}

.btn-preco:hover {
  background: linear-gradient(135deg, #ff7ea6, #ffa3b8);
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(204, 43, 94, 0.4);
}

a.voltar {
  display: inline-block;
  margin-top: 25px;
  padding: 12px 22px;
  border: 1px solid #cc2b5e;
  border-radius: 10px;
  color: #cc2b5e;
  font-weight: 600;
  transition: all 0.3s ease;
}
a.voltar:hover {
  background: #cc2b5e;
  color: #fff;
  box-shadow: 0 0 15px rgba(204, 43, 94, 0.3);
}

/* ==========================
   RODAPÉ
========================== */
footer.rodape {
  background: #000;
  color: #ccc;
  text-align: center;
  padding: 20px 10px;
  font-size: 0.95rem;
  border-top: 2px solid rgba(204, 43, 94, 0.4);
  box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.4);
  position: relative;
}

footer.rodape::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 3px;
  background: linear-gradient(90deg, #cc2b5e, #ff8ea1, #ffbccb);
}

footer.rodape p {
  margin: 0;
  letter-spacing: 0.5px;
}
footer.rodape span {
  color: #cc2b5e;
  font-weight: 600;
}

/* ==========================
   ANIMAÇÕES
========================== */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ==========================
   RESPONSIVIDADE
========================== */
@media (max-width: 900px) {
  .detalhes-produto {
    flex-direction: column;
    align-items: center;
  }
  .info-produto {
    text-align: center;
  }
  h2 {
    font-size: 2rem;
  }
}

@media (max-width: 480px) {
  .info-produto p {
    font-size: 0.9rem;
  }
  footer.rodape {
    font-size: 0.8rem;
  }
}

/* ==========================
   MODAL DE IMAGEM (FULL SCREEN)
========================== */
.image-modal {
  display: none;
  position: fixed;
  z-index: 2000;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.95);
  justify-content: center;
  align-items: center;
  backdrop-filter: blur(5px);
  animation: fadeIn 0.4s ease;
}

.image-modal img {
  max-width: 90%;
  max-height: 85%;
  border-radius: 10px;
  box-shadow: 0 0 25px rgba(255, 255, 255, 0.3);
  animation: zoomIn 0.4s ease;
}

.image-modal .close-modal {
  position: absolute;
  top: 25px;
  right: 35px;
  font-size: 2.2rem;
  color: #fff;
  cursor: pointer;
  transition: color 0.3s ease;
  user-select: none;
}
.image-modal .close-modal:hover {
  color: #ffb0c7;
}

/* Animações */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
@keyframes zoomIn {
  from { transform: scale(0.9); opacity: 0.5; }
  to { transform: scale(1); opacity: 1; }
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

                    <button class="btn-preco add-to-cart" type="button"
                        data-id="<?= (int)$produto['idproduto'] ?>"
                        data-nome="<?= htmlspecialchars($produto['nome'], ENT_QUOTES) ?>"
                        data-preco="<?= number_format((float)$produto['preco'], 2, '.', '') ?>">
                        Adicionar ao Carrinho
                    </button>
                    <a href="../index.php" class="voltar">⬅Retornar ao início</a>
                </div>
            </div>
        </div>
    </main>

    <footer class="rodape">
        <p>&copy; 2025 MaxAcess. Todos os direitos reservados.</p>
    </footer>


    <div id="cart-modal" class="cart-modal" style="display:none;">
      <div class="cart-modal-content">
        <div class="cart-modal-header">
          <h2>Seu Carrinho</h2>
          <span class="cart-close-btn">&times;</span>
        </div>
        <ul class="cart-items"></ul>
        <p id="cart-empty-message" class="cart-empty-message">Seu carrinho está vazio.</p>
        <div class="cart-summary">
          <div class="cart-total">Total: <span id="cart-total-price">R$ 0,00</span></div>
          <button class="cart-checkout-btn">Finalizar Compra</button>
        </div>
      </div>
    </div>

    <script src="../script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('[cart] script carregado (detalhes_produto)');

    const botoesAdd = document.querySelectorAll('.add-to-cart');
    console.log('[cart] botoes encontrados:', botoesAdd.length);

    const cartModal = document.getElementById('cart-modal');
    const cartIcon = document.getElementById('cart-icon');
    const cartItemsContainer = document.querySelector('.cart-items');
    const cartEmptyMessage = document.getElementById('cart-empty-message');
    const cartTotalEl = document.getElementById('cart-total-price');
    const closeBtn = document.querySelector('.cart-close-btn');
    const checkoutBtn = document.querySelector('.cart-checkout-btn');

    function getCarrinho() {
        try {
            return JSON.parse(localStorage.getItem('carrinho')) || [];
        } catch (e) {
            console.error('[cart] erro parse localStorage', e);
            return [];
        }
    }

    function salvarCarrinho(carrinho) {
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        console.log('[cart] carrinho salvo:', carrinho);
        atualizarCarrinhoUI();
    }

    function atualizarCarrinhoUI() {
        const carrinho = getCarrinho();
        if (cartIcon) cartIcon.setAttribute('data-count', carrinho.reduce((s, i) => s + i.quantidade, 0));
        if (!(cartItemsContainer && cartTotalEl && cartEmptyMessage)) return;
        cartItemsContainer.innerHTML = '';
        if (carrinho.length === 0) {
            cartEmptyMessage.style.display = 'block';
        } else {
            cartEmptyMessage.style.display = 'none';
            carrinho.forEach(item => {
                const li = document.createElement('li');
                li.classList.add('cart-item');
                li.innerHTML = `<span>${item.nome}</span><span>R$ ${(item.preco * item.quantidade).toFixed(2).replace('.', ',')}</span><button class="remove-item" data-id="${item.id}">✖</button>`;
                cartItemsContainer.appendChild(li);
            });
            cartItemsContainer.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    let c = getCarrinho();
                    c = c.filter(item => String(item.id) !== String(id));
                    salvarCarrinho(c);
                });
            });
        }
        const total = carrinho.reduce((acc, item) => acc + (item.preco * item.quantidade), 0);
        cartTotalEl.textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
    }

    botoesAdd.forEach(botao => {
        botao.addEventListener('click', function (e) {
            e.preventDefault();
            console.log('[cart] clique em botao add', this.dataset);

            const id = this.dataset.id ? String(this.dataset.id) : null;
            const nome = this.dataset.nome ? String(this.dataset.nome).trim() : null;
            // tenta normalizar separador decimal
            const precoRaw = typeof this.dataset.preco !== 'undefined' ? String(this.dataset.preco).replace(',', '.') : '';
            const preco = parseFloat(precoRaw);

            if (!id || !nome || isNaN(preco)) {
                console.warn('[cart] dados invalidos', { id, nome, precoRaw, preco });
                Swal.fire({ icon: 'error', title: 'Erro', text: 'Dados do produto inválidos.'});
                return;
            }

            // salva no localStorage sempre, antes de qualquer modal/redirect
            let carrinho = getCarrinho();
            const existente = carrinho.find(item => String(item.id) === String(id));
            if (existente) {
                existente.quantidade = (existente.quantidade || 0) + 1;
            } else {
                carrinho.push({ id, nome, preco, quantidade: 1 });
            }

            salvarCarrinho(carrinho);

            Swal.fire({ icon: 'success', title: 'Adicionado ao carrinho!', text: `"${nome}" adicionado.`, timer: 900, showConfirmButton: false });

            // Se o modal existe nesta página, atualiza e abre.
            if (cartModal) {
                setTimeout(() => {
                    cartModal.setAttribute('aria-hidden', 'false');
                    cartModal.style.display = 'flex';
                    atualizarCarrinhoUI();
                }, 200);
                return;
            }

            // Se não há modal, redireciona para a página do carrinho (delay curto para garantir escrita no localStorage)
            console.log('[cart] sem modal, redirecionando para carrinho');
            setTimeout(() => {
                window.location.href = '../carrinho.php';
            }, 250);
        });
    });

    // hooks para abrir/fechar modal se existirem
    if (cartIcon && cartModal) {
        cartIcon.addEventListener('click', () => { cartModal.style.display = 'flex'; atualizarCarrinhoUI(); });
    }
    if (closeBtn && cartModal) {
        closeBtn.addEventListener('click', () => { cartModal.style.display = 'none'; });
    }
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            const carrinho = getCarrinho();
            if (!carrinho.length) {
                Swal.fire({ icon:'warning', title:'Carrinho vazio', text:'Adicione algum produto antes de finalizar.' });
                return;
            }

            Swal.fire({
                title: 'Finalizar compra?',
                text: 'Você será redirecionado ao checkout. Deseja prosseguir?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim, finalizar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '../carrinho/checkout.php'; // ajuste este caminho se o checkout.php estiver em outra pasta
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'cart';
                    input.value = JSON.stringify(carrinho);
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    }

    atualizarCarrinhoUI();
});
</script>


<script> //lupa
document.addEventListener("DOMContentLoaded", function() {
  const modal = document.getElementById("imageModal");
  const modalImg = document.getElementById("modalImage");
  const closeBtn = modal.querySelector(".close-modal");

  // Captura todas as imagens do produto
  const productImages = document.querySelectorAll(".galeria-imagens img");

  productImages.forEach(img => {
    img.addEventListener("click", () => {
      modal.style.display = "flex";
      modalImg.src = img.src;
    });
  });

  // Fecha ao clicar no X
  closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
  });

  // Fecha clicando fora da imagem
  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });

  // Fecha com tecla ESC
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      modal.style.display = "none";
    }
  });
});
</script>




</body>
<!-- Modal de Imagem em Tela Cheia -->
<div id="imageModal" class="image-modal">
  <span class="close-modal">&times;</span>
  <img id="modalImage" src="" alt="Visualização do Produto">
</div>

<div vw-plugin-wrapper>
        <div class="vw-plugin-top-wrapper"></div>
    </div>
</div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
      new window.VLibras.Widget('https://vlibras.gov.br/app');
      
</html>
