<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../conexao.php';

// Garante que o usuário esteja logado
$idusuario = $_SESSION['idusuario'] ?? null;
if (!$idusuario) {
    header("Location: ../login/login.php");
    exit;
}

// Recebe campos do formulário
$nome     = trim($_POST['nome'] ?? '');
$cpf      = trim($_POST['cpf'] ?? '');
$cep      = trim($_POST['cep'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$cidade   = trim($_POST['cidade'] ?? '');
$estado   = trim($_POST['estado'] ?? '');
$bairro   = trim($_POST['bairro'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$email    = trim($_POST['email'] ?? '');
$senha    = trim($_POST['senha'] ?? '');

try {
    // Se veio arquivo válido, lê conteúdo e inclui no UPDATE
    $fotoBlob = null;
    if (!empty($_FILES['foto_perfil']['tmp_name']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $fotoBlob = file_get_contents($_FILES['foto_perfil']['tmp_name']);
    }

    // Se o usuário digitou uma nova senha, criptografa
    if (!empty($senha)) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    } else {
        // mantém a senha atual, buscando do banco
        $sqlSenha = "SELECT senha FROM usuario WHERE idusuario = :idusuario";
        $stmtSenha = $pdo->prepare($sqlSenha);
        $stmtSenha->bindValue(':idusuario', $idusuario, PDO::PARAM_INT);
        $stmtSenha->execute();
        $senhaHash = $stmtSenha->fetchColumn();
    }

    // Query condicional (com ou sem foto)
    if ($fotoBlob !== null) {
        $sql = "UPDATE usuario SET 
                    nome = :nome, cpf = :cpf, cep = :cep, endereco = :endereco,
                    cidade = :cidade, estado = :estado, bairro = :bairro,
                    telefone = :telefone, email = :email, senha = :senha,
                    foto_de_perfil = :foto
                WHERE idusuario = :idusuario";
    } else {
        $sql = "UPDATE usuario SET 
                    nome = :nome, cpf = :cpf, cep = :cep, endereco = :endereco,
                    cidade = :cidade, estado = :estado, bairro = :bairro,
                    telefone = :telefone, email = :email, senha = :senha
                WHERE idusuario = :idusuario";
    }

    $stmt = $pdo->prepare($sql);

    // Binds comuns
    $stmt->bindValue(':nome', $nome);
    $stmt->bindValue(':cpf', $cpf);
    $stmt->bindValue(':cep', $cep);
    $stmt->bindValue(':endereco', $endereco);
    $stmt->bindValue(':cidade', $cidade);
    $stmt->bindValue(':estado', $estado);
    $stmt->bindValue(':bairro', $bairro);
    $stmt->bindValue(':telefone', $telefone);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':senha', $senhaHash);
    $stmt->bindValue(':idusuario', $idusuario, PDO::PARAM_INT);

    if ($fotoBlob !== null) {
        $stmt->bindValue(':foto', $fotoBlob, PDO::PARAM_LOB);
    }

    $ok = $stmt->execute();

    if (!$ok) {
        throw new Exception("Erro ao executar UPDATE: " . implode(' | ', $stmt->errorInfo()));
    }

    // Atualiza sessão com novos dados
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['cpf'] = $cpf;
    $_SESSION['cep'] = $cep;
    $_SESSION['endereco'] = $endereco;
    $_SESSION['cidade'] = $cidade;
    $_SESSION['estado'] = $estado;
    $_SESSION['bairro'] = $bairro;
    $_SESSION['telefone'] = $telefone;
    $_SESSION['email'] = $email;

    if ($fotoBlob !== null) {
        $_SESSION['usuario_foto'] = $fotoBlob;
    }

    header("Location: perfil.php?sucesso=1");
    exit;

} catch (Exception $e) {
    echo "<h3>Erro ao atualizar perfil</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    if (isset($stmt)) {
        echo "<h4>SQL errorInfo:</h4><pre>" . htmlspecialchars(print_r($stmt->errorInfo(), true)) . "</pre>";
    }
    exit;
}
?>
