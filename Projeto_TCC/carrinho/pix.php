<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pagamento via Pix - MaxAcess</title>
    <link rel="stylesheet" href="../css/pix.css">
</head>
<body>
    <div class="container-pagamento">
        <h2>Pagamento via Pix</h2>
        <p>Escaneie o QR Code abaixo para pagar:</p>
        <div class="qr-code">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=PIX_SIMULADO" alt="QR Code Pix">
        </div>
        <button onclick="alert('Pagamento Pix simulado concluído!')">Finalizar</button>
        <a href="pagamento.php" class="voltar">Voltar</a>

    </div>
</body>
<div vw-plugin-wrapper>
        <div class="vw-plugin-top-wrapper"></div>
    </div>
</div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
      new window.VLibras.Widget('https://vlibras.gov.br/app');
</html>
