<?php
include '../conexao.php';

$nome  = trim($_POST['nome']);
$email = trim($_POST['email']);
$senha = trim($_POST['senha']);

try {
    $sql = "SELECT * FROM vendedor WHERE nome = :nome AND email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);

    $stmt->execute();

    $vendedor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vendedor && password_verify($senha, $vendedor['senha'])) {
        session_start();
        $_SESSION['vendedor_logado'] = $vendedor['idvendedor'];
        $_SESSION['vendedor_nome']   = htmlspecialchars($vendedor['nome']);
        $_SESSION['vendedor_foto']   = $vendedor['foto_de_perfil'];

        header('Location: ../painel_vendedor/pagina_vendedor.php');
        exit();
    } else {
        header('Location: login_vendedor.php?error=1');
        exit();
    }
} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
    die();
}
?>
