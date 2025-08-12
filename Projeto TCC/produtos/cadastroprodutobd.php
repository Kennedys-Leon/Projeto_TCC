<?php
include '../vendedor/conexao2.php';

$nome = $_POST['nome'];
$preco = $_POST['preco'];
$categoria = $_POST['categoria'];
$data_pub = $_POST['data_pub'];
$quantidade_estoque = $_POST['quantidade_estoque'];

try{
$sql = "INSERT INTO produto (nome, preco, categoria, quantidade_estoque) VALUES ('$nome', '$preco', '$categoria', '$data_pub', '$quantidade_estoque')";
$conn -> exec($sql);
    echo "Cadastro realizado com sucesso!";
    header("Location: ../index.php");
    
} catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>