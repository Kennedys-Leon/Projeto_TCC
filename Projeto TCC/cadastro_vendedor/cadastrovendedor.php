<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../css/cadastro.css"> 
    <style>
        .linha {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .campo {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

    </style>
</head>
<body>
    <form action="cadastrovendedorbd.php" method="post" enctype="multipart/form-data" class="form-cadastro">
        <h2>Seja Bem-vindo, Novo Vendedor!</h2>


        <!-- Nome e CPF lado a lado -->
        <div class="linha">
            <div class="campo">
                <label for="CampNome">Nome:</label>
                <input type="text" name="nome" required>
            </div>

            <div class="campo">
                <label for="CampCPF">CPF:</label>
                <input type="text" name="cpf" required>
            </div>
        </div>

        <!-- Telefone e Email lado a lado -->
        <div class="linha">
            <div class="campo">
                <label for="CampTelefone">Telefone:</label>
                <input type="text" name="telefone" required>
            </div>

            <div class="campo">
                <label for="CampEmail">Email:</label>
                <input type="email" name="email" required>
            </div>
        </div>


    <div class="linha">
        <div class="campo">
            <label for="CampSenha">Senha:</label>
            <input type="password" name="senha" required>
        </div>

        <div class="campo">
            <label for="CampCNPJ">CNPJ:</label>
            <input type="text" name="cnpj">
        </div>
    </div>
    

        <!-- Foto -->
        <label>Sua foto de preferência:</label>
        <input type="file" name="foto_de_perfil" accept="image/*"><br><br>

        <input type="submit" value="Criar conta" class="btn-vermelho">

        <div class="botoes-inicio">
            <a href="../login_vendedor/login_vendedor.php" class="btn-primario">Já possui uma conta? Entrar</a>
        </div>
    </form>
</body>
</html>
