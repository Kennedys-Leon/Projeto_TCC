<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
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

        /* Select igual ao input */
        select {
            padding: 10px;
            color: #fff;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            background-color: #4d4970;
            transition: border-color 0.2s ease-in-out;
        }

        select:focus {
            border-color: #78d9f4ff; /* vermelho igual ao botão */
            outline: none;
        }

        /* Modal / preview styles (mesmos usados em cadastro.php) */
        #fileModalProduto {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        #fileModalProduto .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 520px;
            max-width: 95%;
            text-align: center;
            position: relative;
        }
        #fileModalProduto #dropAreaProduto {
            border: 2px dashed #9d9dfc;
            border-radius: 10px;
            padding: 30px;
            cursor: pointer;
        }
        #previewProdutoContainer img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <form id="formProduto" action="cadastroprodutobd.php" method="post" class="form-cadastro" enctype="multipart/form-data">
        <h2>Olá Vendedor, cadastre seus preciosos produtos!</h2>

        <div class="linha">
            <div class="campo">
                <label for="CampNome">Nome do produto:</label>
                <input type="text" id="CampNome" name="nome" maxlength="100" required>
            </div>
            <div class="campo">
                <label for="CampCategoria">Categoria:</label>
                <select id="CampCategoria" name="categoria" required>
                    <option value="" disabled selected>Selecione</option>
                    <option value="Conta de Stream">Serviço de Stream</option>
                    <option value="Gift Card">Gift Card</option>
                    <option value="Itens em Jogos">Itens em Jogos</option>
                    <option value="Conta de Jogo">Conta de Jogo</option>
                    <option value="Jogos">Jogos</option>
                    <option value="Chaves de Jogos">Chaves de Jogos</option>
                </select>
            </div>
        </div>

        <div class="linha">
            <div class="campo">
                <label for="CampPreco">Preço:</label>
                <input type="text" id="CampPreco" name="preco" placeholder="Ex: 49,90" required>
            </div>
            <div class="campo">
                <label for="CampQuantidade_estoque">Quantidade de estoque:</label>
                <input type="number" id="CampQuantidade_estoque" name="quantidade_estoque" min="1" required>
            </div>
        </div>

        <div class="linha">
            <div class="campo">
                <label for="CampData_pub">Data de publicação:</label>
                <input type="text" id="CampData_pub" name="data_pub" maxlength="10" placeholder="dd/mm/aaaa" required>
            </div>
            <div class="campo">
                <label>Imagem do seu produto:</label>

                <!-- botão + preview (substitui input file visível) -->
                <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10px;">
                    <button type="button" id="openModalProdutoBtn" class="btn-vermelho" style="width:200px;">Escolher arquivos</button>
                    <div id="previewProdutoContainer" style="display:flex; gap:8px; margin-top:10px; flex-wrap:wrap;"></div>
                </div>

                <!-- input file real (escondido) -->
                <input type="file" id="imagem" name="imagens[]" accept="image/*" multiple required style="display:none;">
            </div>
        </div>

        <div class="campo">
            <label for="CampDescricao">Descrição do produto:</label>
            <textarea id="CampDescricao" name="descricao" rows="4" maxlength="1000" required></textarea>
        </div>

        <input type="submit" value="Cadastrar" class="btn-vermelho">

        <div class="botoes-inicio">
            <a href="../index.php" class="btn-primario">Voltar</a>
        </div>
    </form>

    <!-- Modal de seleção/arraste para imagens (produto) -->
    <div id="fileModalProduto" role="dialog" aria-modal="true">
        <div class="modal-content">
            <h3>Escolha ou arraste as imagens do produto</h3>
            <div id="dropAreaProduto">
                <p>Arraste as imagens aqui ou clique para escolher</p>
                <input type="file" id="modalFileProdutoInput" accept="image/*" multiple style="display:none;">
            </div>
            <div id="modalPreviewProduto" style="display:flex; gap:8px; margin-top:12px; flex-wrap:wrap;"></div>
            <button id="closeModalProdutoBtn" style="margin-top:15px; background:#131318; color:#eaeaea; border:none; padding:10px 20px; border-radius:7px; cursor:pointer;">Fechar</button>
        </div>
    </div>

    <script>
        // Máscara para data dd/mm/aaaa
        function mascaraData(valor) {
            return valor.replace(/\D/g, "")
                        .replace(/(\d{2})(\d)/, "$1/$2")
                        .replace(/(\d{2})(\d)/, "$1/$2")
                        .replace(/(\d{4})(\d)/, "$1");
        }

        document.getElementById("CampData_pub").addEventListener("input", function(e) {
            e.target.value = mascaraData(e.target.value);
        });

        // Máscara para preço (formato brasileiro: 1.234,56)
        function mascaraPreco(valor) {
            valor = valor.replace(/\D/g, ""); // só números
            valor = (parseInt(valor, 10) / 100).toFixed(2) + "";
            valor = valor.replace(".", ",");
            return valor.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        document.getElementById("CampPreco").addEventListener("input", function(e) {
            let cursor = e.target.selectionStart;
            e.target.value = mascaraPreco(e.target.value);
            e.target.setSelectionRange(cursor, cursor);
        });

        // Antes de enviar, converter data e preço para MySQL
        document.getElementById("formProduto").addEventListener("submit", function () {
            let dataInput = document.getElementById("CampData_pub");
            let precoInput = document.getElementById("CampPreco");

            // Converter data dd/mm/aaaa -> aaaa-mm-dd
            let partes = dataInput.value.split("/");
            if (partes.length === 3) {
                dataInput.value = partes[2] + "-" + partes[1] + "-" + partes[0];
            }

            // Converter preço para formato do banco (com ponto como decimal)
            precoInput.value = precoInput.value.replace(/\./g, "").replace(",", ".");
        });

        // ===== Modal / Upload de imagens do produto =====
        (function () {
            const openBtn = document.getElementById("openModalProdutoBtn");
            const fileModal = document.getElementById("fileModalProduto");
            const dropArea = document.getElementById("dropAreaProduto");
            const modalFileInput = document.getElementById("modalFileProdutoInput");
            const modalPreview = document.getElementById("modalPreviewProduto");
            const mainInput = document.getElementById("imagem");
            const previewContainer = document.getElementById("previewProductoContainer") || document.getElementById("previewProdutoContainer");
            const closeBtn = document.getElementById("closeModalProdutoBtn");

            // fallback for missing elements
            if (!openBtn || !fileModal || !modalFileInput || !mainInput) return;

            openBtn.addEventListener("click", () => { fileModal.style.display = "flex"; modalPreview.innerHTML = ""; });
            closeBtn.addEventListener("click", () => { fileModal.style.display = "none"; });

            // close modal clicking outside
            fileModal.addEventListener("click", (e) => { if (e.target === fileModal) fileModal.style.display = "none"; });
            document.addEventListener("keydown", (e) => { if (e.key === "Escape") fileModal.style.display = "none"; });

            dropArea.addEventListener("click", () => modalFileInput.click());
            dropArea.addEventListener("dragover", (e) => { e.preventDefault(); dropArea.style.backgroundColor = "#f0f0ff"; });
            dropArea.addEventListener("dragleave", () => { dropArea.style.backgroundColor = ""; });
            dropArea.addEventListener("drop", (e) => {
                e.preventDefault();
                dropArea.style.backgroundColor = "";
                const files = Array.from(e.dataTransfer.files || []).filter(f => f.type.startsWith("image/"));
                if (files.length) handleFilesSelected(files);
            });

            modalFileInput.addEventListener("change", (e) => {
                const files = Array.from(e.target.files || []).filter(f => f.type.startsWith("image/"));
                if (files.length) handleFilesSelected(files);
            });

            function handleFilesSelected(files) {
                // mostrar previews no modal
                modalPreview.innerHTML = "";
                files.forEach(file => {
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        const img = document.createElement("img");
                        img.src = ev.target.result;
                        img.alt = file.name;
                        modalPreview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });

                // colocar arquivos no input real (DataTransfer)
                if (typeof DataTransfer !== "undefined") {
                    const dt = new DataTransfer();
                    files.forEach(f => dt.items.add(f));
                    mainInput.files = dt.files;
                } else {
                    // fallback: se não suportar DataTransfer, não consigo popular input programaticamente em alguns browsers
                    // deixamos o modalFileInput com os arquivos para serem enviados (poderá usar server-side para ler modalFileProdutoInput)
                    modalFileInput.files = files;
                }

                // atualizar preview visível no formulário
                if (previewContainer) {
                    previewContainer.innerHTML = "";
                    Array.from(mainInput.files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = (ev) => {
                            const img = document.createElement("img");
                            img.src = ev.target.result;
                            img.alt = file.name;
                            previewContainer.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            }
        })();
    </script>
</body>
<div vw-plugin-wrapper>
        <div class="vw-plugin-top-wrapper"></div>
    </div>
</div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
      new window.VLibras.Widget('https://vlibras.gov.br/app');
</html>
