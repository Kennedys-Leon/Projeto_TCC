<?php
session_start();

$nome = isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : null;
$foto_de_perfil = isset($_SESSION['usuario_foto']) ? $_SESSION['usuario_foto'] : null;

include 'conexao.php';

$stmt = $pdo->query("
    SELECT p.idproduto, p.nome, p.preco, p.descricao, p.data_pub, i.imagem
    FROM produto p
    LEFT JOIN imagens i ON p.idproduto = i.idproduto
    GROUP BY p.idproduto
    ORDER BY p.data_pub DESC
    LIMIT 12
");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link href="https://fonts.googleapis.com/css2?family=Playfair Display:wght@400;600&display=swap" rel="stylesheet">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Projeto TCC - MaxAcess</title>

    <!-- Seus estilos -->
    <link rel="stylesheet" href="css/estilo.css" />
    <link rel="stylesheet" href="css/cart.css">

    <!-- Estilo da sidebar (só a lateral) -->
    <link rel="stylesheet" href="css/sidebar.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="dark-mode<?= isset($_SESSION['usuario_nome']) ? ' logged-in' : '' ?>">

    <header class="header">
        <!-- Botão hambúrguer (abre a sidebar) -->
        <button class="menu-toggle" id="menu-toggle" aria-label="Abrir menu lateral" aria-expanded="false">☰</button>
        


        <!-- Ícone do carrinho original (continua funcionando) -->
        

        <div class="logo-user-container">
            <div class="logo">
                <img src="img/logo.png" alt="MaxAcess" class="logo-img" />
            </div>
        </div>

        <nav class="navbar">
    <ul>
            <li class="dropdown">
  <a href="#">Categorias ▼</a>
  <ul class="submenu">
    <li>
      <a href="categorias/brawlstars.php">
        <img src="img/brawl.webp" alt="Brawl Stars" width="20" height="20" style="vertical-align: middle; margin-right: 8px;">
        Brawl Stars
      </a>
    </li>
    <li>
      <a href="categorias/fifa.php">
        <img src="img/fifa.jpeg" alt="FIFA" width="20" height="20" style="vertical-align: middle; margin-right: 8px;">
        FIFA
      </a>
    </li>
    <li>
      <a href="categorias/fortnite.php">
        <img src="img/fortnite.jpeg" alt="Fortnite" width="20" height="20" style="vertical-align: middle; margin-right: 8px;">
        Fortnite
      </a>
    </li>
    <li>
      <a href="categorias/freefire.php">
        <img src="img/FF.jpeg" alt="Free Fire" width="20" height="20" style="vertical-align: middle; margin-right: 8px;">
        Free Fire
      </a>
    </li>
    <li>
      <a href="categorias/minecraft.php">
        <img src="img/minecraft.jpeg" alt="Minecraft" width="20" height="20" style="vertical-align: middle; margin-right: 8px;">
        Minecraft
      </a>
    </li>
    <li>
      <a href="categorias/roblox.php">
        <img src="img/roblox.jpeg" alt="Roblox" width="20" height="20" style="vertical-align: middle; margin-right: 8px;">
        Roblox
      </a>
    </li>
  </ul>
</li>

                 <li><a href="informacoes_cabecalho/como_funciona.php">Como Funciona?</a></li>
                <li><a href="informacoes_cabecalho/sobre.php">Sobre</a></li>
                <li><a href="informacoes_cabecalho/servicos.php">Serviços</a></li>
                

                <?php if (!isset($_SESSION['usuario_nome'])): ?>
                    <li><a href="cadastro_vendedor/cadastrovendedor.php">ANUNCIAR</a></li>
                <?php endif; ?>


            <?php if (isset($_SESSION['usuario_nome']) && $_SESSION['usuario_nome'] === 'adm'): ?>
                <li><a href="consulta/buscar.php">Consulta Usúarios</a></li>
                <li><a href="consultaV/buscar2.php">Consulta Vendedor</a></li>
                <li><a href="consultageral.php">Consulta Geral</a></li>
            <?php endif; ?>
    </ul>

        <!-- Elementos de busca e carrinho FORA da lista -->
        <div class="search-bar-wrapper">
            <input id="search-input" class="search-bar" type="text" placeholder="Buscar 🔍︎" />
        </div>

        <div class="search-cart">
            <br>
                <!-- Modal do carrinho -->
                <div id="cart-modal" class="cart-modal" role="dialog" aria-labelledby="cart-modal-header" aria-describedby="cart-modal-content" aria-hidden="true">
                    <div class="cart-modal-content" id="cart-modal-content">
                        <div class="cart-modal-header">
                            <h2 id="cart-modal-header">Seu Carrinho</h2>
                            <button class="cart-close-btn" aria-label="Fechar carrinho">&times;</button>
                        </div>
                        <ul class="cart-items" aria-label="Itens no carrinho"></ul>
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

                <!-- Botão de tema (continua no header) -->
                <!-- Botão com ícone moderno -->
                <button id="toggle-theme" class="toggle-theme" aria-label="Alternar tema"></button>
        </div>
        </nav>
    </header>

    <!-- ============ SIDEBAR ============ -->
    <!-- ============ SIDEBAR ACESSÍVEL ============ -->
<aside class="sidebar" id="sidebar" aria-hidden="true" role="navigation" aria-label="Menu lateral de navegação">
    <button class="close-btn" id="close-sidebar" aria-label="Fechar menu lateral">&times;</button>
    <ul>
        <li>
            <a href="index.php">
                <img src="img/casa.png" alt="Página inicial" width="16" height="16"> Início
            </a>
        </li>
        <li>
            <a href="carrinho/checkout.php" id="open-cart">
                <img src="img/carrinho-de-compras.png" alt="Carrinho de compras" width="16" height="16"> Carrinho
            </a>
        </li>

        <?php if (isset($_SESSION['vendedor_nome'])): ?>
            <li>
                <a href="cadastro_produtos/cadastroproduto.php">
                    <img src="img/cadastrar_produto.png" alt="Cadastrar Produto" width="16" height="16"> 
                    Cadastrar meus Produtos
                </a>
            </li>
        <?php elseif (isset($_SESSION['usuario_nome'])): ?>
            <li>
                <a href="editar_perfil/perfil.php">
                    <img src="img/editar.png" alt="Editar Perfil" width="16" height="16"> 
                    Meu Perfil
                </a>
            </li>
        <?php else: ?>
            <li>
                <a href="cadastro_vendedor/cadastrovendedor.php">
                    <img src="img/megafone.png" alt="Anunciar produto" width="16" height="16"> Quero Vender
                </a>
            </li>
            <li>
                <a href="cadastro_usuario/cadastro.php">
                    <img src="img/editar.png" alt="Criar Conta" width="16" height="16"> Criar minha Conta
                </a>
            </li>
            <li>
                <a href="login/login.php">
                    <img src="img/chavis.png" alt="Entrar" width="16" height="16"> Entrar
                </a>
            </li>
        <?php endif; ?>

        <?php if (isset($_SESSION['usuario_nome']) && $_SESSION['usuario_nome'] === 'adm'): ?>
            <li><a href="consulta_usuario/buscar.php">👥 Consulta Usuários</a></li>
            <li><a href="consulta_vendedor/buscar2.php">🛍️ Consulta Vendedor</a></li>
            <li><a href="consultageral.php">📊 Consulta Geral</a></li>
        <?php endif; ?>

        <?php if (isset($_SESSION['usuario_nome']) || isset($_SESSION['vendedor_nome'])): ?>
            <li>
                <form action="logout.php" method="post">
                    <button type="submit" class="logout-btn-sidebar" aria-label="Encerrar sessão">
                        <img src="img/sair.png" alt="Sair" width="16" height="16"> Sair
                    </button>
                </form>
            </li>
        <?php endif; ?>

        <!-- 🌙 Alternância de tema -->
        <li>
            <button type="button" id="toggle-theme-sidebar" aria-label="Alternar tema na sidebar">☾</button>
        </li>

        <!-- 👁️ Modo daltônico (preto e branco) -->
        <li>
            <button type="button" id="modo-daltonico-sidebar" aria-pressed="false" aria-label="Ativar modo daltônico na sidebar">
            <img src="img/eye.png" alt="Sair" width="16" height="16"> Modo Daltonico
                
            </button>
        </li>
    </ul>

    <!-- Bloco do usuário -->
    <div class="usuario-box" aria-label="Informações do usuário logado">
        <?php if (($foto_de_perfil)): ?>
            <img src="data:image/*;base64,<?= base64_encode($foto_de_perfil) ?>" class="usuario-icone-img" alt="Foto de perfil do usuário">
        <?php else: ?>
            <img src="https://i.pinimg.com/736x/9f/4c/f0/9f4cf0f24b376077a2fcdab2e85c3584.jpg" class="usuario-icone-img" alt="Usuário padrão">
        <?php endif; ?>

        <?php if (empty($nome)): ?>
            <a href="cadastro_usuario/cadastro" class="btn-cadastro">Usúario não logado</a>
        <?php else: ?>
            <p class="nome-usuario"><?= htmlspecialchars($nome) ?></p>
        <?php endif; ?>
    </div>
</aside>
<!-- ========== FIM SIDEBAR ========== -->


    <main id="main-content" class="conteudo">
        <img src="img/banneratu.png" alt="Banner MaxAcess" class="banner-destaque" />
        <div class="container">
            <br>
        <h2>Bem-vindo ao MaxAcess, venda ou compre</h2>
            <p>contas, jogos, gift cards, gold, itens digitais e mais :></p><br>

            <section class="produtos-destaque">
                <h3>Categorias em Destaque</h3>
                <div class="lista-produtos">
                <a href="categorias/freefire.php" class="produto">
                <img src="img/FF.jpeg" alt="Categoria Free Fire" />
                <p>Free Fire</p>
                </a>

                    <a href="categorias/brawlstars.php" class="produto">
                        <img src="img/brawl.webp" alt="Categoria Brawl Stars" />
                        <p>Brawl Stars </p>
                    </a>
                    <a href="categorias/fifa.php" class="produto">
                        <img src="img/fifa.jpeg" alt="Categoria FIFA" />
                        <p>Fifa</p>
                    </a>
                    <a href="categorias/roblox.php" class="produto">
                        <img src="img/roblox.jpeg" alt="Categoria Roblox" />
                        <p>Roblox</p>
                    </a>
                    <a href="categorias/fortnite.php" class="produto">
                        <img src="img/fortnite.jpeg" alt="Categoria Fortnite" />
                        <p>Fortnite</p>
                    </a>
                    <a href="categorias/minecraft.php" class="produto">
                        <img src="img/minecraft.jpeg" alt="Categoria Minecraft" />
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
                        <button class="btn-preco">Adicionar ao Carrinho</button>
                    </div>
                    <div class="card-produto">
                        <img src="img/chave.png" alt="Steam Key Aleatória">
                        <p>STEAM ALEATÓRIA - KEY ATIVÁVEL EM SUA CONTA - ENTREGA AUTOMÁTICA</p>
                        <button class="btn-preco">Adicionar ao Carrinho</button>
                    </div>
                    <div class="card-produto">
                        <img src="img/mine.jpeg" alt="Conta Minecraft">
                        <p>CONTA COM 1 CURSO BLOCKCITY E 1 SKIN EXCLUSIVA</p>
                        <button class="btn-preco">Adicionar ao Carrinho</button>
                    </div>
                    <div class="card-produto">
                        <img src="img/cs.jpeg" alt="CS:GO Premium">
                        <p>CONTA EXCLUSIVA COM AS MELHORES SKINS</p>
                        <button class="btn-preco">Adicionar ao Carrinho</button>
                    </div>
                </div>
            </section>

            <section class="produtos-destaque">
            <h3>Produtos recém adicionados</h3>
            <div class="lista-produtos-destaque">
                <?php if (count($produtos) > 0): ?>
                    <?php foreach ($produtos as $produto): ?>
                        <div class="card-produto">
                            <?php if (!empty($produto['imagem'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($produto['imagem']) ?>" 
                                    alt="<?= htmlspecialchars($produto['nome']) ?>">
                            <?php else: ?>
                                <img src="img/usuario.png" alt="Foto de Perfil">
                            <?php endif; ?>

                            <p><?php echo htmlspecialchars($produto['nome']); ?></p>
                            <p><strong>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></strong></p>
                            <a href="cadastro_produtos/detalhes_produto.php?id=<?php echo $produto['idproduto']; ?>" class="btn-detalhes">
                                Ver Detalhes
                            </a>
                            <?php if (isset($_SESSION['usuario_nome'])): ?>
                                <a href="add_carrinho.php?id=<?= $produto['idproduto'] ?>" class="btn-preco">
                                    Adicionar ao Carrinho
                                </a>
                            <?php else: ?>
                                <button 
                                    class="btn-detalhes"
                                    onclick="window.location.href='cadastro_usuario/cadastro.php'">
                                    Adicionar ao Carrinho
                                </button>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                        <?php else: ?>
                            <p>Sem produtos cadastrados.</p>
                        <?php endif; ?>
            </div>
        </section>
        </div>
    </main>

    <footer class="rodape">
        <p>&copy; 2025 MaxAcess. Todos os direitos reservados.</p>
    </footer>

    <script src="script.js"></script>
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
