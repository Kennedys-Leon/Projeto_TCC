<?php
session_start();
include '../conexao.php';

header('Content-Type: application/json; charset=utf-8');

$email = trim($_POST['email'] ?? '');

if (empty($email)) {
    echo json_encode(['success' => false, 'msg' => 'E-mail inválido.']);
    exit;
}

try {
    $sql = "UPDATE vendedor SET status_conta = 'ativo' WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'msg' => 'Conta reativada com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'msg' => 'Conta não encontrada ou já ativa.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'msg' => 'Erro ao reativar conta: ' . $e->getMessage()]);
}
?>
