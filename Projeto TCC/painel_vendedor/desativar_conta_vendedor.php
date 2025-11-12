<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
include '../conexao.php';

try {
    // Verifica se o vendedor está logado
    if (!isset($_SESSION['vendedor_logado'])) {
        echo json_encode(['success' => false, 'msg' => 'Sessão expirada. Faça login novamente.']);
        exit;
    }

    // Verifica se foi confirmado o pedido
    if (!isset($_POST['confirm']) || $_POST['confirm'] != 1) {
        echo json_encode(['success' => false, 'msg' => 'Confirmação não recebida.']);
        exit;
    }

    $idvendedor = $_SESSION['vendedor_logado'];

    // Atualiza o status do vendedor para desativado (ajuste o campo conforme seu banco)
    $sql = "UPDATE vendedor SET status_conta = 'desativado' WHERE idvendedor = :idvendedor";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':idvendedor', $idvendedor, PDO::PARAM_INT);
    $stmt->execute();

    // Verifica se a conta foi realmente desativada
    if ($stmt->rowCount() > 0) {
        // Encerra a sessão do vendedor
        session_destroy();
        echo json_encode(['success' => true, 'msg' => 'Sua conta de vendedor foi desativada com sucesso.']);
    } else {
        echo json_encode(['success' => false, 'msg' => 'Nenhuma alteração foi feita. Conta não encontrada.']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'msg' => 'Erro no banco de dados: ' . $e->getMessage()]);
    exit;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'msg' => 'Erro interno: ' . $e->getMessage()]);
    exit;
}
?>
