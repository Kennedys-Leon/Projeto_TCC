<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Checkout - MaxAcess</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #1e272e;
            color: #ecf0f1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 40px;
        }

        .checkout-container {
            background-color: #2c3e50;
            padding: 30px;
            border-radius: 12px;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 10px 25px rgba(0,0,0,0.4);
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .checkout-header {
            text-align: center;
            border-bottom: 2px solid #34495e;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .checkout-header h2 {
            font-size: 26px;
            font-weight: 600;
            color: #00c6fd;
        }

        .checkout-items {
            margin-bottom: 20px;
        }

        .checkout-item {
            display: flex;
            align-items: center;
            background-color: #34495e;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            gap: 20px;
            transition: transform 0.2s ease;
        }

        .checkout-item:hover {
            transform: scale(1.02);
        }

        .checkout-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #00c6fd;
        }

        .checkout-item-details {
            flex-grow: 1;
        }

        .checkout-item-details h4 {
            margin: 0 0 5px 0;
            font-size: 18px;
            font-weight: 500;
        }

        .checkout-item-details p {
            margin: 0;
            font-size: 14px;
            color: #bdc3c7;
        }

        .checkout-price {
            font-size: 18px;
            font-weight: bold;
            color: #00c6fd;
        }

        .checkout-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 2px solid #34495e;
            padding-top: 20px;
            margin-top: 20px;
        }

        .checkout-total {
            font-size: 20px;
            font-weight: 600;
        }

        .checkout-btn {
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .checkout-btn:hover {
            background-color: #2ecc71;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <div class="checkout-header">
            <h2>Finalizar Compra</h2>
            <p>Revise seus produtos antes de concluir</p>
        </div>

        <div id="checkout-items" class="checkout-items"></div>

        <div class="checkout-summary">
            <div class="checkout-total">Total: R$ <span id="checkout-total">0,00</span></div>
            <button class="checkout-btn">Pagar Agora</button>
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
</script>
</body>
</html>
