<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../css/cadastro.css">
    <script>
        function toggleVendedorFields() {
            var tipoUsuario = document.querySelector('select[name="tipo_usuario"]').value;
            var vendedorFields = document.getElementById("vendedorFields");

            if (tipoUsuario === "vendedor") {
                vendedorFields.style.display = "block";
            } else {
                vendedorFields.style.display = "none";
            }
        }
    </script>
</head>
<body>
    <form action="cadastrobd.php" method="post" class="form-cadastro">
        <h2>Cadastro</h2>

        <label for="campNome">Nome:</label>
        <input type="text" name="campNome" required>

        <label for="CampCPF">CPF:</label>
        <input type="text" name="CampCPF" required>

        <label for="CampEndereco">Endereço:</label>
        <input type="text" name="CampEndereco" required>

        <label for="CampTelefone">Telefone:</label>
        <input type="text" name="CampTelefone" required>

        <label for="CampEmail">Email:</label>
        <input type="email" name="CampEmail" required>

        <label for="CampSenha">Senha:</label>
        <input type="password" name="CampSenha" required>

        <label for="tipo_usuario">Tipo de Conta:</label>
        <select name="tipo_usuario" required onchange="toggleVendedorFields()">
            <option value="" disabled selected>Selecione</option>
            <option value="usuario">Usuário Comum</option>
            <option value="vendedor">Vendedor</option>
        </select>

        <div id="vendedorFields" style="display: none;">
            <label for="empresa">Nome da Empresa:</label>
            <input type="text" name="empresa" id="empresa" placeholder="Nome da sua empresa">

            <label for="cnpj">CNPJ:</label>
            <input type="text" name="cnpj" id="cnpj" placeholder="CNPJ da empresa" required>

            <label for="categoria">Categoria de Produtos:</label>
            <input type="text" name="categoria" id="categoria" placeholder="Categoria de produtos que você vende" required>
        </div>

        <input type="submit" value="Cadastrar" class="btn-vermelho">

        <div class="botoes-inicio">
            <a href="../login/login.php" class="btn-primario">Já possui uma conta? Entrar</a>
        </div>
    </form>
</body>
</html>
