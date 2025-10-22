<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../conexao.php';

// Somente usuário (não vendedor) — usar o ID salvo na sessão
$idusuario = $_SESSION['usuario_nome'] ?? null;
if (!$idusuario) {
    header("Location: ../login/login.php");
    exit;
}

// Recebe campos
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
    // se veio arquivo válido, leia conteúdo e inclua no UPDATE
    $fotoBlob = null;
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['foto_perfil']['tmp_name'];
        $fotoBlob = file_get_contents($tmp);
    }

    if ($fotoBlob !== null) {
        $sql = "UPDATE usuario SET 
                    nome = :nome, cpf = :cpf, cep = :cep, endereco = :endereco,
                    cidade = :cidade, estado = :estado, bairro = :bairro,
                    telefone = :telefone, email = :email, senha = :senha,
                    foto_de_perfil = :foto
                WHERE idusuario = :idusuario";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':foto', $fotoBlob, PDO::PARAM_LOB);
    } else {
        $sql = "UPDATE usuario SET 
                    nome = :nome, cpf = :cpf, cep = :cep, endereco = :endereco,
                    cidade = :cidade, estado = :estado, bairro = :bairro,
                    telefone = :telefone, email = :email, senha = :senha
                WHERE idusuario = :idusuario";
        $stmt = $pdo->prepare($sql);
    }

    // binds comuns
    $stmt->bindValue(':nome', $nome);
    $stmt->bindValue(':cpf', $cpf);
    $stmt->bindValue(':cep', $cep);
    $stmt->bindValue(':endereco', $endereco);
    $stmt->bindValue(':cidade', $cidade);
    $stmt->bindValue(':estado', $estado);
    $stmt->bindValue(':bairro', $bairro);
    $stmt->bindValue(':telefone', $telefone);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':senha', $senha);
    $stmt->bindValue(':idusuario', $idusuario, PDO::PARAM_INT);

    $ok = $stmt->execute();
    if (!$ok) {
        $err = $stmt->errorInfo();
        throw new Exception("Erro ao executar UPDATE: " . implode(' | ', $err));
    }

    // Atualiza sessão com os novos valores
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

    // Atualiza foto na sessão: mantém mesma estrutura usada por perfil.php (raw blob)
    if ($fotoBlob !== null) {
        $_SESSION['usuario_foto'] = $fotoBlob;
    }

    header("Location: perfil.php?sucesso=1");
    exit;

} catch (Exception $e) {
    // DEBUG: mostrar erro detalhado na tela (remover depois)
    echo "<h3>Erro ao atualizar perfil</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    if (isset($stmt)) {
        echo "<h4>SQL errorInfo:</h4><pre>" . htmlspecialchars(print_r($stmt->errorInfo(), true)) . "</pre>";
    }
    echo "<h4>POST:</h4><pre>" . htmlspecialchars(print_r($_POST, true)) . "</pre>";
    echo "<h4>FILES:</h4><pre>" . htmlspecialchars(print_r($_FILES, true)) . "</pre>";
    exit;
}
