<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo "
    <html lang='pt-BR'>
    <head>
        <meta charset='UTF-8'>
        <title>Redirecionando...</title>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background: #f7f7f7;
                color: #333;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                text-align: center;
                flex-direction: column;
            }
            .caixa {
                background: white;
                padding: 40px;
                border-radius: 15px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            h2 {
                color: #d9534f;
            }
            p {
                margin-top: 10px;
            }
        </style>
    </head>
    <body>
        <div class='caixa'>
            <h2>⚠️ Você precisa estar logado para finalizar sua compra!</h2>
            <p>Você será redirecionado para a página de cadastro em alguns segundos...</p>
        </div>
        <script>
            setTimeout(() => {
                window.location.href = '../cadastro_usuario/cadastro.php';
            }, 3000);
        </script>
    </body>
    </html>
    ";
    exit;
}

// Lê o carrinho da sessão
$carrinho = $_SESSION['carrinho'] ?? [];
$total = 0;
foreach ($carrinho as $item) {
    $total += $item['preco'] * $item['quantidade'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Checkout - MaxAcess</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/checkout.css">
</head>
<body>
    <div class="checkout-container">
        <div class="checkout-header">
            <h2>Seu Carrinho</h2>
        </div>

        <?php if (empty($carrinho)): ?>
            <p>Seu carrinho está vazio.</p>
            <a href="../index.php" class="btn-voltar">Voltar à Loja</a>

        <?php else: ?>
            <table class="checkout-tabela">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th>Qtd</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrinho as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                            <td><?= $item['quantidade'] ?></td>
                            <td>R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="checkout-total">
                <h3>Total: <span>R$ <?= number_format($total, 2, ',', '.') ?></span></h3>
            </div>

            <div class="checkout-acoes">
                <a href="../index.php" class="btn-voltar">Continuar Comprando</a>
                <a href="pagamento.php" class="btn-finalizar">Finalizar Pagamento</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Plugin VLibras -->
    <div vw-plugin-wrapper>
        <div class="vw-plugin-top-wrapper"></div>
    </div>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>new window.VLibras.Widget('https://vlibras.gov.br/app');</script>
</body>
</html>
