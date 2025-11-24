<?php
require '../../conexao.php';

if (!isset($_POST['idusuario'])) {
    die("ID inválido.");
}

$id = $_POST['idusuario'];

// Excluir usuário
$sql = $pdo->prepare("DELETE FROM usuario WHERE idusuario = ?");
$sql->execute([$id]);

header("Location: ../usuarios.php");
exit;
