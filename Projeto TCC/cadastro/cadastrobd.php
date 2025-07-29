<?php
include 'conexao.php';

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$endereco = $_POST['endereco'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$senha = $_POST['senha'];

$foto = $_FILES['foto'];
$foto_nome = uniqid() . '_' . basename($foto['name']);
$foto_tmp = $foto['tmp_name'];
$caminho_destino = '../uploads/' . $foto_nome;

if (!is_dir('../uploads')) {
    mkdir('../uploads', 0755, true);
}

try {
    if (move_uploaded_file($foto_tmp, $caminho_destino)) {
        $sql = "INSERT INTO cadastro (nome, cpf, endereco, telefone, email, senha, foto) 
                VALUES (:nome, :cpf, :endereco, :telefone, :email, :senha, :foto)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':foto', $foto_nome);
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
