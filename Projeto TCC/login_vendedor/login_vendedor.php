<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vendedor</title>
    <link rel="stylesheet" href="../css/cadastro.css">
</head>
<body>
    <form action="process_login_vendedor.php" method="post" class="form-cadastro">
        <h2>Olá vendedor! Faça seu login 👇</h2>

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" placeholder="Nome do Vendedor" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Seu Email de Vendas" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" placeholder="Sua Senha" required><br><br>
        
        <input type="submit" value="Entrar" class="btn-vermelho">

        <div class="botoes-inicio">
            <a href="../cadastro_vendedor/cadastrovendedor.php" class="btn-primario">Não tem conta? Cadastre-se agora!</a>
        </div>

        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <p style="color: red; margin-top: 10px;">Email ou senha incorretos.</p>
        <?php endif; ?>
    </form>
</body>
</html>
