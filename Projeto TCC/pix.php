<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pagamento via Pix - MaxAcess</title>
    <link rel="stylesheet" href="css/pagamentos.css">
</head>
<body>
    <div class="container-pagamento">
        <h2>Pagamento via Pix</h2>
        <p>Escaneie o QR Code abaixo para pagar:</p>
        <div class="qr-code">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=PIX_SIMULADO" alt="QR Code Pix">
        </div>
        <button onclick="alert('Pagamento Pix simulado concluído!')">Finalizar</button>
    </div>
</body>
</html>
