<?php
session_start();
include '../conexao.php';

// Verifica se o vendedor está logado
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../login_vendedor/login_vendedor.php");
    exit;
}

$vendedor_id = $_SESSION['usuario_logado'];

// Captura os dados do formulário
$nome     = trim($_POST['nome']);
$email    = trim($_POST['email']);
$telefone = trim($_POST['telefone'] ?? '');
$cnpj     = trim($_POST['cnpj'] ?? '');

// Upload da foto (opcional)
$fotoPath = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $fotoPath = "uploads/" . uniqid("vendedor_") . "." . $ext;
    move_uploaded_file($_FILES['foto']['tmp_name'], $fotoPath);
}

// Somente nome e email obrigatórios
if (!empty($nome) && !empty($email)) {
    try {
        if ($fotoPath) {
            $stmt = $pdo->prepare("
                UPDATE vendedor 
                SET nome = ?, email = ?, telefone = ?, cnpj = ?, foto = ?
                WHERE idvendedor = ?
            ");
            $stmt->execute([$nome, $email, $telefone, $cnpj, $fotoPath, $vendedor_id]);
        } else {
            $stmt = $pdo->prepare("
                UPDATE vendedor 
                SET nome = ?, email = ?, telefone = ?, cnpj = ?
                WHERE idvendedor = ?
            ");
            $stmt->execute([$nome, $email, $telefone, $cnpj, $vendedor_id]);
        }

        header("Location: painel_vendedor.php?msg=perfil_atualizado");
        exit;
    } catch (PDOException $e) {
        die("Erro ao atualizar perfil: " . $e->getMessage());
    }
} else {
    header("Location: painel_vendedor.php?msg=campos_invalidos");
    exit;
}
