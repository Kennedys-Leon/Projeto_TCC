<?php
include '../conexao.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// ==== RECEBE DADOS DO FORM ====
$nome     = trim($_POST['nome'] ?? '');
$cpf      = $_POST['cpf'];
$cep      = $_POST['cep'];
$telefone = $_POST['telefone'];
$email    = trim($_POST['email'] ?? '');
$senha    = password_hash($_POST['senha'], PASSWORD_DEFAULT);

// === VERIFICA SE JÁ EXISTE EMAIL OU NOME ====
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
    $msg = urlencode("Email ou Nome de usuário já cadastrados!");
    header("Location: cadastro.php?error=1&msg={$msg}");
    exit;
}

// === FOTO DE PERFIL ====
$foto_de_perfil = null;
if (isset($_FILES['foto_de_perfil']) && $_FILES['foto_de_perfil']['error'] === UPLOAD_ERR_OK) {
    $foto_de_perfil = file_get_contents($_FILES['foto_de_perfil']['tmp_name']);
}

// === CADASTRA USUÁRIO ====
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

    // ==========================================
    //       ENVIA EMAIL DE BOAS-VINDAS
    // ==========================================
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tccecommercegames@gmail.com';
        $mail->Password   = 'gbya jopz wzui ghei'; // sua senha de app
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('tccecommercegames@gmail.com', 'Max Acess');
        $mail->addAddress($email); // ← ENVIA PARA O USUÁRIO

        $mail->isHTML(true);
        $mail->Subject = 'Cadastro realizado com sucesso ✔️';
        $mail->Body = "
            <h2>Bem-vindo, $nome!</h2>
            <p>Seu cadastro foi concluído com sucesso na Max Acess.</p>
            <p>Agora você pode acessar sua conta normalmente.</p>
        ";

        $mail->send();

    } catch (Exception $e) {
        error_log("Erro ao enviar email de cadastro: " . $mail->ErrorInfo);
    }

    // REDIRECIONA PARA LOGIN
    header("Location: ../login/login.php");
    exit();

} catch (PDOException $e) {
    $err = htmlspecialchars($e->getMessage(), ENT_QUOTES);
    error_log("cadastrobd erro: " . $e->getMessage());
    echo "<div style=\"color:#c00; font-weight:600; padding:16px;\">Erro ao cadastrar usuário: {$err}</div>";
    exit;
}
