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

// === lógica de participação baseada em níveis (colocar após cálculo de $total_vendas e $todas_vendas) ===

// Busca o nível atual baseado no número de vendas do vendedor
$stmt = $pdo->prepare("
    SELECT idparticipacao_percentual, percentual, min_vendas
    FROM participacao_percentual
    WHERE min_vendas <= ?
    ORDER BY min_vendas DESC
    LIMIT 1
");
$stmt->execute([$total_vendas]);
$nivel = $stmt->fetch(PDO::FETCH_ASSOC);

if ($nivel) {
    $id_participacao = (int)$nivel['idparticipacao_percentual'];
    $percentual = (float)$nivel['percentual'];
    $nivel_min_vendas = (int)$nivel['min_vendas'];
} else {
    // fallback seguro
    $id_participacao = 1;
    $percentual = 30.00;
    $nivel_min_vendas = 0;
}

// Atualiza a tabela vendedor somente se for diferente (evita updates desnecessários)
$update = $pdo->prepare("UPDATE vendedor SET idparticipacao_percentual = ? WHERE idvendedor = ? AND (idparticipacao_percentual IS NULL OR idparticipacao_percentual <> ?)");
$update->execute([$id_participacao, $vendedor_id, $id_participacao]);

// Busca a próxima meta (se houver)
$stmt = $pdo->prepare("
    SELECT min_vendas, percentual
    FROM participacao_percentual
    WHERE min_vendas > ?
    ORDER BY min_vendas ASC
    LIMIT 1
");
$stmt->execute([$nivel_min_vendas]);
$proxima = $stmt->fetch(PDO::FETCH_ASSOC);

// Calcula progresso percentual até a próxima meta
$progresso = 100;
$meta_vendas = null;
$vendas_restantes = 0;
if ($proxima) {
    $meta_vendas = (int)$proxima['min_vendas'];
    $distancia = $meta_vendas - $nivel_min_vendas;
    $atingido = $total_vendas - $nivel_min_vendas;
    if ($distancia > 0) {
        $progresso = min(100, max(0, ($atingido / $distancia) * 100));
    } else {
        $progresso = 100;
    }
    $vendas_restantes = max(0, $meta_vendas - $total_vendas);
}

// (opcional) métrica antiga: participação relativa ao total de vendas do site
$participacao_site = ($todas_vendas == 0) ? 0.0 : ($total_vendas / $todas_vendas) * 100.0;


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
    <link rel="stylesheet" href="../css/cart.css">

    <!-- Estilo da sidebar (só a lateral) -->
    <link rel="stylesheet" href="../css/sidebar.css">

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

        /* Styling for the product registration form */
        .form-card {
            background: #2E3B4E;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #fff;
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #555;
            border-radius: 8px;
            background: #1d1932;
            color: #fff;
            font-size: 14px;
            transition: border-color 0.3s, box-shadow 0.3s;
            box-sizing: border-box;
        }

        .form-input:focus {
            border-color: #00c9ff;
            box-shadow: 0 0 8px rgba(0,201,255,0.5);
            outline: none;
        }

        textarea.form-input {
            resize: vertical;
            min-height: 80px;
        }

        select.form-input {
            /* Inherits from .form-input */
        }

        input[type="file"].form-input {
            padding: 5px;
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

                // Modal for Product Image
                const openModalBtnProduto = document.getElementById("openModalBtnProduto");
                const fileModalProduto = document.getElementById("fileModalProduto");
                const closeModalBtnProduto = document.getElementById("closeModalBtnProduto");
                const dropAreaProduto = document.getElementById("dropAreaProduto");
                const modalFileInputProduto = document.getElementById("modalFileInputProduto");
                const mainFileInputProduto = document.getElementById("imagem_produto");
                const modalPreviewProduto = document.getElementById("modalPreviewProduto");
                const mainPreviewProduto = document.getElementById("previewProduto");

                openModalBtnProduto.addEventListener("click", () => {
                    fileModalProduto.style.display = "flex";
                });

                closeModalBtnProduto.addEventListener("click", () => {
                    fileModalProduto.style.display = "none";
                });

                // Clicking drop area triggers file input click
                dropAreaProduto.addEventListener("click", () => {
                    modalFileInputProduto.click();
                });

                // Handle file selection in modal
                modalFileInputProduto.addEventListener("change", (event) => {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            modalPreviewProduto.src = e.target.result;
                            modalPreviewProduto.style.display = "block";
                            mainPreviewProduto.src = e.target.result;
                            mainPreviewProduto.style.display = "block";
                            mainFileInputProduto.files = modalFileInputProduto.files;
                        }
                        reader.readAsDataURL(file);
                    }
                });

                // Drag and drop in modal
                dropAreaProduto.addEventListener("dragover", (event) => {
                    event.preventDefault();
                    dropAreaProduto.style.borderColor = "#00c9ff";
                });

                dropAreaProduto.addEventListener("dragleave", (event) => {
                    event.preventDefault();
                    dropAreaProduto.style.borderColor = "#9d9dfc";
                });

                dropAreaProduto.addEventListener("drop", (event) => {
                    event.preventDefault();
                    dropAreaProduto.style.borderColor = "#9d9dfc";
                    if (event.dataTransfer.files.length > 0) {
                        modalFileInputProduto.files = event.dataTransfer.files;
                        const changeEvent = new Event('change');
                        modalFileInputProduto.dispatchEvent(changeEvent);
                    }
                });
            });
        </script>
        
    </head>
<body>

    <header>
        <h1>MaxAcess - Painel do Vendedor</h1>
        <p>Bem-vindo, <?php echo htmlspecialchars($vendedor['nome']); ?>!</p>
        
        <button class="menu-toggle" id="menu-toggle" aria-label="Abrir menu">☰</button>
    </header>

    <aside class="sidebar" id="sidebar" aria-hidden="true">
        <button class="close-btn" id="close-sidebar" aria-label="Fechar menu">&times;</button>
        <ul>

        <li><a href="pagina_vendedor.php"><img src="../img/casa.png" alt="Início" style="width:16px; height:16px; vertical-align:middle;"> Início</a></li>


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
    
    <div class="container">
        <!-- Abas -->
        <div class="tabs">
            <div class="tab active" data-tab="vendas" onclick="openTab('vendas')">Resumo de Vendas</div>
            <div class="tab" data-tab="produtos" onclick="openTab('produtos')">Meus Produtos</div>
            <div class="tab" data-tab="perfil" onclick="openTab('perfil')">Minhas Informações</div>
        </div>

        <!-- Resumo de Vendas -->
        <div id="vendas" class="tab-content active">
            <h2>Resumo de Vendas</h2>
            <p>Total de vendas realizadas: <b><?php echo $total_vendas; ?></b></p>
            <p>Total do site: <b><?php echo $todas_vendas; ?></b></p>
            <!-- Exibe participação fixa por níveis -->
            <p class="percentual">Participação: <strong><?php echo number_format($percentual, 2, ',', '.'); ?>%</strong></p>

            <?php if ($meta_vendas !== null): ?>
                <p>Meta seguinte: <?php echo $meta_vendas; ?> vendas (faltam <?php echo $vendas_restantes; ?>)</p>
                <progress value="<?php echo round($progresso, 2); ?>" max="100" style="width:100%; height:18px;"></progress>
                <p style="font-size:0.9rem; margin-top:6px;"><?php echo round($progresso, 2); ?>% até o próximo nível</p>
            <?php else: ?>
                <p>Você atingiu o nível máximo de participação.</p>
            <?php endif; ?>

            <!-- (Opcional) Exibir também participação relativa ao site -->
            <p style="margin-top:8px; font-size:0.9rem; color:#bbb;">
                Participação relativa ao total do site: <?php echo number_format($participacao_site, 2, ',', '.'); ?>%
            </p>
        </div>

        <!-- Produtos -->
        <div id="produtos" class="tab-content">
            <h2>Meus Produtos</h2>
            <button class="btn" onclick="toggleNovoProduto()">Cadastrar Novo Produto</button>
            
            <!-- Formulário de Novo Produto -->
            <div id="formNovoProduto" style="display:none; margin-top:15px;">
                <div class="form-card">
                    <form method="post" action="salvar_produto.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Nome do Produto:</label>
                            <input type="text" name="nome" class="form-input" required maxlength="100">
                        </div>

                        <div class="form-group">
                            <label>Preço:</label>
                            <input type="text" id="CampPreco" name="preco" class="form-input" placeholder="0,00" required maxlength="15">
                        </div>

                        <div class="form-group">
                            <label>Categoria:</label>
                            <select id="CampCategoria" name="categoria" class="form-input" required>
                                <option value="" disabled selected>Selecione</option>
                                <option value="Contas de Streaming">Contas de Streaming</option>
                                <option value="Gift Cards">Gift Cards</option>
                                <option value="Itens Digitais em Jogos">Itens Digitais em Jogos</option>
                                <option value="Contas de Jogos">Contas de Jogos</option>
                                <option value="Jogos Digitais ou Mídia Física">Jogos Digitais ou Mídia Física</option>
                                <option value="Keys de Jogos">Keys de Jogos</option>
                                <option value="Outros">Outros</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Quantidade em Estoque:</label>
                            <input type="number" name="quantidade" class="form-input" required min="1" max="9999">
                        </div>

                        <div class="form-group">
                            <label>Data de Publicação:</label>
                            <input type="text" id="CampData_pub" name="data_pub" class="form-input" placeholder="dd/mm/aaaa" required maxlength="10">
                        </div>

                        <div class="form-group">
                            <label>Descrição do Produto:</label>
                            <textarea name="descricao" class="form-input" rows="4" required maxlength="500"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Imagem do produto:</label>
                            <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 10px;">
                                <button type="button" id="openModalBtnProduto" class="btn" style="width: 200px;">Escolher arquivo</button>
                                <img id="previewProduto" src="#" alt="Pré-visualização da imagem do Produto" style="display:none; width: 150px; height: 150px; margin-top: 10px; border-radius: 8px; object-fit: cover;">
                            </div>
                            <input type="file" id="imagem_produto" name="imagem" accept="image/*" style="display:none;" required>
                        </div>

                        <input type="hidden" name="idvendedor" value="<?php echo $vendedor_id; ?>">

                        <button type="submit" class="btn">Salvar Produto</button>
                    </form>
                </div>
            </div>

            <!-- Modal for Product Image -->
            <div id="fileModalProduto" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 2000; justify-content: center; align-items: center;">
                <div style="background: #2a2a2aff; padding: 20px; border-radius: 10px; width: 400px; max-width: 90%; text-align: center; position: relative;">
                    <h3>Escolha ou arraste a imagem</h3>
                    <div id="dropAreaProduto" style="border: 2px dashed #9d9dfc; border-radius: 10px; padding: 30px; cursor: pointer;">
                        <p>Arraste a imagem aqui ou clique para escolher</p>
                        <input type="file" id="modalFileInputProduto" accept="image/*" style="display:none;">
                    </div>
                    <img id="modalPreviewProduto" src="../img/bobeira.jpg" alt="Pré-visualização da imagem" style="display:none; width: 50px; height: 50px; margin: 15px auto 0 auto; border-radius: 8px; object-fit: cover; display: block;">
                    <button id="closeModalBtnProduto" style="margin-top: 15px; background: #131318; color: #eaeaea; border: none; padding: 10px 20px; border-radius: 7px; cursor: pointer;">Fechar</button>
                </div>
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
    <script src="../script.js"></script>
</body>
</html>
