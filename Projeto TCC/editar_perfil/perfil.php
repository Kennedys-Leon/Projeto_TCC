<?php
session_start();

$nome      = $_SESSION['nome']      ?? "Usuário";
$cpf       = $_SESSION['cpf']       ?? "";
$cep       = $_SESSION['cep']       ?? "";
$endereco  = $_SESSION['endereco']  ?? "";
$cidade    = $_SESSION['cidade']    ?? "";
$estado    = $_SESSION['estado']    ?? "";
$bairro    = $_SESSION['bairro']    ?? "";
$telefone  = $_SESSION['telefone']  ?? "";
$email     = $_SESSION['email']     ?? "email@exemplo.com";
$senha     = $_SESSION['senha']     ?? "";
$foto      = $_SESSION['foto']      ?? "..img/usuario.png"; // caminho padrão caso não tenha
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        body {
            font-family: Poppins, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px;
        }

        .form-container {
            width: 100%;
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            margin: 10px 0 5px;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }

        button, .btn-voltar {
            width: 100%;
            padding: 12px;
            background: #007BFF;
            border: none;
            color: white;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        button:hover, .btn-voltar:hover {
            background: #0056b3;
        }

        .sucesso {
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .foto-perfil {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 2px solid #ccc;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Editar Perfil</h2>

        <!-- Mensagem de sucesso -->
        <?php if (isset($_GET['sucesso'])): ?>
            <p class="sucesso">✅ Perfil atualizado com sucesso!</p>
        <?php endif; ?>

        <!-- Foto de perfil -->
        <img src="<?= htmlspecialchars($foto) ?>" alt="Foto de Perfil" class="foto-perfil">

        <form method="POST" action="salvar_perfil.php" enctype="multipart/form-data">
            
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($nome) ?>">

            <label for="cpf">CPF</label>
            <input type="text" name="cpf" id="cpf" maxlength="11" value="<?= htmlspecialchars($cpf) ?>">

            <label for="cep">CEP</label>
            <input type="text" name="cep" id="cep" maxlength="8" value="<?= htmlspecialchars($cep) ?>" required>

            <label for="endereco">Endereço</label>
            <input type="text" name="endereco" id="endereco" value="<?= htmlspecialchars($endereco) ?>" readonly>

            <label for="cidade">Cidade</label>
            <input type="text" name="cidade" id="cidade" value="<?= htmlspecialchars($cidade) ?>" readonly>

            <label for="estado">Estado</label>
            <input type="text" name="estado" id="estado" value="<?= htmlspecialchars($estado) ?>" readonly>

            <label for="bairro">Bairro</label>
            <input type="text" name="bairro" id="bairro" value="<?= htmlspecialchars($bairro) ?>" readonly>

            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" maxlength="14" value="<?= htmlspecialchars($telefone) ?>">

            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>">

            <label for="senha">Senha</label>
            <input type="password" name="senha" id="senha" value="<?= htmlspecialchars($senha) ?>">

            <label for="foto_perfil">Alterar Foto de Perfil</label>
            <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*">

            <button type="submit">Salvar Alterações</button>
        </form>

        <!-- Botão de voltar -->
        <a href="usuario.php" class="btn-voltar">⬅ Voltar para Página Inicial</a>
    </div>

    <script>
        // Buscar endereço via ViaCEP
        document.getElementById('cep').addEventListener('blur', function() {
            let cep = this.value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('endereco').value = data.logradouro;
                            document.getElementById('bairro').value = data.bairro;
                            document.getElementById('cidade').value = data.localidade;
                            document.getElementById('estado').value = data.uf;
                        } else {
                            alert('CEP não encontrado!');
                        }
                    })
                    .catch(() => alert('Erro ao buscar CEP!'));
            }
        });
    </script>
</body>
</html>
