<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../css/cadastro.css">
    <style>
        /* Estilo básico para alinhar Nome e CPF lado a lado */
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

        /* Caso queira Nome maior que CPF */
        .campo.nome {
            flex: 1;
        }

        
    </style>
</head>
<body>
    <form action="cadastrobd.php" method="post" enctype="multipart/form-data" class="form-cadastro">
        <h2>Bem-Vindo! Cadastre-se com suas informações abaixo</h2>

        <div class="linha">
            <div class="campo nome">
                <label for="CampNome">Nome:</label>
                <input type="text" name="nome" required>
            </div>

            <div class="campo CPF">
                <label for="CampCPF">CPF:</label>
                <input type="text" name="cpf" required>
            </div>
        </div>
        
        <div class="linha">
            <div class="campo CEP">
            <label for="CampCep">CEP:</label>
        <input type="text" name="cep" required>
            </div>

            <div class="campo Telefone">
            <label for="CampTelefone">Telefone:</label>
        <input type="text" name="telefone" required>
            </div>
        </div>

        <div class="linha">
            <div class="campo Email">
            <label for="CampEmail">Email:</label>
        <input type="email" name="email" required>
            </div>

            <div class="campo Senha">
            <label for="CampSenha">Senha:</label>
        <input type="password" name="senha" required>
            </div>
        </div>      

        <div class="campo">
        <label for="CampFoto">Sua foto de preferência:</label>
        <input type="file" name="foto_de_perfil" accept="image/*">

        <input type="submit" value="Cadastrar" class="btn-vermelho">

        <div class="botoes-inicio">
            <a href="../login/login.php" class="btn-primario">Já possui uma conta? Entrar</a>
        </div>
    </form>
</body>
</html>
