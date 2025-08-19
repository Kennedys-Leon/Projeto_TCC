<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
    <link rel="stylesheet" href="../css/cadastro.css">
    
</head>
<body>
    <form action="cadastroprodutobd.php" method="post" class="form-cadastro">
        <h2>Olá Vendedor! Cadastre seus preciosos produtos</h2>

        <label for="CampNome">Nome do produto:</label>
        <input type="text" name="nome" required>

        <label for="CampPreco">Preço do produto:</label>
        <input type="text" name="preco" required>

        <label for="CampCategoria">Categoria do produto:</label>
        <input type="text" name="categoria" required>

        <label for="CampQuantidade_estoque">Quantidade de estoque:</label>
        <input type="text" name="quantidade_estoque" required>

        <label for="CampData_pub">Data de publicação:</label>
        <input type="text" name="data_pub" required>

        <label for="CampDescricao">Descrição do produto:</label>
        <input type="text" name="descricao" required>

        <input type="submit" value="Cadastrar" class="btn-vermelho">

        <div class="botoes-inicio">
            <a href="../index.php" class="btn-primario">Voltar</a>
        </div>
    </form>
</body>
</html>
