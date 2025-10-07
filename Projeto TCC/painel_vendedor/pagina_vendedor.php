<?php
session_start();

$nome = isset($_SESSION['vendedor_nome']) ? $_SESSION['vendedor_nome'] : null;
$foto_de_perfil = isset($_SESSION['vendedor_foto']) ? $_SESSION['vendedor_foto'] : null;

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Projeto TCC</title>

    <!-- Seus estilos -->
    <link rel="stylesheet" href="../css/estilo.css" />
    <link rel="stylesheet" href="../css/cart.css">

    <!-- Estilo da sidebar (só a lateral) -->
    <link rel="stylesheet" href="../css/sidebar.css">

    

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="dark-mode<?= isset($_SESSION['usuario_nome']) ? ' logged-in' : '' ?>">

    <header class="header">
        <!-- Botão hambúrguer (abre a sidebar) -->
        <button class="menu-toggle" id="menu-toggle" aria-label="Abrir menu">☰</button>

        <!-- Ícone do carrinho original (continua funcionando) -->
        

        <div class="logo-user-container">
            <div class="logo">
                <img src="../img/Logos.png" alt="MaxAcess" class="logo-img" />
            </div>
        </div>

        <nav class="navbar">
            <ul>
                <!-- Estes 3 ficam fora da sidebar, como você pediu -->
                <li><a href="#">Como Funciona?</a></li>
                <li><a href="#">Sobre</a></li>
                <li><a href="#">Serviços</a></li>

                <?php if (isset($_SESSION['vendedor_nome'])): ?>
                <?php else: ?>
                    <li><a href="../vendedor/cadastrovendedor.php">ANUNCIAR</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION['usuario_nome']) && $_SESSION['usuario_nome'] === 'adm'): ?>
                    <li><a href="../consulta/buscar.php">Consulta Usúarios</a></li>
                    <li><a href="../consultaV/buscar2.php">Consulta Vendedor</a></li>
                    <li><a href="../consultageral.php">Consulta Geral</a></li>
                <?php endif; ?>
            </ul>

            <div class="search-cart">

                <!-- Modal do carrinho -->
                <div id="cart-modal" class="cart-modal">
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

                <?php if (isset($_SESSION['vendedor_cadastrado'])): ?>
                    <a href="produto" class="button-login" style="background-color: #8C5B3F; color: white;">
                        Logar como Vendedor
                    </a>
                    <?php unset($_SESSION['vendedor_cadastrado']); ?>
                <?php endif; ?>

                                <!-- Botão de tema igual ao index -->
                <button id="toggle-theme" class="toggle-theme" aria-label="Alternar tema"></button>            
            </div>
        </nav>
    </header>

    <!-- ============ SIDEBAR ============ -->
    <aside class="sidebar" id="sidebar" aria-hidden="true">
        <button class="close-btn" id="close-sidebar" aria-label="Fechar menu">&times;</button>
        <ul>

        <li><a href="pagina_vendedor.php"><img src="../img/casa.png" alt="Início" style="width:16px; height:16px; vertical-align:middle;"> Início</a></li>

        <li><a href="painel_vendedor.php"><img src="../img/casa.png" alt="Informações" style="width:16px; height:16px; vertical-align:middle;"> Minhas informações</a></li>

            <?php if (isset($_SESSION['vendedor_nome'])): ?>
                <li><a href="../cadastro_produtos/cadastroproduto.php"><img src="../img/cadastrar_produto.png" alt="Cadastrar Produto" style="width:16px; height:16px; vertical-align:middle;"> Cadastrar meus Produtos</a></li>
            <?php else: ?>
                <li><a href="../cadastro_vendedor/cadastrovendedor.php"><img src="../img/megafone.png" alt="Megafone" style="width:16px; height:16px; vertical-align:middle;">Anunciar</a></li>
            <?php endif; ?>

            <?php if (!isset($_SESSION['usuario_nome']) && !isset($_SESSION['vendedor_nome'])): ?>
                <li><a href="../login/login.php"><img src="../img/chavis.png" alt="Carrinho" style="width:16px; height:16px; vertical-align:middle;"> Logar</a></li>
                <li><a href="../cadastro_usuario/cadastro.php"><img src="../img/editar.png" alt="Carrinho" style="width:16px; height:16px; vertical-align:middle;"> Cadastrar Conta</a></li>
            <?php endif; ?>

            <?php if (isset($_SESSION['usuario_nome']) && $_SESSION['usuario_nome'] === 'adm'): ?>
                <li><a href="../consulta_usuario/buscar.php">👥 Consulta Usuários</a></li>
                <li><a href="../Consulta_vendedor/buscar2.php">🛍️ Consulta Vendedor</a></li>
                <li><a href="../consultageral.php">📊 Consulta Geral</a></li>
            <?php endif; ?>

            <?php if (isset($_SESSION['usuario_nome']) || isset($_SESSION['vendedor_nome'])): ?>
                <li>
                    <form action="../logout.php" method="post">
                        <button type="submit" class="logout-btn-sidebar"><img src="../img/sair.png" alt="Sair" style="width:16px; height:16px; vertical-align:middle;"> Sair</button>
                    </form>
                </li>
            <?php endif; ?>

            <li><button type="button" id="toggle-theme-sidebar">☾</button></li>
        </ul>

        <div class="usuario-box">
            <?php if (($foto_de_perfil)): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($foto_de_perfil) ?>" 
                    class="usuario-icone-img" 
                    alt="Foto de Perfil">
            <?php else: ?>
                <img src="https://i.pinimg.com/736x/9f/4c/f0/9f4cf0f24b376077a2fcdab2e85c3584.jpg" 
                    class="usuario-icone-img" 
                    alt="Usuário">
            <?php endif; ?>

            <?php if (empty($nome)): ?>
                <a href="cadastro_usuario/cadastro.php" style="text-decoration: none; color: white;">
                    <p class="nome-usuario">Entre ou crie sua conta</p>
                </a>
            <?php else: ?>
                <p class="nome-usuario"><?= htmlspecialchars($nome) ?></p>
            <?php endif; ?>
        </div>
    </aside>
    <!-- ========== FIM SIDEBAR ========== -->

    <main class="conteudo">
        <div class="container">
            <h2>Bem-vindo ao painel de vendedor!</h2>
            <p>Aproveite sua estadia e faça seu anuncio :></p><br>
        </div>
    </main>

    <footer class="rodape">
        <p>&copy; 2025 MaxAcess. Todos os direitos reservados.</p>
    </footer>

    <script src="../script.js"></script>
</body>

</html>
