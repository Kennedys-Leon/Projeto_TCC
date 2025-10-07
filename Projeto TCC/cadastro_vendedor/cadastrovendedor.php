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
        <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 10px;">
            <button type="button" id="openModalBtn" class="btn-vermelho" style="width: 200px;">Escolher arquivo</button>
            <img id="preview" src="#" alt="Pré-visualização da imagem de Perfil" style="display:none; width: 150px; height: 150px; margin-top: 10px; border-radius: 8px; object-fit: cover;">
        </div>
        <input type="file" id="foto_de_perfil" name="foto_de_perfil" accept="image/*" style="display:none;">

        <input type="submit" value="Criar conta" class="btn-vermelho">

        <div class="botoes-inicio">
            <a href="../login_vendedor/login_vendedor.php" class="btn-primario">Já possui uma conta? Entrar</a>
        </div>
    </form>

    <!-- Modal -->
    <div id="fileModal" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 2000; justify-content: center; align-items: center;">
        <div style="background: #ffffffff; padding: 20px; border-radius: 10px; width: 400px; max-width: 90%; text-align: center; position: relative;">
            <h3>Escolha ou arraste a imagem</h3>
            <div id="dropArea" style="border: 2px dashed #9d9dfc; border-radius: 10px; padding: 30px; cursor: pointer;">
                <p>Arraste a imagem aqui ou clique para escolher</p>
                <input type="file" id="modalFileInput" accept="image/*" style="display:none;">
            </div>
            <img id="modalPreview" src="../img/bobeira.jpg" alt="Pré-visualização da imagem" style="display:none; width: 50px; height: 50px; margin: 15px auto 0 auto; border-radius: 8px; object-fit: cover; display: block;">
            <button id="closeModalBtn" style="margin-top: 15px; background: #131318; color: #eaeaea; border: none; padding: 10px 20px; border-radius: 7px; cursor: pointer;">Fechar</button>
        </div>
    </div>

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

        // Modal open/close
        const openModalBtn = document.getElementById("openModalBtn");
        const fileModal = document.getElementById("fileModal");
        const closeModalBtn = document.getElementById("closeModalBtn");
        const dropArea = document.getElementById("dropArea");
        const modalFileInput = document.getElementById("modalFileInput");
        const mainFileInput = document.getElementById("foto_de_perfil");
        const modalPreview = document.getElementById("modalPreview");
        const mainPreview = document.getElementById("preview");

        openModalBtn.addEventListener("click", () => {
            fileModal.style.display = "flex";
        });

        closeModalBtn.addEventListener("click", () => {
            fileModal.style.display = "none";
        });

        // Clicking drop area triggers file input click
        dropArea.addEventListener("click", () => {
            modalFileInput.click();
        });

        // Handle file selection in modal
        modalFileInput.addEventListener("change", (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    modalPreview.src = e.target.result;
                    modalPreview.style.display = "block";
                    mainPreview.src = e.target.result;
                    mainPreview.style.display = "block";
                    mainFileInput.files = modalFileInput.files;
                }
                reader.readAsDataURL(file);
            }
        });

        // Drag and drop in modal
        dropArea.addEventListener("dragover", (event) => {
            event.preventDefault();
            dropArea.style.borderColor = "#00c9ff";
        });

        dropArea.addEventListener("dragleave", (event) => {
            event.preventDefault();
            dropArea.style.borderColor = "#9d9dfc";
        });

        dropArea.addEventListener("drop", (event) => {
            event.preventDefault();
            dropArea.style.borderColor = "#9d9dfc";
            if (event.dataTransfer.files.length > 0) {
                modalFileInput.files = event.dataTransfer.files;
                const changeEvent = new Event('change');
                modalFileInput.dispatchEvent(changeEvent);
            }
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
