<?php
session_start();
include '../conexao.php';

// ============================
// Verifica se o vendedor está logado
// ============================
if (!isset($_SESSION['vendedor_logado'])) {
    header("Location: ../login_vendedor/login_vendedor.php");
    exit;
}

$idvendedor = $_SESSION['vendedor_logado'];
$idproduto  = $_GET['id'] ?? null;

// ============================
// Busca o produto pertencente ao vendedor logado
// ============================
$stmt = $pdo->prepare("SELECT * FROM produto WHERE idproduto = ? AND idvendedor = ?");
$stmt->execute([$idproduto, $idvendedor]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado ou você não tem permissão para editá-lo.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="../css/cadastro.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f2f2f2;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 500px;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }
        .form-container input,
        .form-container textarea,
        .form-container select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .form-container button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .form-container button:hover {
            background: #45a049;
        }
        .preview {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }
        .preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Editar Produto</h2>
    <form method="post" action="atualizar_produto.php" enctype="multipart/form-data">
        <input type="hidden" name="idproduto" value="<?php echo $produto['idproduto']; ?>">

        <label for="nome">Nome do Produto</label>
        <input type="text" id="nome" name="nome" maxlength="100"
               value="<?php echo htmlspecialchars($produto['nome']); ?>" required>

        <label for="preco">Preço</label>
        <input type="text" id="preco" name="preco" maxlength="15"
               value="<?php echo number_format($produto['preco'], 2, ',', '.'); ?>" required>

        <label for="quantidade">Quantidade em Estoque</label>
        <input type="number" id="quantidade" name="quantidade" min="1" max="9999"
               value="<?php echo $produto['quantidade_estoque']; ?>" required>

        <label for="descricao">Descrição</label>
        <textarea id="descricao" name="descricao" maxlength="500" rows="5" required><?php echo htmlspecialchars($produto['descricao']); ?></textarea>

        <label for="categoria">Categoria</label>
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

        <div class="preview">
            <?php
            // Mostra imagem atual do produto
            $stmtImg = $pdo->prepare("SELECT imagem FROM imagens WHERE idproduto = ? LIMIT 1");
            $stmtImg->execute([$produto['idproduto']]);
            $img = $stmtImg->fetch(PDO::FETCH_ASSOC);

            if ($img && !empty($img['imagem'])) {
                echo '<img id="imgPreview" src="data:image/jpeg;base64,' . base64_encode($img['imagem']) . '" alt="Imagem atual">';
            } else {
                echo '<img id="imgPreview" src="https://via.placeholder.com/100x100?text=Sem+Imagem" alt="Sem imagem">';
            }
            ?>
        </div>

        <label for="imagem">Nova Imagem (opcional)</label>
        <input type="file" id="imagem" name="imagem" accept="image/*" onchange="previewImagem(event)">

        <button type="submit">Salvar Alterações</button>
    </form>
</div>

<script>
// Mascara de preço
const precoInput = document.getElementById('preco');
precoInput.addEventListener('input', () => {
    let valor = precoInput.value.replace(/\D/g, '');
    valor = (valor / 100).toFixed(2) + '';
    valor = valor.replace(".", ",");
    valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    precoInput.value = valor;
});

// Preview de imagem
function previewImagem(event) {
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('imgPreview').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>
</body>
</html>
