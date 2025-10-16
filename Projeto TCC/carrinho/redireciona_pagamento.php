<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
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
            <h2>⚠️ Você precisa estar logado para continuar o pagamento!</h2>
            <p>Redirecionando para o login...</p>
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

// Verifica se o formulário foi enviado corretamente
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['pagamento'])) {
    echo "
    <html lang='pt-BR'>
    <head>
        <meta charset='UTF-8'>
        <title>Erro</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f9f9f9;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .erro {
                background: white;
                padding: 40px;
                border-radius: 10px;
                text-align: center;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            a {
                color: #8C5B3F;
                text-decoration: none;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class='erro'>
            <h2>Nenhum método de pagamento selecionado.</h2>
            <p><a href='pagamento.php'>Voltar e tentar novamente</a></p>
        </div>
    </body>
    </html>
    ";
    exit;
}

$metodo = $_POST['pagamento'];

// Redireciona conforme o método selecionado
switch ($metodo) {
    case 'pix':
        header("Location: pix.php");
        exit;
    case 'cartao':
        header("Location: cartao.php");
        exit;
    case 'boleto':
        header("Location: boleto.php");
        exit;
    default:
        echo "
        <html lang='pt-BR'>
        <head>
            <meta charset='UTF-8'>
            <title>Método inválido</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: #f9f9f9;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }
                .erro {
                    background: white;
                    padding: 40px;
                    border-radius: 10px;
                    text-align: center;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                a {
                    color: #8C5B3F;
                    text-decoration: none;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <div class='erro'>
                <h2>⚠️ Método de pagamento inválido!</h2>
                <p><a href='pagamento.php'>Voltar e tentar novamente</a></p>
            </div>
        </body>
        </html>
        ";
        exit;
}
