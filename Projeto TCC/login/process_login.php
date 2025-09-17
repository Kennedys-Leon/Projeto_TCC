<?php
include '../conexao.php';

$nome  = trim(htmlspecialchars($_POST['nome']));
$email = trim(htmlspecialchars($_POST['email']));
$senha = trim($_POST['senha']);

try {
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE nome = ? AND email = ? LIMIT 1");
    $stmt->execute([$nome, $email]);

    $usuario_db = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario_db && password_verify($senha, $usuario_db['senha'])) {
        session_start();
        $_SESSION['usuario_logado'] = $usuario_db['idcadastro'];
        $_SESSION['usuario_nome']   = htmlspecialchars($usuario_db['nome']);
        $_SESSION['usuario_foto']   = $usuario_db['foto_de_perfil'];

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
