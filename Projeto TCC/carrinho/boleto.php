<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pagamento via Boleto - MaxAcess</title>
    <link rel="stylesheet" href="../css/boleto.css">
</head>
<body>
    <div class="container-pagamento">
        <h2>Pagamento via Boleto</h2>
        <p>Para concluir a compra, clique no botão abaixo para gerar o boleto em PDF.</p>

        <a href="boleto_pdf.php" target="_blank" class="btn-boleto">Gerar Boleto PDF</a>

        <a href="pagamento.php" class="voltar">Voltar</a>
    </div>
</body>
</html>
