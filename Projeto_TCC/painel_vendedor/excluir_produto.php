<?php
session_start();
include '../conexao.php';

// Verifica se vendedor está logado
if (!isset($_SESSION['vendedor_logado'])) {
    header("Location: ../login_vendedor/login_vendedor.php");
    exit;
}

if (isset($_POST['idproduto'])) {
    $idproduto = intval($_POST['idproduto']);
    $vendedor_id = $_SESSION['vendedor_logado'];

    // Garante que o produto pertence ao vendedor logado
    $stmt = $pdo->prepare("SELECT * FROM produto WHERE idproduto = ? AND idvendedor = ?");
    $stmt->execute([$idproduto, $vendedor_id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto) {
        // Exclui imagens relacionadas ao produto
        $stmt = $pdo->prepare("DELETE FROM imagens WHERE idproduto = ?");
        $stmt->execute([$idproduto]);

        // Agora exclui o produto
        $stmt = $pdo->prepare("DELETE FROM produto WHERE idproduto = ?");
        $stmt->execute([$idproduto]);
        header("Location: painel_vendedor.php?msg=excluido");
        exit;
    } else {
        header("Location: painel_vendedor.php?msg=erro");
        exit;
    }
} else {
    header("Location: painel_vendedor.php");
    exit;
}
