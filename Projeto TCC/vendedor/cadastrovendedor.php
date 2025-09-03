<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../css/cadastro.css"> 
</head>
<body>
    <form action="cadastrovendedorbd.php" method="post" enctype="multipart/form-data" class="form-cadastro">
        <h2>Seja Bem-vindo, Novo Vendedor!</h2>
        <p>Se Cadastre abaixo com suas informações👇</p>

        <label for="CampNome">Nome:</label>
        <input type="text" name="nome" required>

        <label for="CampCPF">CPF:</label>
        <input type="text" name="cpf" required>

        <label for="CampTelefone">Telefone:</label>
        <input type="text" name="telefone" required>

        <label for="CampEmail">Email:</label>
        <input type="email" name="email" required>

        <label for="CampSenha">Senha:</label>
        <input type="password" name="senha" required>

        <label for="CampCNPJ">CNPJ:</label>
        <input type="text" name="cnpj">

        <label>Sua foto de preferência:</label>
        <input type="file" name="foto" accept="image/*"><br><br>


        <input type="submit" value="Criar conta" class="btn-vermelho">

        <div class="botoes-inicio">
            <a href="../login_vendedor/login_vendedor.php" class="btn-primario">Já possui uma conta? Entrar</a>
        </div>
    </form>
</body>
</html>
