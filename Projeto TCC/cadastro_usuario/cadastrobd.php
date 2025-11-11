<?php
include '../conexao.php';

$nome     = trim($_POST['nome'] ?? '');
$cpf      = $_POST['cpf'];
$cep      = $_POST['cep'];
$telefone = $_POST['telefone'];
$email    = trim($_POST['email'] ?? '');
$senha    = password_hash($_POST['senha'], PASSWORD_DEFAULT);


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
    header("Location: cadastro.php?error=1&msg={$msg}");
    exit;
}

$foto_de_perfil = null;
if (isset($_FILES['foto_de_perfil']) && $_FILES['foto_de_perfil']['error'] === UPLOAD_ERR_OK) {
    $foto_de_perfil = file_get_contents($_FILES['foto_de_perfil']['tmp_name']);
}

try {
    $sql = "INSERT INTO usuario 
        (nome, cpf, cep, telefone, email, senha, foto_de_perfil) 
        VALUES (:nome, :cpf, :cep, :telefone, :email, :senha, :foto_de_perfil)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':cep', $cep);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':foto_de_perfil', $foto_de_perfil, PDO::PARAM_LOB);

    $stmt->execute();

    header("Location: ../login/login.php");
    exit();
} catch (PDOException $e) {
    // mostra erro em vermelho (para debug/usuário) e registra no log
    $err = htmlspecialchars($e->getMessage(), ENT_QUOTES);
    error_log("cadastrobd erro: " . $e->getMessage());
    echo "<div style=\"color:#c00; font-weight:600; padding:16px;\">Erro ao cadastrar usuário: {$err}</div>";
    exit;
}
?>
