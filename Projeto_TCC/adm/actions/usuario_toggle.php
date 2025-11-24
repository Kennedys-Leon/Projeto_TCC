<?php
require '../../conexao.php';

if (!isset($_POST['idusuario'])) {
    die("ID inválido.");
}

$id = $_POST['idusuario'];

// Alternar ativo/desativado
$sql = $pdo->prepare("
    UPDATE usuario 
    SET ativo = NOT ativo, 
        status_conta = IF(ativo = 1, 'desativado', 'ativo') 
    WHERE idusuario = ?
");
$sql->execute([$id]);

header("Location: ../usuarios.php");
exit;
