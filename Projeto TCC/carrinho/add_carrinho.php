<?php
session_start();
include 'conexao.php';

// ID do produto vindo via GET ou POST
$idproduto = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verifica login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: cadastro_usuario/cadastro.php");
    exit;
}

// Busca produto no banco
$stmt = $pdo->prepare("SELECT idproduto, nome, preco FROM produto WHERE idproduto = ?");
$stmt->execute([$idproduto]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    header("Location: index.php?erro=produto_nao_encontrado");
    exit;
}

// Cria o carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Adiciona produto (sem duplicar)
if (!isset($_SESSION['carrinho'][$idproduto])) {
    $_SESSION['carrinho'][$idproduto] = [
        'id' => $produto['idproduto'],
        'nome' => $produto['nome'],
        'preco' => $produto['preco'],
        'quantidade' => 1
    ];
} else {
    $_SESSION['carrinho'][$idproduto]['quantidade']++;
}

// Redireciona para o carrinho
header("Location: carrinho/checkout.php");
exit;
?>
