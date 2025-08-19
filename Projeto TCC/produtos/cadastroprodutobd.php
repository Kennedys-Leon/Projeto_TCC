<?php
include '../cadastro/conexao.php';

$nome = $_POST['nome'];
$preco = $_POST['preco'];
$categoria = $_POST['categoria'];
$quantidade_estoque = $_POST['quantidade_estoque'];
$data_pub = $_POST['data_pub'];
$descricao = $_POST['descricao'];


try{
$sql = "INSERT INTO produto (nome, preco, quantidade_estoque, categoria, descricao ) VALUES ('$nome', '$preco', '$categoria', '$quantidade_estoque', '$data_pub', '$descricao')";
$conn -> exec($sql);
    echo "Cadastro realizado com sucesso!";
    header("Location: ../index.php");
    
} catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>