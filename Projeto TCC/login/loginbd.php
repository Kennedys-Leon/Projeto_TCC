<?php
session_start();
include '../cadastro/conexao.php';

$email = $_POST['CampEmail'];
$senha = $_POST['CampSenha'];

if (empty($email) || empty($senha)) {
    echo "Todos os campos são obrigatórios.";
    exit();
}

$sql = "SELECT * FROM login WHERE email = :email AND senha = :senha";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':senha', $senha);
//$stmt->bind_param('sss', $email, $senha);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['email'] = $email;
    
} else {
    echo "Email ou senha incorretos, ou tipo de conta inválido.";
}

$stmt->close();
$conn->close();
?>
