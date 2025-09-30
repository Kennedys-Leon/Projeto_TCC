<?php
session_start();
include '../conexao.php';

// Verifica se vendedor está logado
if (!isset($_SESSION['vendedor_logado'])) {
    header("Location: ../login_vendedor/login_vendedor.php");
    exit;
}

$vendedor_id = $_SESSION['vendedor_logado'];

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome        = trim($_POST['nome']);
    $preco       = str_replace(",", ".", preg_replace('/[^\d,]/', '', $_POST['preco'])); // limpa e ajusta preço
    $categoria   = $_POST['categoria'];
    $quantidade  = (int) $_POST['quantidade'];
    $data_pub    = DateTime::createFromFormat('d/m/Y', $_POST['data_pub']);
    $descricao   = trim($_POST['descricao']);

    if ($data_pub) {
        $data_pub = $data_pub->format('Y-m-d');
    } else {
        $data_pub = date('Y-m-d');
    }

    // Atualizar imagem apenas se foi enviada
    if (!empty($_FILES['imagem']['name'])) {
        $img = file_get_contents($_FILES['imagem']['tmp_name']);
        if ($img && !empty($img['imagem'])) {
            echo '<img src="data:image/jpeg;base64,' . base64_encode($img['imagem']) . '" alt="Imagem do Produto" style="width:60px; height:60px; object-fit:cover; border-radius:8px;">';
        } else {
            echo '<img src="https://via.placeholder.com/60x60?text=Sem+Imagem" alt="Sem Imagem" style="width:60px; height:60px; object-fit:cover; border-radius:8px;">';
        }
        $stmt = $pdo->prepare("UPDATE produto 
            SET nome = ?, preco = ?, categoria = ?, quantidade_estoque = ?, data_pub = ?, descricao = ?, imagem = ?
            WHERE idproduto = ? AND idvendedor = ?");
        $ok = $stmt->execute([$nome, $preco, $categoria, $quantidade, $data_pub, $descricao, $img, $vendedor_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE produto 
            SET nome = ?, preco = ?, categoria = ?, quantidade_estoque = ?, data_pub = ?, descricao = ?
            WHERE idproduto = ? AND idvendedor = ?");
        $ok = $stmt->execute([$nome, $preco, $categoria, $quantidade, $data_pub, $descricao, $vendedor_id]);
    }

    if ($ok) {
        header("Location: painel_vendedor.php?tab=produtos&msg=Produto atualizado com sucesso");
        exit;
    } else {
        echo "Erro ao atualizar produto!";
    }
} else {
    echo "Requisição inválida!";
}
