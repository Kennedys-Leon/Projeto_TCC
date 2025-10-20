<?php
session_start();
include '../conexao.php';

// Verifica se o vendedor está logado
if (!isset($_SESSION['vendedor_logado'])) {
    header("Location: ../login_vendedor/login_vendedor.php");
    exit;
}

$vendedor_id = $_SESSION['vendedor_logado'];

// Captura os dados do formulário
$nome     = trim($_POST['nome']);
$email    = trim($_POST['email']);
$telefone = trim($_POST['telefone'] ?? '');
$cnpj     = trim($_POST['cnpj'] ?? '');

// Upload da foto (opcional, armazenando no banco como LONGBLOB)
$fotoBinario = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $fotoBinario = file_get_contents($_FILES['foto']['tmp_name']);
}

// Somente nome e email obrigatórios
if (!empty($nome) && !empty($email)) {
    try {
        if ($fotoBinario !== null) {
            $stmt = $pdo->prepare("
                UPDATE vendedor 
                SET nome = ?, email = ?, telefone = ?, cnpj = ?, foto_de_perfil = ?
                WHERE idvendedor = ?
            ");
            $stmt->bindParam(1, $nome);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $telefone);
            $stmt->bindParam(4, $cnpj);
            $stmt->bindParam(5, $fotoBinario, PDO::PARAM_LOB);
            $stmt->bindParam(6, $vendedor_id);
            $stmt->execute();
        } else {
            $stmt = $pdo->prepare("
                UPDATE vendedor 
                SET nome = ?, email = ?, telefone = ?, cnpj = ?
                WHERE idvendedor = ?
            ");
            $stmt->execute([$nome, $email, $telefone, $cnpj, $vendedor_id]);
        }

        // Atualiza a sessão com a nova foto e nome
        $_SESSION['vendedor_nome'] = htmlspecialchars($nome);
        if ($fotoBinario !== null) {
            $_SESSION['vendedor_foto'] = $fotoBinario;
        }

        header("Location: painel_vendedor.php?msg=perfil_atualizado");
        exit;
    } catch (PDOException $e) {
        die("Erro ao atualizar perfil: " . $e->getMessage());
    }
} else {
    header("Location: pagina_vendedor.php?msg=campos_invalidos");
    exit;
}
