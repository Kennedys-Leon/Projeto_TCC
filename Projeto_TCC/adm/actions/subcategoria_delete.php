<?php
require '../../conexao.php';

if (!isset($_POST['id'])) {
    die("ID inválido.");
}

$id = $_POST['id'];

$sql = $pdo->prepare("DELETE FROM subcategorias WHERE id = ?");
$sql->execute([$id]);

header("Location: ../subcategorias.php");
exit;
