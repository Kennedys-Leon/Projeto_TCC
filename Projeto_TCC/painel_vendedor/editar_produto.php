<?php
session_start();
include '../conexao.php';

// verifica login do vendedor
if (!isset($_SESSION['vendedor_logado'])) {
    header("Location: ../login_vendedor/login_vendedor.php");
    exit;
}

$idvendedor = $_SESSION['vendedor_logado'];
$idproduto  = $_GET['id'] ?? null;

// busca produto
$stmt = $pdo->prepare("SELECT * FROM produto WHERE idproduto = ? AND idvendedor = ?");
$stmt->execute([$idproduto, $idvendedor]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$produto) {
    die("Produto não encontrado ou sem permissão.");
}

// busca imagem atual
$stmtImg = $pdo->prepare("SELECT imagem FROM imagens WHERE idproduto = ? LIMIT 1");
$stmtImg->execute([$produto['idproduto']]);
$img = $stmtImg->fetch(PDO::FETCH_ASSOC);
$imgSrc = ($img && !empty($img['imagem'])) ? 'data:image/jpeg;base64,' . base64_encode($img['imagem']) : 'https://via.placeholder.com/140x140?text=Sem+Imagem';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="../css/cadastro.css">
    <style>
        :root{
            --bg:#0f3a4a; /* página */
            --card:#2f3b46; /* cartão */
            --card-inn:#25313a; /* inner */
            --input:#1f2830; /* input bg */
            --accent:#4b6b8b; /* botões */
            --text:#dfeaf0;
            --muted:#b7c2c8;
        }
        body{
            margin:0;
            font-family: 'Poppins',sans-serif;
            background: linear-gradient(180deg,#063646 0%, #0f3a4a 100%);
            color:var(--text);
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:32px;
        }

        .card {
            width:100%;
            max-width:1100px;
            background: linear-gradient(180deg,var(--card) 0%, var(--card-inn) 100%);
            border-radius:14px;
            box-shadow:0 10px 30px rgba(2,10,20,0.6);
            display:flex;
            gap:28px;
            padding:28px;
            box-sizing:border-box;
        }

        /* coluna esquerda: preview + ações */
        .card .col-left{
            width:280px;
            padding:18px;
            display:flex;
            flex-direction:column;
            align-items:center;
            border-right:1px solid rgba(255,255,255,0.04);
        }

        .avatar {
            width:160px;
            height:160px;
            border-radius:50%;
            overflow:hidden;
            border:6px solid rgba(255,255,255,0.06);
            box-shadow:0 6px 16px rgba(0,0,0,0.6);
            margin-bottom:18px;
            background:#0b2732;
        }
        .avatar img{ width:100%; height:100%; object-fit:cover; display:block; }

        .left-actions { width:100%; display:flex; flex-direction:column; gap:12px; margin-top:6px; }

        /* botão de arquivo, salvar e voltar - mesma aparência e largura completa */
        .left-actions label.file-btn,
        .left-actions button.save-btn,
        .left-actions a.back-btn {
            display:inline-flex;
            width:100%;
            align-items:center;
            justify-content:center;
            padding:10px 14px;
            border-radius:8px;
            color:#fff;
            text-decoration:none;
            border:none;
            cursor:pointer;
            font-weight:600;
            box-sizing:border-box;
            background:#486b86; /* mesma cor para todos */
        }

        /* pequenos ajustes visuais */
        .left-actions label.file-btn { /* mantém mesma cor */ }
        .left-actions button.save-btn{ /* mantém mesma cor */ }
        .left-actions a.back-btn { /* agora idêntico aos outros */ }

        .left-actions label.file-btn:hover,
        .left-actions button.save-btn:hover,
        .left-actions a.back-btn:hover {
            filter: brightness(0.92);
            transform: translateY(-1px);
        }

        .left-actions input[type=file]{ display:none; }

        /* coluna direita: campos */
        .col-right{ flex:1; padding:6px 8px; display:flex; flex-direction:column; gap:10px; }

        .col-right h2{ text-align:center; margin:0 0 6px 0; font-size:20px; color:var(--text); }

        .field-row{ display:flex; gap:12px; width:100%; align-items:flex-start; }
        .field{ flex:1; display:flex; flex-direction:column; gap:8px; }
        label.field-label{
            font-weight:700;
            font-size:13px;
            color:var(--muted);
            margin-left:6px;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            background:var(--input);
            border:1px solid rgba(255,255,255,0.04);
            color:var(--text);
            padding:12px 14px;
            border-radius:8px;
            font-size:14px;
            outline:none;
            box-shadow: inset 0 -2px 6px rgba(0,0,0,0.4);
        }
        textarea{ min-height:120px; resize:vertical; }

        .row-two{ display:flex; gap:12px; }
        .row-two .field{ flex:1; }

        .actions-bottom{ display:flex; gap:12px; justify-content:center; margin-top:6px; }
        .actions-bottom button {
            padding:10px 16px;
            border-radius:8px;
            border:none;
            cursor:pointer;
            background:#5a7aa0;
            color:white;
            font-weight:700;
        }

        /* garantir que input date tenha mesmo visual dos outros inputs */
        input[type="date"] {
            background: var(--input);
            border: 1px solid rgba(255,255,255,0.04);
            color: var(--text);
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            box-shadow: inset 0 -2px 6px rgba(0,0,0,0.4);
            -webkit-appearance: none;
            appearance: none;
            height: 44px;
        }

        /* ícone do calendário (WebKit) */
        input[type="date"]::-webkit-clear-button,
        input[type="date"]::-webkit-inner-spin-button {
            display: none;
        }
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1) brightness(1.2); /* deixa o ícone visível em temas escuros */
            opacity: 0.9;
            cursor: pointer;
            margin-right: 6px;
        }

        /* Firefox: forçar padronização visual */
        input[type="date"]::-moz-focus-inner { border: 0; }

        /* responsivo */
        @media(max-width:900px){
            .card{ flex-direction:column; padding:18px; max-width:640px; }
            .card .col-left{ width:100%; flex-direction:row; gap:12px; align-items:center; border-right:none; padding:12px 6px; }
            .avatar{ width:96px; height:96px; }
            .left-actions{ flex:1; margin-top:0; }
        }
    </style>
</head>
<body>
    <div class="card" role="main" aria-labelledby="editarTitle">
        <div class="col-left" aria-hidden="false">
            <div class="avatar" id="imgWrap">
                <img id="imgPreview" src="<?php echo $imgSrc; ?>" alt="Imagem do produto">
            </div>

            <div class="left-actions" aria-hidden="false">
                <label class="file-btn" for="imagem">Selecione uma foto</label>
                <input id="imagem" name="imagem" type="file" accept="image/*" onchange="previewImagem(event)">
                <a class="back-btn" href="painel_vendedor.php">Retornar à Página Inicial</a>
            </div>
        </div>

        <div class="col-right">
            <h2 id="editarTitle">Editar Produto</h2>

            <form method="post" action="atualizar_produto.php" enctype="multipart/form-data" id="editarForm">
                <input type="hidden" name="idproduto" value="<?php echo $produto['idproduto']; ?>">

                <div class="field">
                    <label class="field-label" for="nome">Nome do Produto</label>
                    <input type="text" id="nome" name="nome" maxlength="100" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
                </div>

                <br>

                <div class="field-row">
                    <div class="field">
                        <label class="field-label" for="preco">Preço</label>
                        <input type="text" id="preco" name="preco" maxlength="15" value="<?php echo number_format($produto['preco'], 2, ',', '.'); ?>" required>
                    </div>

                    <div class="field">
                        <label class="field-label" for="quantidade">Quantidade em Estoque</label>
                        <input type="number" id="quantidade" name="quantidade" min="1" max="9999" value="<?php echo $produto['quantidade_estoque']; ?>" required>
                    </div>

                    <div class="field">
                        <label class="field-label" for="data_pub">Data de Publicação</label>
                        <input type="date" id="data_pub" name="data_pub" value="<?php echo !empty($produto['data_pub']) ? date('Y-m-d', strtotime($produto['data_pub'])) : ''; ?>">
                    </div>
                </div>
                <br>
                    <div class="field">
                    <label class="field-label" for="categoria">Categoria</label>
                    <select id="categoria" name="categoria" required>
                        <option value="">Selecione uma categoria</option>
                        <option value="Contas de Streaming" <?php echo ($produto['categoria'] === 'Contas de Streaming') ? 'selected' : ''; ?>>Contas de Streaming</option>
                        <option value="Gift Cards" <?php echo ($produto['categoria'] === 'Gift Cards') ? 'selected' : ''; ?>>Gift Cards</option>
                        <option value="Itens Digitais em Jogos" <?php echo ($produto['categoria'] === 'Itens Digitais em Jogos') ? 'selected' : ''; ?>>Itens Digitais em Jogos</option>
                        <option value="Contas de Jogos" <?php echo ($produto['categoria'] === 'Contas de Jogos') ? 'selected' : ''; ?>>Contas de Jogos</option>
                        <option value="Jogos Digitais ou Mídia Física" <?php echo ($produto['categoria'] === 'Jogos Digitais ou Mídia Física') ? 'selected' : ''; ?>>Jogos Digitais ou Mídia Física</option>
                        <option value="Keys de Jogos" <?php echo ($produto['categoria'] === 'Keys de Jogos') ? 'selected' : ''; ?>>Keys de Jogos</option>
                        <option value="Outros" <?php echo ($produto['categoria'] === 'Outros') ? 'selected' : ''; ?>>Outros</option>
                    </select>
                </div>
                <br>
                <div class="field">
                    <label class="field-label" for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao" maxlength="500" required><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
                </div>

                

                <div class="row-two" style="margin-top:6px;">
                    <!-- preview já exibido na esquerda, mas mantemos espaço para compatibilidade -->
                    <div style="flex:1"></div>
                    <div style="flex:1"></div>
                </div>

                <div class="actions-bottom">
                    <button type="submit">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

<script>
// máscara de preço (mantém comportamento anterior)
const precoInput = document.getElementById('preco');
if (precoInput) {
    precoInput.addEventListener('input', () => {
        let valor = precoInput.value.replace(/\D/g, '');
        valor = (valor / 100).toFixed(2) + '';
        valor = valor.replace(".", ",");
        valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        precoInput.value = valor;
    });
}

// preview de imagem (usa input fora do form por causa do layout)
function previewImagem(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e){
        const img = document.getElementById('imgPreview');
        if (img) img.src = e.target.result;
    }
    reader.readAsDataURL(file);
}

// habilita clique no label para abrir file dialog
document.querySelectorAll('label.file-btn').forEach(l => {
    const input = document.getElementById('imagem');
    l.addEventListener('click', () => input.click());
});
</script>
</body>
</html>
