<?php
session_start();

// encaminhar POST para o processador sem mudar o resto da página
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/salvar_perfil.php';
    exit;
}

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

        /* drop area */
        .drop-area {
            border: 2px dashed #9d9dfc;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
            cursor: pointer;
            max-width: 280px;
            margin: 0 auto 12px auto;
            background: #fff;
        }
        .drop-area.dragover {
            background: #f0f0ff;
        }
    </style>

    <!-- ADICIONE: SweetAlert2 (necessário para as confirmações) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <form id="formPerfil" class="form-cadastro" method="POST" action="perfil.php" enctype="multipart/form-data">
        <h2>Meu Perfil</h2>

        <!-- Mensagem de sucesso -->
        <?php if (isset($_GET['sucesso'])): ?>
            <p class="sucesso">Perfil atualizado com sucesso!</p>
        <?php endif; ?>

        <div class="linha">
            <div class="campo">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($nome) ?>">
            </div>
            <div class="campo">
                <label for="cpf">CPF</label>
                <input type="text" name="cpf" id="cpf" maxlength="14" value="<?= htmlspecialchars($cpf) ?>" placeholder="000.000.000-00">
            </div>
        </div>
        <div class="linha">
            <div class="campo">
                <label for="cep">CEP</label>
                <input type="text" name="cep" id="cep" maxlength="9" value="<?= htmlspecialchars($cep) ?>" required>
            </div>
            <div class="campo">
                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" id="telefone" maxlength="14" value="<?= htmlspecialchars($telefone) ?>" placeholder="(XX) XXXXX-XXXX">
            </div>
        </div>
        <div class="linha">
            <div class="campo">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" placeholder="name@example.com">
            </div>
            <div class="campo">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" value="<?= htmlspecialchars($senha) ?>" placeholder="Digite sua nova senha aqui">
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

        <!-- Colocando alteração de foto no final do formulário -->
        <div class="alterar-foto-container" style="margin-top:8px;">
                <label for="foto_perfil" style="cursor:pointer;">Alterar Foto de Perfil</label>
        </div>
        <div style="margin-top:20px; text-align:center;">
            <!-- Foto de perfil (preview) -->
            <img id="previewImg" src="<?= $foto ?>" alt="Foto de Perfil" class="foto-perfil">

            <!-- Drop area para foto (arrastar ou clicar) -->
            <div id="dropArea" class="drop-area" style="margin-top:10px;">ARRASTE a imagem aqui ou clique para selecionar do seu dispositivo</div>
            <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*" style="display:none;">
        </div>

        <input type="submit" value="Salvar Alterações" class="btn-vermelho">

        <!-- Botão para desativar conta -->
        <button id="deactivate-btn" type="button" class="btn-vermelho" style="background:#c00; margin-top:10px;">
            Desativar minha conta
        </button>

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

        document.addEventListener('DOMContentLoaded', () => {
            const cpfEl = document.getElementById("cpf");
            const cepEl = document.getElementById("cep");
            const telefoneEl = document.getElementById("telefone");

            if (cpfEl) cpfEl.addEventListener("input", () => { cpfEl.value = mascaraCPF(cpfEl.value); });
            if (cepEl) cepEl.addEventListener("input", () => { cepEl.value = mascaraCEP(cepEl.value); });
            if (telefoneEl) telefoneEl.addEventListener("input", () => { telefoneEl.value = mascaraTelefone(telefoneEl.value); });

            // ===== Drop / preview logic (mesma lógica do cadastro) =====
            const dropArea = document.getElementById('dropArea');
            const fileInput = document.getElementById('foto_perfil');
            const previewImg = document.getElementById('previewImg');

            function mostrarPreviewFile(file) {
                const reader = new FileReader();
                reader.onload = (ev) => {
                    if (previewImg) {
                        previewImg.src = ev.target.result;
                    }
                };
                reader.readAsDataURL(file);

                // tenta colocar o arquivo no input real para envio
                if (fileInput && typeof DataTransfer !== "undefined") {
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    fileInput.files = dt.files;
                }
            }

            if (dropArea) {
                // clique abre seletor
                dropArea.addEventListener('click', () => fileInput?.click());

                // arrastar sobre
                dropArea.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropArea.classList.add('dragover');
                });
                dropArea.addEventListener('dragleave', () => {
                    dropArea.classList.remove('dragover');
                });
                dropArea.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropArea.classList.remove('dragover');
                    const file = e.dataTransfer.files && e.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        mostrarPreviewFile(file);
                    }
                });
            }

            if (fileInput) {
                fileInput.addEventListener('change', (e) => {
                    const file = e.target.files && e.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        mostrarPreviewFile(file);
                    }
                });
            }
        });

        // Handler para desativar conta (confirmação + POST para desativar_conta.php)
        document.getElementById('deactivate-btn')?.addEventListener('click', function () {
            Swal.fire({
                title: 'Desativar conta?',
                text: 'Sua conta será desativada e você será desconectado. Deseja continuar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, desativar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // envia requisição para desativação
                    fetch('desativar_conta.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
    body: 'confirm=1',
    credentials: 'same-origin' // garante envio do cookie de sessão
})
.then(response => response.json())
.then(json => {
    if (json.success) {
        Swal.fire({ icon: 'success', title: 'Conta desativada', text: json.msg || 'Sua conta foi desativada com sucesso!', timer: 1600, showConfirmButton: false })
            .then(() => window.location.href = '../index.php');
    } else {
        Swal.fire('Erro', json.msg || 'Não foi possível desativar sua conta.', 'error');
    }
})
.catch(() => Swal.fire('Erro', 'Erro na requisição. Tente novamente.', 'error'));
            }
        });
    });
    </script>
</body>
</html>
