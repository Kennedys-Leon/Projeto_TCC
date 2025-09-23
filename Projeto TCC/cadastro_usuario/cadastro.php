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
            position: relative;
        }

        .campo small {
            color: red;
            font-size: 12px;
            display: none;
            margin-top: 4px;
        }

        .campo input.erro {
            border: 2px solid red;
        }

        .campo.nome {
            flex: 1;
        }
    </style>
</head>
<body>
    <form id="formCadastro" action="cadastrobd.php" method="post" enctype="multipart/form-data" class="form-cadastro">
        <h2>Bem-Vindo! Cadastre-se com suas informações abaixo</h2>

        <div class="linha">
            <div class="campo nome">
                <label for="CampNome">Nome:</label>
                <input type="text" name="nome" required>
            </div>

            <div class="campo CPF">
                <label for="CampCPF">CPF:</label>
                <input type="text" name="cpf" id="cpf" maxlength="14" placeholder="000.000.000-00" required>
                <small id="erro-cpf">CPF inválido.</small>
            </div>
        </div>
        
        <div class="linha">
            <div class="campo CEP">
                <label for="CampCep">CEP:</label>
                <input type="text" name="cep" id="cep" maxlength="9" placeholder="0000-000" required>
                <small id="erro-cep">CEP deve conter 8 dígitos.</small>
            </div>

            <div class="campo Telefone">
                <label for="CampTelefone">Telefone:</label>
                <input type="text" name="telefone" id="telefone" maxlength="14" placeholder="(00) 00000-0000" required>
                <small id="erro-telefone">Telefone inválido.</small>
            </div>
        </div>

        <div class="linha">
            <div class="campo Email">
                <label for="CampEmail">Email:</label>
                <input type="email" name="email" placeholder="nome@exemplo.com" required>
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
        </div>
    </form>

    <script>
        // ==============================
        // Funções de Máscara
        // ==============================
        function mascaraCPF(valor) {
            return valor
                .replace(/\D/g, "")
                .replace(/(\d{3})(\d)/, "$1.$2")
                .replace(/(\d{3})(\d)/, "$1.$2")
                .replace(/(\d{3})(\d{1,2})$/, "$1-$2");
        }

        function mascaraCEP(valor) {
            return valor
                .replace(/\D/g, "")
                .replace(/(\d{5})(\d)/, "$1-$2");
        }

        function mascaraTelefone(valor) {
            return valor
                .replace(/\D/g, "")
                .replace(/^(\d{2})(\d)/g, "($1) $2")
                .replace(/(\d{4,5})(\d{4})$/, "$1-$2");
        }

        document.getElementById("cpf").addEventListener("input", function () {
            this.value = mascaraCPF(this.value);
        });

        document.getElementById("cep").addEventListener("input", function () {
            this.value = mascaraCEP(this.value);
        });

        document.getElementById("telefone").addEventListener("input", function () {
            this.value = mascaraTelefone(this.value);
        });

        // ==============================
        // Validação de CPF
        // ==============================
        function validarCPF(cpf) {
            cpf = cpf.replace(/\D/g, "");
            if (cpf.length !== 11) return false;
            if (/^(\d)\1{10}$/.test(cpf)) return false;

            let soma = 0, resto;

            for (let i = 1; i <= 9; i++) {
                soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
            }
            resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;
            if (resto !== parseInt(cpf.substring(9, 10))) return false;

            soma = 0;
            for (let i = 1; i <= 10; i++) {
                soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
            }
            resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;
            if (resto !== parseInt(cpf.substring(10, 11))) return false;

            return true;
        }

        // ==============================
        // Exibir erros
        // ==============================
        function mostrarErro(campo, idErro, msg) {
            const input = document.getElementById(campo);
            const erro = document.getElementById(idErro);

            if (msg) {
                erro.innerText = msg;
                erro.style.display = "block";
                input.classList.add("erro");
            } else {
                erro.style.display = "none";
                input.classList.remove("erro");
            }
        }

        // ==============================
        // Validação no envio do formulário
        // ==============================
        document.getElementById("formCadastro").addEventListener("submit", function (e) {
            let valido = true;

            const cpf = document.getElementById("cpf").value;
            const cep = document.getElementById("cep").value.replace(/\D/g, "");
            const telefone = document.getElementById("telefone").value;

            // CPF
            if (!validarCPF(cpf)) {
                mostrarErro("cpf", "erro-cpf", "CPF inválido.");
                valido = false;
            } else {
                mostrarErro("cpf", "erro-cpf", "");
            }

            // CEP
            if (cep.length !== 8) {
                mostrarErro("cep", "erro-cep", "CEP deve conter 8 dígitos.");
                valido = false;
            } else {
                mostrarErro("cep", "erro-cep", "");
            }

            // Telefone
            if (telefone.length < 13 || telefone.length > 14) {
                mostrarErro("telefone", "erro-telefone", "Telefone deve ter entre 13 e 14 caracteres.");
                valido = false;
            } else {
                mostrarErro("telefone", "erro-telefone", "");
            }

            if (!valido) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
