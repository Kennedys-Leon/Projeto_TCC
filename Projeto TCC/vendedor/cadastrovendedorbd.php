<?php
include '../cadastro/conexao.php';

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

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto = $_FILES['foto'];
    $foto_nome = uniqid() . '_' . basename($foto['name']);
    $foto_tmp = $foto['tmp_name'];
    $caminho_destino = '../uploads/' . $foto_nome;

    if (!is_dir('../uploads')) {
        mkdir('../uploads', 0755, true);
    }

    if (!move_uploaded_file($foto_tmp, $caminho_destino)) {
        echo "Erro ao fazer upload da foto.";
        exit();
    }
} else {
    $foto_nome = null;
}

try {
    $sql = "INSERT INTO vendedor (nome, cpf, telefone, email, senha, cnpj, foto) 
            VALUES (:nome, :cpf, :telefone, :email, :senha, :cnpj, :foto)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':cnpj', $CNPJ);
    $stmt->bindParam(':foto', $foto_nome);
    $stmt->execute();

    header("Location: ../login_vendedor/login_vendedor.php");
    exit();
} catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$pdo = null;
?>