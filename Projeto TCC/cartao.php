<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pagamento via Cartão - MaxAcess</title>
    <link rel="stylesheet" href="css/pagamentos.css">
</head>
<body>
    <div class="container-pagamento">
        <h2>Pagamento com Cartão</h2>
        <p>Preencha os dados do seu cartão abaixo:</p>

        <form action="#" method="POST" class="form-cartao">
            <label>Número do Cartão</label>
            <input type="text" maxlength="16" placeholder="0000 0000 0000 0000" required>

            <label>Nome Impresso no Cartão</label>
            <input type="text" placeholder="Nome" required>

            <div class="linha-dupla">
                <div>
                    <label>Validade</label>
                    <input type="text" placeholder="MM/AA" required>
                </div>
                <div>
                    <label>Código De Segurança</label>
                    <input type="text"  placeholder="1234" required>
                </div>
            </div>

            <button class="btn" type="submit" onclick="alert('Simulado Concretizado !')">Pagar Agora</button>
        </form>

        <a href="pagamento.php" class="voltar"> Voltar</a>
    </div>
</body>
</html>
