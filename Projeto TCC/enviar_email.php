<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

/**
 * Função para enviar emails no sistema inteiro.
 * 
 * @param string $paraEmail      -> Email do destinatário
 * @param string $paraNome       -> Nome do destinatário
 * @param string $assunto        -> Assunto do email
 * @param string $mensagemHTML   -> Corpo do email em HTML
 *
 * @return bool|string true se enviou, ou mensagem de erro
 */
function enviarEmail($paraEmail, $paraNome, $assunto, $mensagemHTML)
{
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tccecommercegames@gmail.com';
        $mail->Password   = 'gbya jopz wzui ghei'; // senha de app
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Remetente
        $mail->setFrom('tccecommercegames@gmail.com', 'Max Acess');

        // Destinatário
        $mail->addAddress($paraEmail, $paraNome);

        // Conteúdo
        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body    = $mensagemHTML;

        // Enviar
        return $mail->send();

    } catch (Exception $e) {
        return "Erro ao enviar: {$mail->ErrorInfo}";
    }
}
?>
