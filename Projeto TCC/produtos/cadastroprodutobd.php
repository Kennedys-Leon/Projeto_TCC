<?php
session_start();

include '../cadastro/conexao.php';

$nome = $_POST['nome'];
$preco = $_POST['preco'];
$categoria = $_POST['categoria'];
$quantidade_estoque = $_POST['quantidade_estoque'];
$data_pub = $_POST['data_pub'];
$descricao = $_POST['descricao'];

$vendedor_id = $_SESSION['usuario_logado'] ?? null;

// Trata a data no formato brasileiro (dd/mm/yyyy)
$data_formatada = DateTime::createFromFormat('d/m/Y', $data_pub);
if ($data_formatada) {
    $data_pub = $data_formatada->format('Y-m-d');
} else {
    $data_pub = null;
}

try {
    if ($vendedor_id) {
        $sql = "INSERT INTO produto 
                   (nome, preco, quantidade_estoque, categoria, data_pub, descricao, idvendedor) 
                VALUES 
                   (:nome, :preco, :quantidade_estoque, :categoria, :data_pub, :descricao, :idvendedor)";
        
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':quantidade_estoque', $quantidade_estoque);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':idvendedor', $vendedor_id, PDO::PARAM_INT);

        // Se tiver data válida, envia como string, senão envia NULL
        if ($data_pub) {
            $stmt->bindParam(':data_pub', $data_pub);
        } else {
            $stmt->bindValue(':data_pub', null, PDO::PARAM_NULL);
        }

        $stmt->execute();

        echo "Cadastro realizado com sucesso!";
        header("Location: ../index.php");
        exit;
    } else {
        echo "Erro: vendedor não identificado!";
    }

} catch(PDOException $e) {
    echo "Erro no banco: " . $e->getMessage();
}

$pdo = null;
?>
