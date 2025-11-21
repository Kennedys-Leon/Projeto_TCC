<?php
session_start();
require '../conexao.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// ------------------------------------------------------------------------
// 1. RECEBE OS DADOS DO FORM
// ------------------------------------------------------------------------
$tipo  = $_POST['tipo'] ?? '';
$email = $_POST['email'] ?? '';

if (empty($email) || empty($tipo)) {
    header("Location: recuperar_senha.php?erro=1&msg=" . urlencode("Preencha todos os campos."));
    exit;
}

// ------------------------------------------------------------------------
// 2. DEFINE A TABELA CORRETA E O ID CORRETO
// ------------------------------------------------------------------------
if ($tipo === "usuario") {
    $tabela = "usuario";
    $campo_id = "idusuario";
} elseif ($tipo === "vendedor") {
    $tabela = "vendedor";
    $campo_id = "idvendedor";
} else {
    header("Location: recuperar_senha.php?erro=1&msg=" . urlencode("Tipo inválido."));
    exit;
}

// ------------------------------------------------------------------------
// 3. VERIFICAR SE O E-MAIL EXISTE
// ------------------------------------------------------------------------
$sql = $pdo->prepare("SELECT $campo_id AS id FROM $tabela WHERE email = ? LIMIT 1");
$sql->execute([$email]);

$dados = $sql->fetch(PDO::FETCH_ASSOC);

if (!$dados) {
    header("Location: recuperar_senha.php?erro=1&msg=" . urlencode("E-mail não encontrado."));
    exit;
}

$id = $dados['id'];

// ------------------------------------------------------------------------
// 4. GERAR TOKEN
// ------------------------------------------------------------------------
$token = bin2hex(random_bytes(16));
$expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

$update = $pdo->prepare("UPDATE $tabela SET reset_token = ?, token_expira = ? WHERE $campo_id = ?");
$update->execute([$token, $expira, $id]);

// ------------------------------------------------------------------------
// 5. ENVIAR O E-MAIL
// ------------------------------------------------------------------------
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'tccecommercegames@gmail.com';
    $mail->Password = 'gbya jopz wzui ghei';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('tccecommercegames@gmail.com', 'Suporte - Max Acess');
    $mail->addAddress($email);

    $mail->Subject = "Recuperação de senha";

    $link = "http://localhost/Projeto_TCC/Projeto TCC/login/redefinir_senha.php?token=$token&tipo=$tipo";

    $mail->isHTML(true);

    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail->Body = "
        <h2>Recuperação de Senha</h2>
        <p>Clique abaixo para redefinir sua senha:</p>
        <p><a href='$link' style='padding:12px 20px; background:#111; color:#fff; text-decoration:none; border-radius:6px;'>Redefinir Senha</a></p>
        <br>
        <p>Se você não solicitou isso, ignore este e-mail.</p>
    ";

    $mail->send();

    header("Location: recuperar_senha.php?msg=" . urlencode("E-mail enviado! Verifique sua caixa de entrada."));
    exit;

} catch (Exception $e) {
    header("Location: recuperar_senha.php?erro=1&msg=" . urlencode("Erro ao enviar e-mail: {$mail->ErrorInfo}"));
    exit;
}

?>
