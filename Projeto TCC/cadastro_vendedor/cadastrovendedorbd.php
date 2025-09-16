<?php
include '../conexao.php';

$email = trim($_POST['email']);

// Verifica se já existe vendedor com o mesmo e-mail
$stmt = $pdo->prepare("SELECT COUNT(*) FROM vendedor WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();

if ($stmt->fetchColumn() > 0) {
    echo "<script>alert('E-mail já cadastrado!'); window.history.back();</script>";
    exit();
}

$nome     = trim($_POST['nome']);
$cpf      = trim($_POST['cpf']);
$telefone = trim($_POST['telefone']);
$senha    = $_POST['senha'];
$cnpj     = trim($_POST['cnpj']);

// Criptografa a senha antes de salvar
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Foto de perfil
$foto_de_perfil = null;
if (isset($_FILES['foto_de_perfil']) && $_FILES['foto_de_perfil']['error'] == 0) {
    $foto_de_perfil = file_get_contents($_FILES['foto_de_perfil']['tmp_name']);
}

try {
    $sql = "INSERT INTO vendedor 
            (nome, cpf, telefone, email, senha, cnpj, foto_de_perfil) 
            VALUES (:nome, :cpf, :telefone, :email, :senha, :cnpj, :foto_de_perfil)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senhaHash);
    $stmt->bindParam(':cnpj', $cnpj);
    $stmt->bindParam(':foto_de_perfil', $foto_de_perfil, PDO::PARAM_LOB);

    $stmt->execute();

    header("Location: ../login_vendedor/login_vendedor.php");
    exit();
} catch(PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

//github.com/gouveazs/PROJETO-TCC/blob/main/php/insercao/insercaoVendedor.php

$pdo = null;
?>




