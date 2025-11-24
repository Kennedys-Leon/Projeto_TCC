<?php
require '../conexao.php';

// ------------------------------------------
// 1. VERIFICA SE RECEBEU O TOKEN E O TIPO
// ------------------------------------------
$token = $_GET['token'] ?? '';
$tipo  = $_GET['tipo'] ?? '';

if (empty($token) || empty($tipo)) {
    die("Token inválido.");
}

// Define tabela e campo ID
if ($tipo === "usuario") {
    $tabela = "usuario";
    $campo_id = "idusuario";
} elseif ($tipo === "vendedor") {
    $tabela = "vendedor";
    $campo_id = "idvendedor";
} else {
    die("Tipo inválido.");
}

// ------------------------------------------
// 2. VERIFICAR TOKEN NO BANCO
// ------------------------------------------
$sql = $pdo->prepare("
    SELECT $campo_id AS id, token_expira 
    FROM $tabela 
    WHERE reset_token = ? LIMIT 1
");
$sql->execute([$token]);
$dados = $sql->fetch(PDO::FETCH_ASSOC);

if (!$dados) {
    die("Token inválido ou já utilizado.");
}

$expira = strtotime($dados['token_expira']);

if ($expira < time()) {
    die("O link expirou. Solicite a recuperação novamente.");
}

$id = $dados['id'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
    <style>
        body {
            background: #f7f6f3;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 100%;
            max-width: 420px;
            padding: 30px;
            margin: 80px auto;
            background: #222;
            border-radius: 12px;
            color: #fff;
            text-align: center;
        }
        input[type=password] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: none;
            background: #111;
            color: #fff;
        }
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #070707;
            color: #fff;
            cursor: pointer;
        }
        button:hover { background: #000; }
    </style>
</head>
<body>

<div class="container">
    <h2>Redefinir Senha</h2>

    <form action="salvar_nova_senha.php" method="POST">

        <input type="hidden" name="tipo" value="<?= $tipo ?>">
        <input type="hidden" name="id" value="<?= $id ?>">

        <label>Nova Senha:</label>
        <input type="password" name="senha1" required>

        <label>Confirmar Senha:</label>
        <input type="password" name="senha2" required>

        <button type="submit">Salvar Nova Senha</button>
    </form>
</div>

</body>
</html>
