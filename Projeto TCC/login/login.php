<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/cadastro.css">
</head>
<body>
    <form action="process_login.php" method="post" class="form-cadastro">
        <h2>Olá usuário! Logue suas informações👇</h2>

        <label for="nome">Nome:</label>
        <input type="text" name="nome" required>

        <label for="email">Email:</label>
        <input type="text" name="email" required>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" required>

        <input type="submit" value="Entrar" class="btn-vermelho">

        <div class="botoes-inicio">
            <a href="../cadastro_usuario/cadastro.php" class="btn-primario">Não tem conta? Cadastre-se</a>
        </div>
    </form>
    <?php
    if(isset($_GET['error']) && $_GET['error']== 1){ ?>
        <p style="color:red;">Email ou senha Incorreta.</p>
    <?php   } ?>
</body>
</html>
