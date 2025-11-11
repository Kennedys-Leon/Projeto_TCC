<?php
// desativa conta do usuário logado (usuário comum) e encerra sessão
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
header('Content-Type: application/json; charset=utf-8');

include __DIR__ . '/../conexao.php';

// valida request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'msg' => 'Método não permitido.']);
    exit;
}

$confirm = filter_input(INPUT_POST, 'confirm', FILTER_SANITIZE_STRING);
if (empty($confirm)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'msg' => 'Requisição inválida. Confirmação necessária.']);
    exit;
}

$idusuario = $_SESSION['idusuario'] ?? $_SESSION['usuario_nome'] ?? $_SESSION['idusuario'] ?? null;
$idusuario = (int) $idusuario;
if ($idusuario <= 0) {
    http_response_code(401);
    echo json_encode(['success' => false, 'msg' => 'Usuário não autenticado.']);
    exit;
}

try {
    // Tenta atualizar usando nomes de PK comuns (ajuste se seu PK for outro)
    $pkCandidates = ['idusuario', 'idcadastro', 'id'];
    $updated = false;

    foreach ($pkCandidates as $pk) {
        $sql = "UPDATE usuario SET ativo = 0 WHERE {$pk} = :idusuario";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':idusuario', $idusuario, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $updated = true;
            break;
        }
    }

    if (!$updated) {
        // pode já estar desativada ou coluna PK diferente
        http_response_code(404);
        echo json_encode(['success' => false, 'msg' => 'Conta não encontrada ou já desativada.']);
        exit;
    }

    // encerra sessão limpa
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();

    echo json_encode(['success' => true, 'msg' => 'Sua conta foi desativada com sucesso!']);
    exit;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'msg' => 'Erro ao desativar conta: ' . $e->getMessage()]);
    exit;
}
?>