<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Projeto TCC</title>
    <link rel="stylesheet" href="css/estilo.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/cart.css">
</head>

<body class="dark-mode"<?= isset($_SESSION['usuario_nome']) ? ' logged-in' : '' ?>>

    <header class="header">
        <a href="#" class="cart-icon" id="cart-icon" data-count="0">🛒</a>
        <div class="logo-user-container">
            <div class="logo">
                <img src="img/Logos.png" alt="MaxAcess" class="logo-img" />
            </div>

            <?php if (isset($_SESSION['usuario_nome'])): ?>
                <div class="usuario-box">
                    <img src="uploads/<?php echo isset($_SESSION['usuario_foto']) ? htmlspecialchars($_SESSION['usuario_foto']) : 'user-icon.png'; ?>" alt="Foto do usuário" class="usuario-icone-img">
                    <?= htmlspecialchars($_SESSION['usuario_nome']) ?>
                    <form action="logout.php" method="post" style="margin: 0;">
                        <button class="logout-btn" type="submit">Sair</button>
                    </form>
                </div>
            <?php elseif (isset($_SESSION['vendedor_nome'])): ?>
                <div class="usuario-box">
                    <img src="https://i.pinimg.com/736x/9f/4c/f0/9f4cf0f24b376077a2fcdab2e85c3584.jpg" alt="Vendedor" class="usuario-icone-img">
                    <?= htmlspecialchars($_SESSION['vendedor_nome']) ?>
                    <form action="logout.php" method="post" style="margin: 0;">
                        <button class="logout-btn" type="submit">Sair</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="usuario-box">
                    <img src="https://i.pinimg.com/736x/9f/4c/f0/9f4cf0f24b376077a2fcdab2e85c3584.jpg" alt="Usuário" class="usuario-icone-img">
                    Usuário
                </div>
            <?php endif; ?>
        </div>

        <nav class="navbar">
            <ul>
                <li><a href="#">Como Funciona?</a></li>
                <li><a href="#">Sobre</a></li>
                <li><a href="#">Serviços</a></li>

                <?php if (isset($_SESSION['vendedor_nome'])): ?>
                    <li><a href="produtos/cadastroproduto.php">Cadastrar meus Produtos</a></li>
                <?php else: ?>
                    <li><a href="vendedor/cadastrovendedor.php">ANUNCIAR</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION['usuario_nome']) && $_SESSION['usuario_nome'] === 'adm'): ?>
                    <li><a href="consulta/buscar.php">Consulta Usúarios</a></li>
                    <li><a href="consultaV/buscar2.php">Consulta Vendedor</a></li>
                    <li><a href="consultageral.php">Consulta Geral</a></li>
                <?php endif; ?>
            </ul>

            <div class="search-cart">
                <input class="search-bar" type="text" placeholder="Buscar..." />
                <div id="cart-modal" class="cart-modal">
                    <div class="cart-modal-content">
                        <div class="cart-modal-header">
                            <h2>Seu Carrinho</h2>
                            <span class="cart-close-btn">&times;</span>
                        </div>
                        <ul class="cart-items"></ul>
                        <p id="cart-empty-message" class="cart-empty-message" style="display: block;">Seu carrinho está vazio.</p>
                        <div class="cart-summary">
                            <div class="cart-total">Total: <span id="cart-total-price">R$ 0,00</span></div>
                            <button class="cart-checkout-btn">Finalizar Compra</button>
                        </div>
                    </div>
                </div>

                <?php if (!isset($_SESSION['usuario_nome']) && !isset($_SESSION['vendedor_nome'])): ?>
                    <a href="login/login.php" class="button-login-login">Logar</a>
                    <a href="cadastro/cadastro.php" class="button-login">Cadastrar Conta</a>
                <?php endif; ?>

                <?php if (isset($_SESSION['vendedor_cadastrado'])): ?>
                    <a href="produto" class="button-login" style="background-color: #8C5B3F; color: white;">
                        Logar como Vendedor
                    </a>
                    <?php unset($_SESSION['vendedor_cadastrado']); ?>
                <?php endif; ?>

                <input type="text" id="toggle-theme" class="toggle-theme" value="🌙 Tema Escuro" readonly />
            </div>
        </nav>
    </header>

    <main class="conteudo">
        <div class="container">
            <h2>Bem-vindo ao MaxAcess, venda ou compre</h2>
            <p>contas, jogos, gift cards, gold, itens digitais e mais :></p>

            <img src="img/banner.png" alt="Banner MaxAcess" class="banner-destaque" />

            <section class="produtos-destaque">
                <h3>Categorias em Destaque</h3>
                <div class="lista-produtos">
                    <a href="freefire.php" class="produto">
                        <img src="img/FF.jpeg" alt="Produto 1" />
                        <p>Free Fire</p>
                    </a>
                    <a href="clashroyale.php" class="produto">
                        <img src="img/clash.jpeg" alt="Produto 2" />
                        <p>Clash Royale</p>
                    </a>
                    <a href="fifa.php" class="produto">
                        <img src="img/fifa.jpeg" alt="Produto 3" />
                        <p>Fifa</p>
                    </a>
                    <a href="roblox.php" class="produto">
                        <img src="img/roblox.jpeg" alt="Produto 4" />
                        <p>Roblox</p>
                    </a>
                    <a href="fortnite.php" class="produto">
                        <img src="img/fortnite.jpeg" alt="Produto 5" />
                        <p>Fortnite</p>
                    </a>
                    <a href="minecraft.php" class="produto">
                        <img src="img/minecraft.jpeg" alt="Produto 6" />
                        <p>Mine</p>
                    </a>
                </div>
            </section>

            <section class="produtos-destaque">
                <h3>Produtos em Destaques</h3>
                <div class="lista-produtos-destaque">
                    <div class="card-produto">
                        <img src="img/steam.jpeg" alt="Jogos Steam">
                        <p>STEAM JOGOS (CÓDIGOS DE ATIVAÇÃO) - CONTA STEAM OFICIAL (ENTREGA AUTOMÁTICA)</p>
                        <button class="btn-preco">R$ 25,00 +</button>
                    </div>
                    <div class="card-produto">
                        <img src="img/chave.png" alt="Steam Key Aleatória">
                        <p>STEAM ALEATÓRIA - KEY ATIVÁVEL EM SUA CONTA - ENTREGA AUTOMÁTICA</p>
                        <button class="btn-preco">R$ 25,00 +</button>
                    </div>
                    <div class="card-produto">
                        <img src="img/mine.jpeg" alt="Conta Minecraft">
                        <p>CONTA COM 1 CURSO BLOCKCITY E 1 SKIN EXCLUSIVA</p>
                        <button class="btn-preco">R$ 25,00 +</button>
                    </div>
                    <div class="card-produto">
                        <img src="img/cs.jpeg" alt="CS:GO Premium">
                        <p>CONTA EXCLUSIVA COM AS MELHORES SKINS</p>
                        <button class="btn-preco">R$ 25,00 +</button>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="rodape">
        <p>&copy; 2025 MaxAcess. Todos os direitos reservados.</p>
    </footer>

    <script src="script.js"></script>
</body>

</html>

<script>
let cartItems = JSON.parse(localStorage.getItem('cart')) || [];

function updateCartCount() {
    const cartIcon = document.getElementById('cart-icon');
    const cartCount = cartItems.length;
    cartIcon.setAttribute
