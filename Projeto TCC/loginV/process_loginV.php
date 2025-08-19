<?php
include '../cadastro/conexao.php';

$nome = trim(htmlspecialchars($_POST['nome']));
$email = trim(htmlspecialchars($_POST['email']));
$senha = trim(htmlspecialchars($_POST['senha']));

try {
    $stmt = $pdo->prepare("SELECT * FROM vendedor WHERE nome = ? AND email = ? AND senha = ?");
    $stmt->execute([$nome, $email, $senha]);

    $usuario_db = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario_db) {
        session_start();
        $_SESSION['usuario_logado'] = $usuario_db['idvendedor'];
        $_SESSION['vendedor_nome'] = $usuario_db['nome'];
        header('Location: ../index.php');
        exit();
    } else {
        header('Location: loginV.php?error=1');
        exit();
    }
} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
    die();
}
?>
