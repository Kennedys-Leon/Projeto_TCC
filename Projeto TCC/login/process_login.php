<?php
include '../cadastro/conexao.php';

$nome = trim(htmlspecialchars($_POST['nome']));
$email = trim(htmlspecialchars($_POST['email']));
$senha = trim(htmlspecialchars($_POST['senha']));

try {
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE nome = ? AND email = ? AND senha = ?");
    $stmt->execute([$nome, $email, $senha]);

    $usuario_db = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario_db) {
        session_start();
        $_SESSION['usuario_logado'] = $usuario_db['idcadastro'];
        $_SESSION['usuario_nome'] = $usuario_db['nome'];
        $_SESSION['usuario_foto'] = $usuario_db['foto'];
        
        header('Location: ../index.php');
        exit();
    } else {
        header('Location: login.php?error=1');
        exit();
    }
} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
    die();
}
?>
