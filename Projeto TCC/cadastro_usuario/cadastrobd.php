<?php
include '../conexao.php';

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$cep = $_POST['cep'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$senha = $_POST['senha'];

if ($stmt->fetchColumn() > 0) {
    echo "<script>alert('E-mail já cadastrado!'); window.history.back();</script>";
    exit();
}

$foto_de_perfil = null;
    if (isset($_FILES['foto_de_perfil']) && $_FILES['foto_de_perfil']['error'] == 0) {
    $foto_de_perfil = file_get_contents($_FILES['foto_de_perfil']['tmp_name']);
}

try {
    if (move_uploaded_file($foto_tmp, $caminho_destino)) {
        $sql = "INSERT INTO usuario (nome, cpf, cep, telefone, email, senha, foto_de_perfil) 
                VALUES (:nome, :cpf, :cep, :telefone, :email, :senha, :foto_de_perfil)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':foto_de_perfil', $foto_nome);
        $stmt->execute();

        header("Location: ../login/login.php");
        exit();
    } else {
        echo "Erro ao fazer upload da foto.";
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

$conn = null;
?>
