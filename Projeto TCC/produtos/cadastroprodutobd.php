<?php
include '../cadastro/conexao.php';

$nome = $_POST['nome'];
$preco = $_POST['preco'];
$categoria = $_POST['categoria'];
$quantidade_estoque = $_POST['quantidade_estoque'];
$data_pub = $_POST['data_pub'];
$descricao = $_POST['descricao'];

$data_formatada = DateTime::createFromFormat('dmY', $data_pub);
if ($data_formatada) {
    $data_pub = $data_formatada->format('Y-m-d');
} else {
    $data_pub = null; 
}

try {
    $sql = "INSERT INTO produto (nome, preco, quantidade_estoque, categoria, data_pub, descricao) 
            VALUES ('$nome', '$preco', '$quantidade_estoque', '$categoria', '$data_pub', '$descricao')";
    $pdo->exec($sql);

    echo "Cadastro realizado com sucesso!";
    header("Location: ../index.php");
    exit;

} catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$pdo = null;
?>
