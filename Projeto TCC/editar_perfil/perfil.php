<?php
session_start();

$nome      = $_SESSION['nome']      ?? ($_SESSION['usuario_nome'] ?? "");
$cpf       = $_SESSION['cpf']       ?? "";
$cep       = $_SESSION['cep']       ?? "";
$endereco  = $_SESSION['endereco']  ?? "";
$cidade    = $_SESSION['cidade']    ?? "";
$estado    = $_SESSION['estado']    ?? "";
$bairro    = $_SESSION['bairro']    ?? "";
$telefone  = $_SESSION['telefone']  ?? "";
$email     = $_SESSION['email']     ?? "";
$senha     = $_SESSION['senha']     ?? "";

// Foto de perfil (se tiver no banco como LONGBLOB, converte para base64)
if (!empty($_SESSION['usuario_foto'])) {
    $foto = "data:image/jpeg;base64," . base64_encode($_SESSION['usuario_foto']);
} else {
    $foto = "../img/usuario.png"; // caminho padrão
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Usuário</title>
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
        .foto-perfil {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 18px auto;
            display: block;
            border: 2px solid #bbb;
            background: #2d2d4d;
        }
        .sucesso {
            color: #2ecc71;
            font-weight: 500;
            margin-bottom: 18px;
            font-size: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <form class="form-cadastro" method="POST" action="salvar_perfil.php" enctype="multipart/form-data">
        <h2>Meu Perfil</h2>

        <!-- Mensagem de sucesso -->
        <?php if (isset($_GET['sucesso'])): ?>
            <p class="sucesso">✅ Perfil atualizado com sucesso!</p>
        <?php endif; ?>

        <!-- Foto de perfil -->
        <img src="<?= $foto ?>" alt="Foto de Perfil" class="foto-perfil">

        <div class="linha">
            <div class="campo">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($nome) ?>">
            </div>
            <div class="campo">
                <label for="cpf">CPF</label>
                <input type="text" name="cpf" id="cpf" maxlength="11" value="<?= htmlspecialchars($cpf) ?>">
            </div>
        </div>
        <div class="linha">
            <div class="campo">
                <label for="cep">CEP</label>
                <input type="text" name="cep" id="cep" maxlength="8" value="<?= htmlspecialchars($cep) ?>" required>
            </div>
            <div class="campo">
                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" id="telefone" maxlength="14" value="<?= htmlspecialchars($telefone) ?>">
            </div>
        </div>
        <div class="linha">
            <div class="campo">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>">
            </div>
            <div class="campo">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" value="<?= htmlspecialchars($senha) ?>">
            </div>
        </div>
        <div class="linha">
            <div class="campo">
                <label for="endereco">Endereço</label>
                <input type="text" name="endereco" id="endereco" value="<?= htmlspecialchars($endereco) ?>" readonly>
            </div>
            <div class="campo">
                <label for="bairro">Bairro</label>
                <input type="text" name="bairro" id="bairro" value="<?= htmlspecialchars($bairro) ?>" readonly>
            </div>
        </div>
        <div class="linha">
            <div class="campo">
                <label for="cidade">Cidade</label>
                <input type="text" name="cidade" id="cidade" value="<?= htmlspecialchars($cidade) ?>" readonly>
            </div>
            <div class="campo">
                <label for="estado">Estado</label>
                <input type="text" name="estado" id="estado" value="<?= htmlspecialchars($estado) ?>" readonly>
            </div>
        </div>
        <div class="alterar-foto-container">
            <label for="foto_perfil">Alterar Foto de Perfil</label>
            <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*">
        </div>

        <input type="submit" value="Salvar Alterações" class="btn-vermelho">

        <div class="botoes-inicio">
            <a href="../index.php" class="btn-primario">⬅ Voltar para Página Inicial</a>
        </div>
    </form>

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

        document.addEventListener('DOMContentLoaded', () => {
    // ======= Funções de máscara =======
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

    // ======= Referências (podem ser null) =======
    const cpfEl = document.getElementById("cpf");
    const cepEl = document.getElementById("cep");
    const telefoneEl = document.getElementById("telefone");
    const form = document.getElementById("formCadastro");

    // só registra listeners se o elemento existir
    if (cpfEl) cpfEl.addEventListener("input", () => { cpfEl.value = mascaraCPF(cpfEl.value); });
    if (cepEl) cepEl.addEventListener("input", () => { cepEl.value = mascaraCEP(cepEl.value); });
    if (telefoneEl) telefoneEl.addEventListener("input", () => { telefoneEl.value = mascaraTelefone(telefoneEl.value); });

    // ======= Validação CPF =======
    function validarCPF(cpf) {
        if (!cpf) return false;
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

    // ======= Mostrar erro (seguro) =======
    function mostrarErro(campoId, idErro, msg) {
        const input = document.getElementById(campoId);
        const erro = document.getElementById(idErro);
        if (!input || !erro) return; // evita crash se algum elemento faltar

        if (msg) {
            erro.innerText = msg;
            erro.style.display = "block";
            input.classList.add("erro");
        } else {
            erro.innerText = "";
            erro.style.display = "none";
            input.classList.remove("erro");
        }
    }

    // ======= Validação no submit (só se o form existir) =======
    if (form) {
        form.addEventListener("submit", function (e) {
            let valido = true;

            if (cpfEl) {
                const cpf = cpfEl.value;
                if (!validarCPF(cpf)) {
                    mostrarErro("cpf", "erro-cpf", "CPF inválido.");
                    valido = false;
                } else {
                    mostrarErro("cpf", "erro-cpf", "");
                }
            }

            if (cepEl) {
                const cep = (cepEl.value || "").replace(/\D/g, "");
                if (cep.length !== 8) {
                    mostrarErro("cep", "erro-cep", "CEP deve conter 8 dígitos.");
                    valido = false;
                } else {
                    mostrarErro("cep", "erro-cep", "");
                }
            }

            if (telefoneEl) {
                const telefone = telefoneEl.value || "";
                if (telefone.length < 13 || telefone.length > 14) {
                    mostrarErro("telefone", "erro-telefone", "Telefone deve ter entre 13 e 14 caracteres.");
                    valido = false;
                } else {
                    mostrarErro("telefone", "erro-telefone", "");
                }
            }

            if (!valido) e.preventDefault();
        });
    </script>
</body>
</html>
