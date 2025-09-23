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
            border-color: #e63946; /* vermelho igual ao botão */
            outline: none;
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
                    <option value="Conta de Stream">Conta de Stream</option>
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
                <input type="file" id="imagem" name="imagens[]" accept="image/*" multiple required>
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
    </script>
</body>
</html>
