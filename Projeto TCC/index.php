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
    </style>
</head>
<body class="dark-mode"<?= isset($_SESSION['usuario_nome']) ? 'logged-in' : '' ?>>
    <header class="header">
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
                <a href="#" class="cart-icon">🛒</a>

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
