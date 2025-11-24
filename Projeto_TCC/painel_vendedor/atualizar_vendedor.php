<?php
session_start();
include '../conexao.php';

// Verifica se o vendedor está logado
if (!isset($_SESSION['vendedor_logado'])) {
    header("Location: ../login_vendedor/login_vendedor.php");
    exit;
}

$vendedor_id = $_SESSION['vendedor_logado'];

// Captura e sanitiza os dados enviados
$nome     = trim($_POST['nome'] ?? '');
$email    = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$cnpj     = trim($_POST['cnpj'] ?? '');

// Upload da foto (opcional)
$fotoBinario = null;
if (!empty($_FILES['foto']['tmp_name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $fotoBinario = file_get_contents($_FILES['foto']['tmp_name']);
}

// Verificação — nome e email são obrigatórios
if ($nome !== "" && $email !== "") {

    try {
        // Atualização com nova foto
        if ($fotoBinario !== null) {

            $sql = "
                UPDATE vendedor
                SET 
                    nome = ?, 
                    email = ?, 
                    telefone = ?, 
                    cnpj = ?, 
                    foto_de_perfil = ?
                WHERE idvendedor = ?
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(1, $nome);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $telefone);
            $stmt->bindParam(4, $cnpj);
            $stmt->bindParam(5, $fotoBinario, PDO::PARAM_LOB);
            $stmt->bindParam(6, $vendedor_id);

        } else {
            // Atualização sem mexer na foto
            $sql = "
                UPDATE vendedor
                SET 
                    nome = ?, 
                    email = ?, 
                    telefone = ?, 
                    cnpj = ?
                WHERE idvendedor = ?
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome, $email, $telefone, $cnpj, $vendedor_id]);
        }

        // Executa atualização quando houver bind manual
        if ($fotoBinario !== null) {
            $stmt->execute();
        }

        // Atualiza sessão
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
