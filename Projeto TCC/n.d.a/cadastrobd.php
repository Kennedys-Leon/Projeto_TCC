<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "cadastrodb";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$nome = $_POST['campNome'];
$cpf = $_POST['CampCPF'];
$endereco = $_POST['CampEndereco'];
$telefone = $_POST['CampTelefone'];
$email = $_POST['CampEmail'];
$senha = $_POST['CampSenha'];
$tipo_usuario = $_POST['tipo_usuario'];

$senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nome, cpf, endereco, telefone, email, senha, tipo_usuario) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $nome, $cpf, $endereco, $telefone, $email, $senhaCriptografada, $tipo_usuario);

if ($stmt->execute()) {
    echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href='login/login.php';</script>";
} else {
    echo "Erro: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
