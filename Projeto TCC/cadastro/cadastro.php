<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../css/cadastro.css">
</head>
<body>
    <form action="cadastrobd.php" method="post" enctype="multipart/form-data" class="form-cadastro">
        <h2>Bem-Vindo! Cadastre-se com suas informações abaixo</h2>

        <label for="CampNome">Nome:</label>
        <input type="text" name="nome" required>

        <label for="CampCPF">CPF:</label>
        <input type="text" name="cpf" required>

        <label for="CampCep">CEP:</label>
        <input type="text" name="cep" required>

        <label for="CampTelefone">Telefone:</label>
        <input type="text" name="telefone" required>

        <label for="CampEmail">Email:</label>
        <input type="email" name="email" required>

        <label for="CampSenha">Senha:</label>
        <input type="password" name="senha" required>

        <label for="CampFoto">Sua foto de preferência:</label>
        <input type="file" name="foto" accept="image/*" required>

        <input type="submit" value="Cadastrar" class="btn-vermelho">

        <div class="botoes-inicio">
            <a href="../login/login.php" class="btn-primario">Já possui uma conta? Entrar</a>
        </div>
    </form>
</body>
</html>
