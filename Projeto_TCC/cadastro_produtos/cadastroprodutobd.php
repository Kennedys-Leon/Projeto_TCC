<?php
session_start();

include '../conexao.php';

$nome = $_POST['nome'];
$preco = $_POST['preco'];
$id_categoria = $_POST['id_categoria'];
$id_subcategoria = $_POST['id_subcategoria'];
$quantidade_estoque = $_POST['quantidade_estoque'];
$data_pub = $_POST['data_pub'];
$descricao = $_POST['descricao'];

$vendedor_id = $_SESSION['vendedor_logado'];

$data_formatada = DateTime::createFromFormat('d/m/Y', $data_pub);
if ($data_formatada) {
    $data_pub = $data_formatada->format('Y-m-d');
} else {
    $data_pub = null;
}

try {
    if ($vendedor_id) {
        $sql = "INSERT INTO produto 
            (nome, preco, quantidade_estoque, id_categoria, id_subcategoria, data_pub, descricao, idvendedor) 
            VALUES 
            (:nome, :preco, :quantidade_estoque, :id_categoria, :id_subcategoria, :data_pub, :descricao, :idvendedor)";
        
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':quantidade_estoque', $quantidade_estoque);
        $stmt->bindParam(':id_categoria', $id_categoria);
        $stmt->bindParam(':id_subcategoria', $id_subcategoria);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':idvendedor', $vendedor_id, PDO::PARAM_INT);

        if ($data_pub) {
            $stmt->bindParam(':data_pub', $data_pub);
        } else {
            $stmt->bindValue(':data_pub', null, PDO::PARAM_NULL);
        }

        $stmt->execute();

        $idproduto = $pdo->lastInsertId();

        if (!empty($_FILES['imagens']['name'][0])) {
            foreach ($_FILES['imagens']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['imagens']['error'][$key] === 0) {
                    $imagem = file_get_contents($tmp_name);
                    $sql_img = "INSERT INTO imagens (imagem, idproduto) VALUES (:imagem, :idproduto)";
                    $stmt_img = $pdo->prepare($sql_img);
                    $stmt_img->bindParam(':imagem', $imagem, PDO::PARAM_LOB);
                    $stmt_img->bindParam(':idproduto', $idproduto, PDO::PARAM_INT);
                    $stmt_img->execute();
                }
            }
        } else {
            die("Pelo menos uma imagem deve ser enviada.");
        }

        header("Location: ../painel_vendedor/painel_vendedor.php");
        exit;
    } else {
        echo "Erro: vendedor não identificado!";
    }

} catch(PDOException $e) {
    echo "Erro no banco: " . $e->getMessage();
}

$pdo = null;
?>
