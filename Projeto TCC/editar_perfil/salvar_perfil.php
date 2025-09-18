<?php
session_start();
include '../conexao.php';

$idusuario = $_SESSION['usuario_logado'] ?? null;

if (!$idusuario) {
    header("Location: ../login/login.php");
    exit;
}

$nome     = trim($_POST['nome']);
$cpf      = trim($_POST['cpf']);
$cep      = trim($_POST['cep']);
$endereco = trim($_POST['endereco']);
$cidade   = trim($_POST['cidade']);
$estado   = trim($_POST['estado']);
$bairro   = trim($_POST['bairro']);
$telefone = trim($_POST['telefone']);
$email    = trim($_POST['email']);
$senha    = trim($_POST['senha']);

// Foto de perfil
$foto_perfil = null;
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
    $foto_perfil = file_get_contents($_FILES['foto_perfil']['tmp_name']); // BLOB puro
}

try {
    if ($foto_perfil) {
        $sql = "UPDATE usuario 
                SET nome = :nome, cpf = :cpf, cep = :cep, endereco = :endereco,
                    cidade = :cidade, estado = :estado, bairro = :bairro, 
                    telefone = :telefone, email = :email, senha = :senha, 
                    foto_de_perfil = :foto 
                WHERE idcadastro = :idusuario";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':foto', $foto_perfil, PDO::PARAM_LOB);
    } else {
        $sql = "UPDATE usuario 
                SET nome = :nome, cpf = :cpf, cep = :cep, endereco = :endereco,
                    cidade = :cidade, estado = :estado, bairro = :bairro, 
                    telefone = :telefone, email = :email, senha = :senha
                WHERE idcadastro = :idusuario";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':cep', $cep);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':cidade', $cidade);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':bairro', $bairro);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':idusuario', $idusuario);

    $stmt->execute();

    // Atualizar sessão
    $_SESSION['nome']     = $nome;
    $_SESSION['cpf']      = $cpf;
    $_SESSION['cep']      = $cep;
    $_SESSION['endereco'] = $endereco;
    $_SESSION['cidade']   = $cidade;
    $_SESSION['estado']   = $estado;
    $_SESSION['bairro']   = $bairro;
    $_SESSION['telefone'] = $telefone;
    $_SESSION['email']    = $email;
    $_SESSION['senha']    = $senha;

    // Atualiza a foto na sessão
    if ($foto_perfil) {
        $_SESSION['usuario_foto'] = $foto_perfil; // Mantém o blob cru
    } else {
        // Caso não tenha feito upload, busca a foto já existente do banco
        $stmtFoto = $pdo->prepare("SELECT foto_de_perfil FROM usuario WHERE idcadastro = :idusuario");
        $stmtFoto->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
        $stmtFoto->execute();
        $fotoExistente = $stmtFoto->fetchColumn();

        if ($fotoExistente) {
            $_SESSION['usuario_foto'] = $fotoExistente;
        }
    }

    header("Location: editar_perfil.php?sucesso=1");
    exit;

} catch (PDOException $e) {
    die("Erro ao atualizar perfil: " . $e->getMessage());
}
