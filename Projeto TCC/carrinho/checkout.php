<?php
session_start();
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
            <h2>Finalizar Compra</h2>
            <p>Revise seus produtos antes de concluir</p>
        </div>

        <div id="checkout-items" class="checkout-items"></div>

        <div class="checkout-summary">
            <img src="img/FF.jpeg" alt="Produto 1" />
            <div class="checkout-total">Total: R$ <span id="checkout-total">0,00</span></div>
            <button id="btn-pagar" class="checkout-btn">Pagar Agora</button>
        </div>
    </div>

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
</html>
