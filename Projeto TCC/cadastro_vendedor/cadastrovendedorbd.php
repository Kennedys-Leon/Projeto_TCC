<?php
include '../conexao.php';

$email = $_POST['email'];

$stmt = $pdo->prepare("SELECT COUNT(*) FROM vendedor WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();

if ($stmt->fetchColumn() > 0) {
    echo "<script>alert('E-mail já cadastrado!'); window.history.back();</script>";
    exit();
}

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$telefone = $_POST['telefone'];
$senha = $_POST['senha'];
$CNPJ = $_POST['cnpj'];

$foto_de_perfil = null;
if (isset($_FILES['foto_de_perfil']) && $_FILES['foto_de_perfil']['error'] == 0) {
    $foto_de_perfil = file_get_contents($_FILES['foto_de_perfil']['tmp_name']);
}

try {
    $sql = "INSERT INTO vendedor (nome, cpf, telefone, email, senha, cnpj, foto_de_perfil) 
            VALUES (:nome, :cpf, :telefone, :email, :senha, :cnpj, :foto_de_perfil)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':cnpj', $CNPJ);
    $stmt->bindParam(':foto_de_perfil', $foto_de_perfil, PDO::PARAM_LOB);
    
    $stmt->execute();

    header("Location: ../login_vendedor/login_vendedor.php");
    exit();
} catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}


//https://github.com/gouveazs/PROJETO-TCC/blob/main/php/insercao/insercaoVendedor.php

$pdo = null;
?>


