<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
    <link rel="stylesheet" href="../css/cadastro.css">
</head>
<body>
    <form action="cadastroprodutobd.php" method="post" class="form-cadastro" enctype="multipart/form-data">
        <h2>Olá Vendedor, cadastre seus preciosos produtos!</h2>

        <label for="CampNome">Nome:</label>
        <input type="text" id="CampNome" name="nome" required>

        <label for="CampPreco">Preço:</label>
        <input type="number" id="CampPreco" name="preco" step="0.01" placeholder="Ex: 49.90" required>

        <label for="CampCategoria">Categoria:</label>
        <input type="text" id="CampCategoria" name="categoria" required>

        <label for="CampQuantidade_estoque">Quantidade de estoque:</label>
        <input type="number" id="CampQuantidade_estoque" name="quantidade_estoque" required>

        <label for="CampData_pub">Data de publicação:</label>
        <input type="text" id="CampData_pub" name="data_pub" placeholder="dd/mm/aaaa" required>

        <label for="CampDescricao">Descrição do produto:</label>
        <textarea id="CampDescricao" name="descricao" rows="4" required></textarea>

        <label>Imagem do seu produto:</label>
        <input type="file" id="imagem" name="imagens[]" accept="image/*" multiple required><br><br>

        <input type="submit" value="Cadastrar" class="btn-vermelho">

        <div class="botoes-inicio">
            <a href="../index.php" class="btn-primario">Voltar</a>
        </div>
    </form>
</body>
</html>
