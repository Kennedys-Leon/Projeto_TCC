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

        <div class="campo.nome">
            <div class="campo nome">
                <label for="CampNome">Nome de Usúario:</label>
                <input type="text" name="nome" placeholder="Nome de Usuário" required>
            </div>

            <div class="campo Email">
                <label for="CampEmail">Seu Melhor Email:</label>
                <input type="email" name="email" placeholder="nome@exemplo.com" required>
            </div>

            <div class="campo Senha">
                <label for="CampSenha">Sua Senha</label>
                <input type="password" name="senha" placeholder="Senha" required><br>
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
                <a href="../login/login.php" class="btn-primario">Já possui uma conta? Entrar</a>
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
    }

    // ======= Modal / Upload (protegido) =======
    const openModalBtn = document.getElementById("openModalBtn");
    const fileModal = document.getElementById("fileModal");
    const dropArea = document.getElementById("dropArea");
    const modalFileInput = document.getElementById("modalFileInput");
    const modalPreview = document.getElementById("modalPreview");
    const fotoPerfilInput = document.getElementById("foto_de_perfil");
    const preview = document.getElementById("preview");
    const closeModalBtn = document.getElementById("closeModalBtn");

    if (openModalBtn && fileModal) {
        openModalBtn.addEventListener("click", () => { fileModal.style.display = "flex"; });
    }
    if (closeModalBtn && fileModal) {
        closeModalBtn.addEventListener("click", () => { fileModal.style.display = "none"; });
    }
    if (fileModal) {
        // fechar clicando fora do modal
        fileModal.addEventListener("click", (e) => {
            if (e.target === fileModal) fileModal.style.display = "none";
        });
        // fechar com ESC
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") fileModal.style.display = "none";
        });
    }

    if (dropArea && modalFileInput) {
        dropArea.addEventListener("click", () => { modalFileInput.click(); });
        dropArea.addEventListener("dragover", (e) => { e.preventDefault(); dropArea.style.backgroundColor = "#f0f0ff"; });
        dropArea.addEventListener("dragleave", () => { dropArea.style.backgroundColor = ""; });
        dropArea.addEventListener("drop", (e) => {
            e.preventDefault();
            dropArea.style.backgroundColor = "";
            const file = e.dataTransfer.files && e.dataTransfer.files[0];
            if (file && file.type.startsWith("image/")) mostrarPreview(file);
        });

        modalFileInput.addEventListener("change", (e) => {
            const file = e.target.files && e.target.files[0];
            if (file && file.type.startsWith("image/")) mostrarPreview(file);
        });
    }

    function mostrarPreview(file) {
        const reader = new FileReader();
        reader.onload = (ev) => {
            if (modalPreview) { modalPreview.src = ev.target.result; modalPreview.style.display = "block"; }
            if (preview) { preview.src = ev.target.result; preview.style.display = "block"; }

            // tenta colocar o arquivo no input real (DataTransfer pode não existir em browsers muito antigos)
            if (fotoPerfilInput && typeof DataTransfer !== "undefined") {
                const dt = new DataTransfer();
                dt.items.add(file);
                fotoPerfilInput.files = dt.files;
            }
        };
        reader.readAsDataURL(file);
    }

}); // DOMContentLoaded
</script>
</body>
</html>
