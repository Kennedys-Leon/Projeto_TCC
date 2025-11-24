<?php
session_start();
include('../conexao.php');

header('Content-Type: application/json; charset=utf-8');

// Verifica se o usuário está logado
if (!isset($_SESSION['idusuario'])) {
    echo json_encode(['success' => false, 'msg' => 'Usuário não autenticado.']);
    exit;
}

$idusuario = (int) $_SESSION['idusuario'];

// Busca CPF, CEP e telefone
$stmt = $pdo->prepare("SELECT cpf, cep, telefone FROM usuario WHERE idusuario = :id");
$stmt->bindValue(':id', $idusuario, PDO::PARAM_INT);
$stmt->execute();
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dados) {
    echo json_encode(['success' => false, 'msg' => 'Usuário não encontrado.']);
    exit;
}

// Verifica se os campos estão preenchidos
if (empty($dados['cpf']) || empty($dados['cep']) || empty($dados['telefone'])) {
    echo json_encode([
        'success' => false,
        'msg' => 'Por favor, complete seu CPF, CEP e Telefone para continuar.'
    ]);
    exit;
}

echo json_encode(['success' => true]);
exit;
?>
