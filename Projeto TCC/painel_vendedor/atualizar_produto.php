
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

// ============================
// Coleta e valida dados do formulário
// ============================
$idproduto   = $_POST['idproduto'] ?? null;
$nome        = trim($_POST['nome'] ?? '');
$preco       = $_POST['preco'] ?? '';
$categoria   = $_POST['categoria'] ?? '';
$quantidade  = $_POST['quantidade'] ?? 0;
$descricao   = trim($_POST['descricao'] ?? '');

if (!$idproduto || empty($nome) || empty($preco) || empty($categoria) || $quantidade <= 0) {
    die("Preencha todos os campos obrigatórios.");
}

// ============================
// Verifica se o produto pertence ao vendedor logado
// ============================
$stmt = $pdo->prepare("SELECT idproduto FROM produto WHERE idproduto = ? AND idvendedor = ?");
$stmt->execute([$idproduto, $idvendedor]);
if (!$stmt->fetch()) {
    die("Produto não encontrado ou sem permissão para editar.");
}

// ============================
// Converte o preço para formato de banco
// ============================
$preco = str_replace(['.', ','], ['', '.'], $preco);

// ============================
// Atualiza os dados principais do produto
// ============================
$stmt = $pdo->prepare("UPDATE produto 
                       SET nome = ?, preco = ?, categoria = ?, quantidade_estoque = ?, descricao = ? 
                       WHERE idproduto = ?");
$stmt->execute([$nome, $preco, $categoria, $quantidade, $descricao, $idproduto]);

// ============================
// Atualiza a imagem (se enviada)
// ============================
if (!empty($_FILES['imagem']['tmp_name'])) {
    $imgData = file_get_contents($_FILES['imagem']['tmp_name']);

    // Verifica se já existe imagem para este produto
    $stmtImg = $pdo->prepare("SELECT idimagens FROM imagens WHERE idproduto = ?");
    $stmtImg->execute([$idproduto]);
    $imagemExistente = $stmtImg->fetch(PDO::FETCH_ASSOC);

    if ($imagemExistente) {
        // Atualiza imagem existente
        $stmt = $pdo->prepare("UPDATE imagens SET imagem = ? WHERE idproduto = ?");
        $stmt->execute([$imgData, $idproduto]);
    } else {
        // Insere nova imagem se ainda não existir
        $stmt = $pdo->prepare("INSERT INTO imagens (idproduto, imagem) VALUES (?, ?)");
        $stmt->execute([$idproduto, $imgData]);
    }
}

// ============================
// Redireciona de volta para o painel com sucesso
// ============================
header("Location: painel_vendedor.php?msg=Produto atualizado com sucesso");
exit;
?>
