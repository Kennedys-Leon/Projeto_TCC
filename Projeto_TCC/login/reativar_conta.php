<?php
session_start();
include('../conexao.php');

header('Content-Type: application/json; charset=UTF-8');

$email = trim($_POST['email'] ?? '');

if ($email === '') {
    echo json_encode(['success' => false, 'msg' => 'Email não informado.']);
    exit;
}

try {
    $sql = "UPDATE usuario SET ativo = 1 WHERE email = ? AND ativo = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'msg' => 'Conta reativada com sucesso! Faça login novamente.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'msg' => 'Conta já está ativa ou o email não existe.'
        ]);
    }
} catch (PDOException $e) {
    error_log("reativar_conta erro: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'msg' => 'Erro ao processar a solicitação. Tente novamente mais tarde.'
    ]);
}
?>
