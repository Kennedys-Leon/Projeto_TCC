<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login/login.php");
    exit;
}

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
            <?php if (empty($carrinho)): ?>
                <p>Seu carrinho está vazio.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th>Qtd</th>
                        <th>Subtotal</th>
                    </tr>
                    <?php foreach ($carrinho as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                            <td><?= $item['quantidade'] ?></td>
                            <td>R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <h3>Total: R$ <?= number_format($total, 2, ',', '.') ?></h3>
                <a href="pagamento.php" class="btn">Finalizar Pagamento</a>
            <?php endif; ?>

    <script>
        let cartItems = JSON.parse(localStorage.getItem('cart')) || [];
        let checkoutDiv = document.getElementById('checkout-items');
        let total = 0;

        if (cartItems.length > 0) {
            cartItems.forEach(item => {
                let div = document.createElement('div');
                div.classList.add('checkout-item');
                div.innerHTML = `
                    <img src="uploads/${item.image}" alt="${item.name}">
                    <div class="checkout-item-details">
                        <h4>${item.name}</h4>
                        <p>Produto digital - Entrega automática</p>
                    </div>
                    <div class="checkout-price">R$ ${item.price}</div>
                `;
                checkoutDiv.appendChild(div);
                total += parseFloat(item.price);
            });
            document.getElementById('checkout-total').textContent = total.toFixed(2);
        } else {
            checkoutDiv.innerHTML = "<p>Carrinho vazio.</p>";
        }

        document.getElementById("btn-pagar").addEventListener("click", () => {
            window.location.href = "pagamento.php";
        });
    </script>
</body>

    <div vw-plugin-wrapper>
        <div class="vw-plugin-top-wrapper"></div>
    </div>
    </div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
      new window.VLibras.Widget('https://vlibras.gov.br/app');
</html>
