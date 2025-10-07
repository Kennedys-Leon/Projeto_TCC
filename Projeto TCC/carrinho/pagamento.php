<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pagamento - MaxAcess</title>
    <link rel="stylesheet" href="../css/pagamento.css">

</head>
<body>
    <div class="pagamento-container">
        <h2>Escolha a forma de pagamento</h2>

        <form action="redireciona_pagamento.php" method="POST">
            <label class="metodo">
                <input type="radio" name="pagamento" value="pix" required>
                💸 Pix
            </label>

            <label class="metodo">
                <input type="radio" name="pagamento" value="cartao" required>
                💳 Cartão de Crédito/Débito
            </label>

            <label class="metodo">
                <input type="radio" name="pagamento" value="boleto" required>
                📄 Boleto Bancário
            </label>

            <button type="submit" class="btn-pagar">Confirmar Pagamento</button>
        </form>
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
