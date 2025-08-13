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
    <style>
        .usuario-box {
            display: flex;
            align-items: center;
            background-color: #00c6fd;
            color: white;
            padding: 4px 20px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 17px;
            margin-left: 40px;
        }

        .usuario-icone-img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: white;
            object-fit: cover;
            margin-right: 8px;
        }

        .logout-btn {
            margin-left: 10px;
            background: transparent;
            border: 1px solid white;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: white;
            color: black;
        }

        .button-login-login,
        .button-login {
            display: inline-block;
        }

        body.logged-in .button-login-login,
        body.logged-in .button-login {
            display: none;
        }

        .logo-user-container {
            display: flex;
            align-items: center;
        }
        /* Estilos para o ícone do carrinho de compras */
.cart-icon {
    font-size: 24px;
    color: white;
    margin-left: 20px;
    text-decoration: none;
    position: relative;
    transition: color 0.3s ease;
}

.cart-icon:hover {
    color: #ffd700; /* Dourado */
}

/* Indicador de número de itens no carrinho */
.cart-icon::after {
    content: attr(data-count);
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: #ff0000;
    color: white;
    font-size: 12px;
    font-weight: bold;
    border-radius: 50%;
    padding: 2px 6px;
    line-height: 1;
    display: none; /* Ocultar por padrão, mostrar apenas quando houver itens */
}

/* Mostra o contador se houver itens no carrinho */
.cart-icon.has-items::after {
    display: block;
}

/* Estilos para o Modal/Popup do Carrinho */
.cart-modal {
    display: none; /* Oculto por padrão */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    overflow: auto;
    animation: fadeIn 0.3s ease-out;
}

.cart-modal-content {
    background-color: #2c3e50; /* Cor de fundo escura */
    color: #ecf0f1; /* Cor do texto claro */
    margin: 5% auto;
    padding: 30px;
    border: 1px solid #4a6572;
    width: 80%;
    max-width: 600px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
    position: relative;
    transform: translateY(-50px);
    animation: slideIn 0.4s ease-out forwards;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.cart-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #34495e;
    padding-bottom: 15px;
    margin-bottom: 20px;
}

.cart-modal-header h2 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
}

.cart-close-btn {
    color: #ecf0f1;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s ease;
}

.cart-close-btn:hover,
.cart-close-btn:focus {
    color: #e74c3c; /* Cor de destaque ao passar o mouse */
    text-decoration: none;
}

/* Lista de itens no carrinho */
.cart-items {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.cart-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 15px 0;
    border-bottom: 1px dashed #34495e;
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

.cart-item-details {
    flex-grow: 1;
}

.cart-item-details h4 {
    margin: 0 0 5px 0;
    font-size: 18px;
    font-weight: 500;
}

.cart-item-details p {
    margin: 0;
    font-size: 14px;
    color: #bdc3c7;
}

.cart-item-price {
    font-size: 18px;
    font-weight: 600;
    color: #00c6fd;
}

.cart-remove-btn {
    background: none;
    border: none;
    color: #e74c3c;
    font-size: 20px;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.cart-remove-btn:hover {
    transform: scale(1.1);
}

/* Seção de total e checkout */
.cart-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 2px solid #34495e;
}

.cart-total {
    font-size: 20px;
    font-weight: 600;
}

.cart-checkout-btn {
    background-color: #27ae60;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.cart-checkout-btn:hover {
    background-color: #2ecc71;
    transform: translateY(-2px);
}

/* Estilo para carrinho vazio */
.cart-empty-message {
    text-align: center;
    font-size: 18px;
    color: #7f8c8d;
    padding: 40px 0;
}
    </style>
</head>
<body class="dark-mode"<?= isset($_SESSION['usuario_nome']) ? 'logged-in' : '' ?>>

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
                    <img src="img/user-icon.png" alt="Vendedor" class="usuario-icone-img">
                    <?= htmlspecialchars($_SESSION['vendedor_nome']) ?>
                    <form action="logout.php" method="post" style="margin: 0;">
                        <button class="logout-btn" type="submit">Sair</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="usuario-box">
                    <img src="img/user-icon.png" alt="Usuário" class="usuario-icone-img">
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
                    <li><a href="loginV/cadastroproduto.php">Cadastrar meus Produtos</a></li>
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
    <ul class="cart-items">
      </ul>
    <p id="cart-empty-message" class="cart-empty-message" style="display: block;">Seu carrinho está vazio.</p>
    <div class="cart-summary">
      <div class="cart-total">Total: <span id="cart-total-price">R$ 0,00</span></div>
      <button class="cart-checkout-btn">Finalizar Compra</button>
    </div>
  </div>
</div>

                <?php if (!isset($_SESSION['usuario_nome']) && !isset($_SESSION['vendedor_nome'])): ?>
                    <a href="login/login.php" class="button-login-login">Logar</a>
                    <a href="cadastro/cadastro.php" class="button-login">Cadastar Conta</a>
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
                    <div class="produto"><img src="img/FF.jpeg" alt="Produto 1" /><p>Free Fire</p></div>
                    <div class="produto"><img src="img/clash.jpeg" alt="Produto 2" /><p>Clash Royale</p></div>
                    <div class="produto"><img src="img/fifa.jpeg" alt="Produto 3" /><p>Fifa</p></div>
                    <div class="produto"><img src="img/roblox.jpeg" alt="Produto 4" /><p>Roblox</p></div>
                    <div class="produto"><img src="img/fortnite.jpeg" alt="Produto 5" /><p>Fortnite</p></div>
                    <div class="produto"><img src="img/minecraft.jpeg" alt="Produto 6" /><p>Mine</p></div>
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
                        <img src="img/chave.jpeg" alt="Steam Key Aleatória">
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
