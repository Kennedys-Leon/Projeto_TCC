<?php
session_start();
include('../conexao.php');

$email = trim($_GET['email'] ?? '');

if ($email === '') {
    header("Location: login.php?error=1");
    exit;
}

try {
    $sql = "UPDATE usuario SET ativo = 1 WHERE email = ? AND ativo = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        header("Location: login.php?msg=" . urlencode("Conta reativada com sucesso! Faça login novamente."));
        exit;
    } else {
        header("Location: login.php?error=1");
        exit;
    }
} catch (PDOException $e) {
    error_log("reativar_conta erro: " . $e->getMessage());
    header("Location: login.php?error=1");
    exit;
}
?>
