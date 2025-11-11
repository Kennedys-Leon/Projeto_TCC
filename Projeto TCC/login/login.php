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
        <h2>Olá usuário! Insira seu Login👇</h2>

        <label for="nome">Nome:</label>
        <input type="text" name="nome" required>

        <label for="email">Email:</label>
        <input type="text" name="email" required>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" required>

        <!-- Mensagem de erro exibida logo abaixo do campo senha -->
        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <p class="error-msg" role="alert" style="color:#e74c3c; margin-top:8px; font-size:0.95rem;">Email ou senha incorreta.</p>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] == 2): ?>
            <p class="error-msg" style="color:#e67e22; margin-top:8px; font-size:0.95rem;">
                Sua conta está desativada. 
                <a href="reativar_conta.php?email=<?= urlencode($_GET['email'] ?? '') ?>" 
                style="color:#3498db; text-decoration:underline;">
                Clique aqui para reativar sua conta.
                </a>
            </p>
        <?php endif; ?>


        <input type="submit" value="Entrar" class="btn-vermelho">

        <div class="botoes-inicio">
            <a href="../cadastro_usuario/cadastro.php" class="btn-primario">Não tem conta? Cadastre-se</a>
        </div>
    </form>
</body>
</html>
