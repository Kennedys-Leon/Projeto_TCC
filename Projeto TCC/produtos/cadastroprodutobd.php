<?php
session_start();

include '../cadastro/conexao.php';

$nome = $_POST['nome'];
$preco = $_POST['preco'];
$categoria = $_POST['categoria'];
$quantidade_estoque = $_POST['quantidade_estoque'];
$data_pub = $_POST['data_pub'];
$descricao = $_POST['descricao'];

$vendedor_id = $_SESSION['idvendedor'] ?? null;

$data_formatada = DateTime::createFromFormat('d/m/Y', $data_pub);
if ($data_formatada) {
    $data_pub = $data_formatada->format('Y-m-d');
} else {
    $data_pub = null;
}

try {
    if ($vendedor_id) {
        $sql = "INSERT INTO produto (nome, preco, quantidade_estoque, categoria, data_pub, descricao, vendedor_idvendedor) 
                VALUES ('$nome', '$preco', '$quantidade_estoque', '$categoria', '$data_pub', '$descricao', '$vendedor_id')";
        $pdo->exec($sql);

        echo "Cadastro realizado com sucesso!";
        header("Location: ../index.php");
        exit;
    } else {
        echo "Erro: vendedor não identificado!";
    }

} catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$pdo = null;
?>
