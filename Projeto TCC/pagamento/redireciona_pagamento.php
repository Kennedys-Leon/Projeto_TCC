<?php
session_start();

if (!isset($_POST['pagamento'])) {
    echo "Nenhum método selecionado.";
    exit;
}

$metodo = $_POST['pagamento'];

// Redireciona para páginas específicas
if ($metodo === 'pix') {
    header("Location: pix.php");
    exit;
} elseif ($metodo === 'cartao') {
    header("Location: cartao.php");
    exit;
} elseif ($metodo === 'boleto') {
    header("Location: boleto.php");
    exit;
} else {
    echo "Método inválido.";
    exit;
}
