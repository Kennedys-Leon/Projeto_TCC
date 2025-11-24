<?php
require '../../conexao.php';

if (!isset($_POST['idvendedor'])) {
    die("ID inválido.");
}

$id = $_POST['idvendedor'];

// Excluir
$sql = $pdo->prepare("DELETE FROM vendedor WHERE idvendedor = ?");
$sql->execute([$id]);

header("Location: ../vendedores.php");
exit;
