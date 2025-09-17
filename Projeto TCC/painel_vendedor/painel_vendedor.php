<?php
session_start();
$nome = isset($_SESSION['vendedor_nome']) ? $_SESSION['vendedor_nome'] : null;
$foto_de_perfil = isset($_SESSION['vendedor_foto']) ? $_SESSION['vendedor_foto'] : null;

include '../conexao.php';

// ============================
// Verifica se vendedor está logado
// ============================
if (!isset($_SESSION['vendedor_logado'])) {
    header("Location: ../login_vendedor/login_vendedor.php");
    exit;
}

$vendedor_id = $_SESSION['vendedor_logado'];

// ============================
// Buscar informações do vendedor
// ============================
$stmt = $pdo->prepare("SELECT * FROM vendedor WHERE idvendedor = ?");
$stmt->execute([$vendedor_id]);
$vendedor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vendedor) {
    die("Erro: vendedor não encontrado.");
}

// ============================
// Total de vendas do vendedor
// ============================
$stmt = $pdo->prepare("SELECT COUNT(*) as total_vendas FROM vendas WHERE idvendedor = ?");
$stmt->execute([$vendedor_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_vendas = isset($row['total_vendas']) ? intval($row['total_vendas']) : 0;

$stmt = $pdo->query("SELECT COUNT(*) as todas_vendas FROM vendas");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$todas_vendas = isset($row['todas_vendas']) ? (int)$row['todas_vendas'] : 1;

if ($todas_vendas == 0) {
    $percentual = 0;
} else {
    $percentual = ($total_vendas / $todas_vendas) * 100;
}

// ============================
// Produtos cadastrados pelo vendedor
// ============================
$stmt = $pdo->prepare("SELECT * FROM produto WHERE idvendedor = ?");
$stmt->execute([$vendedor_id]);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Vendedor - MaxAcess</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/painel.css">
    <link rel="stylesheet" href="../css/perfil_vendedor.css">
    <script>
        // Função JS para trocar abas
        function openTab(tabName) {
            let tabs = document.querySelectorAll('.tab-content');
            let buttons = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            buttons.forEach(btn => btn.classList.remove('active'));
            document.getElementById(tabName).classList.add('active');
            document.querySelector('[data-tab="'+tabName+'"]').classList.add('active');
        }

        // Mostrar/ocultar formulário de novo produto
        function toggleNovoProduto() {
            let form = document.getElementById("formNovoProduto");
            form.style.display = (form.style.display === "none" ? "block" : "none");
        }
    </script>
</head>
<body>
    <header>
        <h1>MaxAcess - Painel do Vendedor</h1>
        <p>Bem-vindo, <?php echo htmlspecialchars($vendedor['nome']); ?>!</p>
    </header>
    

    <div class="container">
        <!-- Abas -->
        <div class="tabs">
            <div class="tab active" data-tab="vendas" onclick="openTab('vendas')">Resumo de Vendas</div>
            <div class="tab" data-tab="produtos" onclick="openTab('produtos')">Meus Produtos</div>
            <div class="tab" data-tab="perfil" onclick="openTab('perfil')">Meu Perfil</div>
        </div>

        <!-- Resumo de Vendas -->
        <div id="vendas" class="tab-content active">
            <h2>Resumo de Vendas</h2>
            <p>Total de vendas realizadas: <b><?php echo $total_vendas; ?></b></p>
            <p>Total do site: <b><?php echo $todas_vendas; ?></b></p>
            <p class="percentual">Participação: <?php echo number_format($percentual, 2); ?>%</p>
        </div>

        <!-- Produtos -->
        <div id="produtos" class="tab-content">
            <h2>Meus Produtos</h2>
            <button class="btn" onclick="toggleNovoProduto()">Cadastrar Novo Produto</button>
            
            <!-- Formulário de Novo Produto -->
            <div id="formNovoProduto" style="display:none; margin-top:15px;">
                <form method="post" action="salvar_produto.php">
                    <label>Nome do Produto:</label><br>
                    <input type="text" name="nome" required><br><br>

                    <label>Preço:</label><br>
                    <input type="number" name="preco" step="0.01" required><br><br>

                    <label>Categoria:</label><br>
                    <input type="text" name="categoria" required><br><br>

                    <label>Quantidade em Estoque:</label><br>
                    <input type="number" name="quantidade" required><br><br>

                    <label>Descrição de Publicação:</label><br>
                    <input type="text" name="data_pub" required><br><br>

                    <label>Descrição do Produto:</label><br>
                    <input type="text" name="descricao" required><br><br>

                    <label>Imagem do produto:</label>
                    <input type="file" name="imagem" accept="image/*" required><br><br>


                    <input type="hidden" name="idvendedor" value="<?php echo $vendedor_id; ?>">

                    <button type="submit" class="btn">Salvar Produto</button>
                </form>
            </div>

            <!-- Lista de Produtos -->
            <table>
                <tr>
                    <th>Nome do produto</th>
                    <th>Preço</th>
                    <th>Categoria</th>
                    <th>Qtd</th>
                    <th>Data Pub.</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($produtos as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['nome']); ?></td>
                    <td>R$ <?php echo number_format($p['preco'],2,",","."); ?></td>
                    <td><?php echo htmlspecialchars($p['categoria']); ?></td>
                    <td><?php echo $p['quantidade_estoque']; ?></td>
                    <td><?php echo date("d/m/Y", strtotime($p['data_pub'])); ?></td>
                    <td><?php echo htmlspecialchars($p['descricao']); ?></td>
                    <td>
                        <button class="btn">Editar</button>
                        <button class="btn">Excluir</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>


        <!-- Perfil -->
        <div id="perfil" class="tab-content">
            <div class="perfil-container">
                <h2 class="perfil-titulo">Meu Perfil</h2>
                <form method="post" action="atualizar_vendedor.php" enctype="multipart/form-data" class="perfil-form">
                    <label>Nome:</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($vendedor['nome']); ?>" required>

                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($vendedor['email']); ?>" required>

                    <label>Telefone:</label>
                    <input type="text" name="telefone" value="<?php echo htmlspecialchars($vendedor['telefone'] ?? ''); ?>">

                    <label>CNPJ:</label>
                    <input type="text" name="cnpj" value="<?php echo htmlspecialchars($vendedor['cnpj'] ?? ''); ?>">

                    <label>Sua foto de preferência:</label>
                    <input type="file" name="foto_de_perfil" accept="image/*"><br><br>

                    <label>Foto de Perfil:</label>
                    <div class="usuario-box">
            <?php if (($foto_de_perfil)): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($foto_de_perfil) ?>" wight="100" height="100"
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
                
            <?php endif; ?>
        </div>
                    <input type="file" name="foto_perfil" accept="image/*">     
                    <button type="submit">Salvar Alterações</button>
                </form>
            </div>
        </div>

    </div>

    <footer>
        <p>Todos os Direitos Reservados - MaxAcess © 2025</p>
    </footer>
</body>
</html>
 