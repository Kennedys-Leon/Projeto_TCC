<?php
require '../../conexao.php';

if (!isset($_POST['idvendedor'])) {
    die("ID inválido.");
}

$id = $_POST['idvendedor'];

// Alternar ativo/desativado
$sql = $pdo->prepare("UPDATE vendedor SET ativo = NOT ativo, status_conta = IF(ativo = 1, 'desativado', 'ativo') WHERE idvendedor = ?");
$sql->execute([$id]);

header("Location: ../vendedores.php");
exit;
