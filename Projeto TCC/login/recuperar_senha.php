<?php
// opcional: mensagens de erro ou sucesso
$mensagem = $_GET['msg'] ?? '';
$erro = isset($_GET['erro']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>

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
        box-shadow: 0 0 10px rgba(0,0,0,0.25);
        color: #fff;
        text-align: center;
    }

    h2 {
        margin-bottom: 25px;
        color: #fff;
        font-size: 22px;
        font-weight: bold;
    }

    label {
        display: block;
        text-align: left;
        margin-bottom: 6px;
        font-weight: bold;
        color: #ddd;
        margin-top: 10px;
    }

    input[type=email],
    select {
        width: 99%;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #444;
        background: #111;
        color: #fff;
        margin-bottom: 18px;
        font-size: 15px;
    }

    input::placeholder {
        color: #bbb;
    }

    button {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 8px;
        background: #070707;
        color: white;
        font-size: 16px;
        cursor: pointer;
        transition: 0.2s;
        margin-top: 5px;
    }

    button:hover {
        background: #000;
    }

    .msg {
        padding: 12px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .erro {
        background: #ffdddd;
        color: #a30000;
    }

    .sucesso {
        background: #ddffdd;
        color: #007700;
    }
    </style>
</head>
<body>

<div class="container">
    <h2>Recuperação ou Troca de Senha</h2>

    <?php if ($mensagem): ?>
        <div class="msg <?= $erro ? 'erro' : 'sucesso' ?>">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php endif; ?>

    <form action="processa_recuperacao.php" method="POST">

        <label>Você é:</label>
        <select id="tipoUsuario" name="tipo" required>
            <option value="">Selecione...</option>
            <option value="usuario">Usuário</option>
            <option value="vendedor">Vendedor</option>
        </select>

        <label>Digite seu e-mail:</label>
        <input type="email" name="email" placeholder="email@exemplo.com" required>

        <button type="submit">Enviar código de recuperação</button><br><br>

        <button id="linkRetorno" type="button" class="voltar" style="background:none; border:none; color:#fff; cursor:pointer; font-size:15px;">
            ⬅ Retornar ao início
        </button>
    </form>
</div>

<script>
    const tipo = document.getElementById('tipoUsuario');
    const btnRetorno = document.getElementById('linkRetorno');

    btnRetorno.addEventListener('click', function () {

        if (tipo.value === "") {
            alert("Selecione se você é Usuário ou Vendedor antes de retornar.");
            return;
        }

        if (tipo.value === "vendedor") {
            window.location.href = "../painel_vendedor/painel_vendedor.php";
        } else {
            window.location.href = "../index.php";
        }
    });
</script>


</body>
</html>
