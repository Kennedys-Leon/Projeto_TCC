<?php
session_start();

// Verifica login do usuário
if (!isset($_SESSION['usuario_nome'])) {
    echo "
    <html lang='pt-BR'>
    <head>
        <meta charset='UTF-8'>
        <title>Redirecionando...</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f7f7f7;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                color: #333;
            }
            .mensagem {
                background: white;
                padding: 30px;
                border-radius: 10px;
                text-align: center;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
        </style>
    </head>
    <body>
        <div class='mensagem'>
            <h2>⚠️ Você precisa estar logado para realizar um pagamento!</h2>
            <p>Redirecionando para o cadastro...</p>
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

// Verifica se há itens no carrinho
if (empty($_SESSION['carrinho'])) {
    echo "
    <html lang='pt-BR'>
    <head>
        <meta charset='UTF-8'>
        <title>Sem produtos</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f7f7f7;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                color: #333;
            }
            .mensagem {
                background: white;
                padding: 30px;
                border-radius: 10px;
                text-align: center;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            a {
                color: #007bff;
                text-decoration: none;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class='mensagem'>
            <h2>Seu carrinho está vazio.</h2>
            <p><a href='../index.php'>Voltar à loja</a></p>
        </div>
    </body>
    </html>
    ";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pagamento - MaxAcess</title>
    <link rel="stylesheet" href="../css/pagamento.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f9f9f9;
            color: #333;
        }
        .pagamento-container {
            max-width: 500px;
            margin: 100px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
        }
        h2 {
            color: #444;
            margin-bottom: 30px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .metodo {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            border: 2px solid #ccc;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            font-weight: 600;
        }
        .metodo:hover {
            border-color: #8C5B3F;
            background: #f7efe8;
        }
        input[type="radio"] {
            transform: scale(1.2);
            accent-color: #8C5B3F;
        }
        .btn-pagar {
            background-color: #8C5B3F;
            color: white;
            border: none;
            padding: 15px;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-pagar:hover {
            background-color: #6f462f;
        }
    </style>
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
            <a href="../index.php" class="voltar">⬅Retornar ao início</a>
        </form>
    </div>

    <!-- VLibras -->
    <div vw-plugin-wrapper><div class="vw-plugin-top-wrapper"></div></div>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>new window.VLibras.Widget('https://vlibras.gov.br/app');</script>
</body>
</html>
