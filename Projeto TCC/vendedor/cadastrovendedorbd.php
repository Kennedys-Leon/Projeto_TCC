<?php
include 'conexao2.php';

$nome = $_POST['nome'];
$cpf = $_POST['CampCPF'];
$telefone = $_POST['CampTelefone'];
$email = $_POST['CampEmail'];
$senha = $_POST['CampSenha'];
$CNPJ = $_POST['CampCNPJ'];

try{
$sql = "INSERT INTO vendedor (nome, cpf, telefone, email, senha, CNPJ) VALUES ('$nome', '$cpf', '$telefone', '$email', '$senha', '$CNPJ')";
$conn -> exec($sql);
    echo "Insercao de cagao:";
    header("Location: ../loginV/LoginV.php");
    
} catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>
