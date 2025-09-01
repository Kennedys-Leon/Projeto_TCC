<?php
include '../cadastro/conexao.php';

$nome = trim(htmlspecialchars($_POST['nome']));
$email = trim(htmlspecialchars($_POST['email']));
$senha = trim(htmlspecialchars($_POST['senha']));

try {
    $sql = "SELECT * FROM vendedor WHERE nome = :nome AND email = :email AND senha = :senha";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);

    $stmt->execute();

    $vendedor_id = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vendedor_id) {
        session_start();
        $_SESSION['usuario_logado'] = $vendedor_id['idvendedor'];
        $_SESSION['vendedor_nome'] = $vendedor_id['nome'];

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
