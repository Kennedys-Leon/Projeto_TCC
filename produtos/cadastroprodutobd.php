<?php
include '../vendedor/conexao2.php';

$nome = $_POST['nome'];
$preco = $_POST['preco'];
$categoria = $_POST['categoria'];
$quantidade_estoque = $_POST['quantidade_estoque'];

try{
$sql = "INSERT INTO produto (nome, preco, categoria, quantidade_estoque) VALUES ('$nome', '$preco', '$categoria', '$quantidade_estoque')";
$conn -> exec($sql);
    echo "Cadastro realizado com sucesso!";
    header("Location: ../index.php");
    
} catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>