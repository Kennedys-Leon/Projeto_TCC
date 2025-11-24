<?php
include '../conexao.php';

$email = trim($_POST['email'] ?? '');
$nome  = trim($_POST['nome'] ?? '');

// verifica se email ou nome já existem em usuario ou vendedor
$checkSql = "
    SELECT 'usuario' AS origem FROM usuario WHERE email = :email OR nome = :nome
    UNION
    SELECT 'vendedor' AS origem FROM vendedor WHERE email = :email OR nome = :nome
    LIMIT 1
";
$stmt = $pdo->prepare($checkSql);
$stmt->execute([':email' => $email, ':nome' => $nome]);
$found = $stmt->fetchColumn();

if ($found) {
    // redireciona de volta com mensagem de erro
    $msg = urlencode("Email ou Nome de usúario ja cadastrados!");
    header("Location: cadastrovendedor.php?error=1&msg={$msg}");
    exit;
}

// Verifica se já existe vendedor com o mesmo e-mail
$stmt = $pdo->prepare("SELECT COUNT(*) FROM vendedor WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();

if ($stmt->fetchColumn() > 0) {
    // redireciona usando a mesma mensagem para exibir em vermelho na página de cadastro
    $msg = urlencode("Email ou Nome de usúario ja cadastrados!");
    header("Location: cadastrovendedor.php?error=1&msg={$msg}");
    exit;
}

$cpf      = trim($_POST['cpf']);
$telefone = trim($_POST['telefone']);
$senha    = $_POST['senha'];
$cnpj     = trim($_POST['cnpj']);

// Criptografa a senha antes de salvar
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Foto de perfil
$foto_de_perfil = null;
if (isset($_FILES['foto_de_perfil']) && $_FILES['foto_de_perfil']['error'] == 0) {
    $foto_de_perfil = file_get_contents($_FILES['foto_de_perfil']['tmp_name']);
}

try {
    $sql = "INSERT INTO vendedor 
            (nome, cpf, telefone, email, senha, cnpj, foto_de_perfil) 
            VALUES (:nome, :cpf, :telefone, :email, :senha, :cnpj, :foto_de_perfil)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senhaHash);
    $stmt->bindParam(':cnpj', $cnpj);
    $stmt->bindParam(':foto_de_perfil', $foto_de_perfil, PDO::PARAM_LOB);

    $stmt->execute();

    header("Location: ../login_vendedor/login_vendedor.php");
    exit();
} catch(PDOException $e) {
    // mostra erro em vermelho
    $err = htmlspecialchars($e->getMessage(), ENT_QUOTES);
    echo "<div style=\"color:#c00; font-weight:600; padding:16px;\">Erro ao cadastrar vendedor: {$err}</div>";
    exit;
}

$pdo = null;
?>




