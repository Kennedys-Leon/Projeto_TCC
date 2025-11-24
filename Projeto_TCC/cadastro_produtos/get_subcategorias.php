<?php
require '../conexao.php';

$id = intval($_GET['id']);

$sql = $pdo->prepare("SELECT id, nome FROM subcategorias WHERE id_categoria = ?");
$sql->execute([$id]);

echo json_encode($sql->fetchAll(PDO::FETCH_ASSOC));
