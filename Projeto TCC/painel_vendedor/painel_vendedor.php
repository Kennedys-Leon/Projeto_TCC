<?php
session_start();
$nome = isset($_SESSION['vendedor_nome']) ? $_SESSION['vendedor_nome'] : null;
$foto_de_perfil = isset($_SESSION['vendedor_foto']) ? $_SESSION['vendedor_foto'] : null;

include '../conexao.php';

// Verifica se vendedor está logado
if (!isset($_SESSION['vendedor_logado'])) {
    header("Location: ../login_vendedor/login_vendedor.php");
    exit;
}

$vendedor_id = $_SESSION['vendedor_logado'];

// Buscar informações do vendedor
$stmt = $pdo->prepare("SELECT * FROM vendedor WHERE idvendedor = ?");
$stmt->execute([$vendedor_id]);
$vendedor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vendedor) {
    die("Erro: vendedor não encontrado.");
}

// Total de vendas do vendedor
$stmt = $pdo->prepare("SELECT COUNT(*) as total_vendas FROM vendas WHERE idvendedor = ?");
$stmt->execute([$vendedor_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_vendas = isset($row['total_vendas']) ? intval($row['total_vendas']) : 0;

$stmt = $pdo->query("SELECT COUNT(*) as todas_vendas FROM vendas");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$todas_vendas = isset($row['todas_vendas']) ? (int)$row['todas_vendas'] : 1;

$percentual = ($todas_vendas == 0) ? 0 : ($total_vendas / $todas_vendas) * 100;

// Produtos cadastrados pelo vendedor
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

    <style>
        .perfil-imagem-display {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 18px auto;
            display: block;
            border: 1px solid #bbb;
            background: #2d2d4d;
        }

        select, input, textarea {
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #bbb;
            width: 100%;
            box-sizing: border-box;
            font-size: 14px;
        }
    </style>

        <script>
            function openTab(tabName) {
                let tabs = document.querySelectorAll('.tab-content');
                let buttons = document.querySelectorAll('.tab');
                tabs.forEach(tab => tab.classList.remove('active'));
                buttons.forEach(btn => btn.classList.remove('active'));
                document.getElementById(tabName).classList.add('active');
                document.querySelector('[data-tab="'+tabName+'"]').classList.add('active');
            }

            function toggleNovoProduto() {
                let form = document.getElementById("formNovoProduto");
                form.style.display = (form.style.display === "none" ? "block" : "none");
            }

            // ========== Máscaras automáticas ==========
            document.addEventListener("DOMContentLoaded", () => {
                // Preço produto
                const precoInput = document.getElementById("CampPreco");
                if (precoInput) {
                    precoInput.addEventListener("input", function(e) {
                        let valor = e.target.value.replace(/\D/g, "");
                        if (valor === "") {
                            e.target.value = "";
                            return;
                        }
                        valor = (valor / 100).toFixed(2) + "";
                        valor = valor.replace(".", ",");
                        e.target.value = valor;
                    });
                }

                // Data publicação
                const dataInput = document.getElementById("CampData_pub");
                if (dataInput) {
                    dataInput.addEventListener("input", function(e) {
                        let valor = e.target.value.replace(/\D/g, "");
                        if (valor.length > 8) valor = valor.substring(0, 8);
                        let formatado = "";
                        if (valor.length > 4) {
                            formatado = valor.substring(0, 2) + "/" + valor.substring(2, 4) + "/" + valor.substring(4);
                        } else if (valor.length > 2) {
                            formatado = valor.substring(0, 2) + "/" + valor.substring(2);
                        } else {
                            formatado = valor;
                        }
                        e.target.value = formatado;
                    });
                }

                // Telefone
                const telInput = document.getElementById("CampTelefone");
                if (telInput) {
                    telInput.addEventListener("input", function(e) {
                        let valor = e.target.value.replace(/\D/g, "");
                        if (valor.length > 11) valor = valor.substring(0, 11);
                        if (valor.length > 6) {
                            e.target.value = `(${valor.substring(0, 2)}) ${valor.substring(2, 7)}-${valor.substring(7)}`;
                        } else if (valor.length > 2) {
                            e.target.value = `(${valor.substring(0, 2)}) ${valor.substring(2)}`;
                        } else {
                            e.target.value = valor;
                        }
                    });
                }

                // CPF
                const cpfInput = document.getElementById("CampCPF");
                if (cpfInput) {
                    cpfInput.addEventListener("input", function(e) {
                        let valor = e.target.value.replace(/\D/g, "");
                        if (valor.length > 11) valor = valor.substring(0, 11);
                        if (valor.length > 9) {
                            e.target.value = valor.substring(0, 3) + "." + valor.substring(3, 6) + "." + valor.substring(6, 9) + "-" + valor.substring(9);
                        } else if (valor.length > 6) {
                            e.target.value = valor.substring(0, 3) + "." + valor.substring(3, 6) + "." + valor.substring(6);
                        } else if (valor.length > 3) {
                            e.target.value = valor.substring(0, 3) + "." + valor.substring(3);
                        } else {
                            e.target.value = valor;
                        }
                    });
                }

                // CNPJ
                const cnpjInput = document.getElementById("CampCNPJ");
                if (cnpjInput) {
                    cnpjInput.addEventListener("input", function(e) {
                        let valor = e.target.value.replace(/\D/g, "");
                        if (valor.length > 14) valor = valor.substring(0, 14);
                        if (valor.length > 12) {
                            e.target.value = valor.substring(0, 2) + "." + valor.substring(2, 5) + "." + valor.substring(5, 8) + "/" + valor.substring(8, 12) + "-" + valor.substring(12);
                        } else if (valor.length > 8) {
                            e.target.value = valor.substring(0, 2) + "." + valor.substring(2, 5) + "." + valor.substring(5, 8) + "/" + valor.substring(8);
                        } else if (valor.length > 5) {
                            e.target.value = valor.substring(0, 2) + "." + valor.substring(2, 5) + "." + valor.substring(5);
                        } else if (valor.length > 2) {
                            e.target.value = valor.substring(0, 2) + "." + valor.substring(2);
                        } else {
                            e.target.value = valor;
                        }
                    });
                }
            });
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
                <form method="post" action="salvar_produto.php" enctype="multipart/form-data">
                    <label>Nome do Produto:</label>
                    <input type="text" name="nome" required maxlength="100"><br><br>

                    <label>Preço:</label>
                    <input type="text" id="CampPreco" name="preco" placeholder="0,00" required maxlength="15"><br><br>

                    <label>Categoria:</label>
                    <select id="CampCategoria" name="categoria" required>
                        <option value="" disabled selected>Selecione</option>
                        <option value="Contas de Streaming">Contas de Streaming</option>
                        <option value="Gift Cards">Gift Cards</option>
                        <option value="Itens Digitais em Jogos">Itens Digitais em Jogos</option>
                        <option value="Contas de Jogos">Contas de Jogos</option>
                        <option value="Jogos Digitais ou Mídia Física">Jogos Digitais ou Mídia Física</option>
                        <option value="Keys de Jogos">Keys de Jogos</option>
                        <option value="Outros">Outros</option>
                    </select><br><br>

                    <label>Quantidade em Estoque:</label>
                    <input type="number" name="quantidade" required min="1" max="9999"><br><br>

                    <label>Data de Publicação:</label>
                    <input type="text" id="CampData_pub" name="data_pub" placeholder="dd/mm/aaaa" required maxlength="10"><br><br>

                    <label>Descrição do Produto:</label>
                    <textarea name="descricao" rows="4" required maxlength="500"></textarea><br><br>

                    <label>Imagem do produto:</label>
                    <input type="file" name="imagem" accept="image/*" required><br><br>

                    <input type="hidden" name="idvendedor" value="<?php echo $vendedor_id; ?>">

                    <button type="submit" class="btn">Salvar Produto</button>
                </form>
            </div>

            <!-- Lista de Produtos -->
            <table class="produtos-tabela">
                <tr>
                    <th>Imagem</th>
                    <th>Nome do produto</th>
                    <th>Preço</th>
                    <th>Categoria</th>
                    <th>Estoque</th>
                    <th>Data Pub.</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($produtos as $p): ?>
                <tr style="text-align:center; vertical-align:middle;">
                    <td style="text-align:center;">
                        <?php
                        // Busca a imagem do produto
                        $stmtImg = $pdo->prepare("SELECT imagem FROM imagens WHERE idproduto = ? LIMIT 1");
                        $stmtImg->execute([$p['idproduto']]);
                        $img = $stmtImg->fetch(PDO::FETCH_ASSOC);
                        if ($img && !empty($img['imagem'])) {
                            echo '<img src="data:image/jpeg;base64,' . base64_encode($img['imagem']) . '" alt="Imagem do Produto" style="width:60px; height:60px; object-fit:cover; border-radius:8px;">';
                        } else {
                            echo '<img src="https://via.placeholder.com/60x60?text=Sem+Imagem" alt="Sem Imagem" style="width:60px; height:60px; object-fit:cover; border-radius:8px;">';
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($p['nome']); ?></td>
                    <td>R$ <?php echo number_format($p['preco'],2,",","."); ?></td>
                    <td><?php echo htmlspecialchars($p['categoria']); ?></td>
                    <td><?php echo $p['quantidade_estoque']; ?></td>
                    <td><?php echo date("d/m/Y", strtotime($p['data_pub'])); ?></td>
                    <td style="text-align:center; white-space:normal; word-wrap:break-word;">
                        <?php echo htmlspecialchars($p['descricao']); ?>
                    </td>
                    <td>
                    
                        <a href="editar_produto.php?id=<?php echo $p['idproduto']; ?>" class="btn">Editar</a>

                        <form method="post" action="excluir_produto.php" style="display:inline;">
                            <input type="hidden" name="idproduto" value="<?php echo $p['idproduto']; ?>">
                            <button type="submit" class="btn" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
                <style>
                .produtos-tabela th, .produtos-tabela td {
                    text-align: center;
                    vertical-align: middle;
                    white-space: normal;
                    word-wrap: break-word;
                }
                .produtos-tabela td {
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    max-width: 180px;
                }
                .produtos-tabela td img {
                    display: block;
                    margin: 0 auto;
                }
                </style>
        </div>

        <!-- Meu Perfil -->
        <div id="perfil" class="tab-content">
            <div class="perfil-container">
                <h2 class="perfil-titulo">Meu Perfil</h2>

                <!-- Foto de Perfil -->
                <div class="perfil-imagem">
                    <?php if (!empty($foto_de_perfil)): ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($foto_de_perfil) ?>" alt="Foto de Perfil" class="perfil-imagem-display">
                    <?php else: ?>
                        <img src="https://i.pinimg.com/736x/9f/4c/f0/9f4cf0f24b376077a2fcdab2e85c3584.jpg" alt="Foto de Perfil Padrão" class="perfil-imagem-display">
                    <?php endif; ?>
                </div>

                <!-- Formulário de edição -->
                <form method="post" action="atualizar_vendedor.php" enctype="multipart/form-data" class="perfil-form">
                    <label>Nome:</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($vendedor['nome']); ?>">

                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($vendedor['email']); ?>">

                    <label>Telefone:</label>
                    <input type="text" id="CampTelefone" name="telefone" maxlength="15" value="<?php echo htmlspecialchars($vendedor['telefone'] ?? ''); ?>">

                    <label>CPF:</label>
                    <input type="text" id="CampCPF" name="cpf" maxlength="14" value="<?php echo htmlspecialchars($vendedor['cpf'] ?? ''); ?>">

                    <label>CNPJ:</label>
                    <input type="text" id="CampCNPJ" name="cnpj" maxlength="18" value="<?php echo htmlspecialchars($vendedor['cnpj'] ?? ''); ?>">

                    <label>Sua foto de preferência:</label>
                    <input type="file" name="foto" accept="image/*"><br><br>

                    <button type="submit">Salvar Alterações</button>

                    <div class="botoes-inicio">
                        <a href="pagina_vendedor" class="btn-primario">Retornar a Página Inicial</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <p>Todos os Direitos Reservados - MaxAcess © 2025</p>
    </footer>
</body>
</html>
