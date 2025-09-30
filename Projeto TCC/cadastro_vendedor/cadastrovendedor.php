<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Vendedor</title>
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
    <form id="formCadastro" action="cadastrovendedorbd.php" method="post" enctype="multipart/form-data" class="form-cadastro">
        <h2>Seja Bem-vindo, Novo Vendedor!</h2>

        <!-- Nome e CPF -->
        <div class="linha">
            <div class="campo">
                <label for="CampNome">Nome:</label>
                <input type="text" name="nome" maxlength="100" placeholder="Nome de Vendedor" required>
            </div>

            <div class="campo">
                <label for="CampCPF">CPF:</label>
                <input type="text" id="cpf" name="cpf" maxlength="14" placeholder="000.000.000-00" required>
            </div>
        </div>

        <!-- Telefone e Email -->
        <div class="linha">
            <div class="campo">
                <label for="CampTelefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" maxlength="15" placeholder="(00) 00000-0000" required>
            </div>

            <div class="campo">
                <label for="CampEmail">Email:</label>
                <input type="email" name="email" maxlength="150" placeholder="nome@exemplo.com" required>
            </div>
        </div>

        <!-- Senha e CNPJ -->
        <div class="linha">
            <div class="campo">
                <label for="CampSenha">Senha:</label>
                <input type="password" name="senha" minlength="" maxlength="32" required>
            </div>

            <div class="campo">
                <label for="CampCNPJ">CNPJ:</label>
                <input type="text" id="cnpj" name="cnpj" maxlength="18" placeholder="00.000.000/0000-00">
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

    <script>
        // Máscaras
        function mascaraCpf(valor) {
            return valor.replace(/\D/g, "")
                        .replace(/(\d{3})(\d)/, "$1.$2")
                        .replace(/(\d{3})(\d)/, "$1.$2")
                        .replace(/(\d{3})(\d{1,2})$/, "$1-$2");
        }

        function mascaraTelefone(valor) {
            return valor.replace(/\D/g, "")
                        .replace(/^(\d{2})(\d)/g, "($1) $2")
                        .replace(/(\d{5})(\d)/, "$1-$2");
        }

        function mascaraCnpj(valor) {
            return valor.replace(/\D/g, "")
                        .replace(/^(\d{2})(\d)/, "$1.$2")
                        .replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3")
                        .replace(/\.(\d{3})(\d)/, ".$1/$2")
                        .replace(/(\d{4})(\d)/, "$1-$2");
        }

        // Eventos de digitação
        document.getElementById("cpf").addEventListener("input", function(e) {
            e.target.value = mascaraCpf(e.target.value);
        });

        document.getElementById("telefone").addEventListener("input", function(e) {
            e.target.value = mascaraTelefone(e.target.value);
        });

        document.getElementById("cnpj").addEventListener("input", function(e) {
            e.target.value = mascaraCnpj(e.target.value);
        });

        // Antes de enviar, limpar os caracteres
        document.getElementById("formCadastro").addEventListener("submit", function () {
            let cpf = document.getElementById("cpf");
            let telefone = document.getElementById("telefone");
            let cnpj = document.getElementById("cnpj");

            cpf.value = cpf.value.replace(/\D/g, "");
            telefone.value = telefone.value.replace(/\D/g, "");
            cnpj.value = cnpj.value.replace(/\D/g, "");
        });
    </script>
</body>
</html>
