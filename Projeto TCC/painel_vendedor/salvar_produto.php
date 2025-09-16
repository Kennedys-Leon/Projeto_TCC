<?php
session_start();
include '../conexao.php';

// Verifica se o vendedor está logado
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../login_vendedor/login_vendedor.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome       = trim($_POST['nome']);
    $preco      = floatval($_POST['preco']);
    $categoria  = trim($_POST['categoria']);
    $quantidade = intval($_POST['quantidade']);

    // Data de publicação: se não vier do formulário, usa data atual
    $data_pub   = !empty($_POST['data_pub']) 
                    ? date("Y-m-d", strtotime($_POST['data_pub'])) 
                    : date("Y-m-d");

    $descricao  = trim($_POST['descricao']);
    $idvendedor = intval($_POST['idvendedor']);

    if (!empty($nome) && $preco > 0 && $quantidade >= 0 && !empty($descricao)) {
        try {
            $pdo->beginTransaction();

            // 1) Insere produto
            $stmt = $pdo->prepare("
                INSERT INTO produto 
                (nome, preco, categoria, quantidade_estoque, data_pub, descricao, idvendedor) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$nome, $preco, $categoria, $quantidade, $data_pub, $descricao, $idvendedor]);

            // pega o ID do produto recém criado
            $idproduto = $pdo->lastInsertId();

            // 2) Upload de imagem (se existir)
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $imagemTmp  = $_FILES['imagem']['tmp_name'];
                $imagemData = file_get_contents($imagemTmp);

                $stmtImg = $pdo->prepare("INSERT INTO imagens (imagem, idproduto) VALUES (?, ?)");
                $stmtImg->bindParam(1, $imagemData, PDO::PARAM_LOB);
                $stmtImg->bindParam(2, $idproduto, PDO::PARAM_INT);
                $stmtImg->execute();
            }

            $pdo->commit();

            // Redireciona de volta ao painel do vendedor
            header("Location: painel_vendedor.php?msg=produto_cadastrado");
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            die("Erro ao salvar produto: " . $e->getMessage());
        }
    } else {
        header("Location: painel_vendedor.php?msg=campos_invalidos");
        exit;
    }
} else {
    header("Location: painel_vendedor.php");
    exit;
}
