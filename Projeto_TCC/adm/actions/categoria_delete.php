<?php
require '../../conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);

    if ($id > 0) {

        // Verificar se existem subcategorias antes de excluir
        $check = $pdo->prepare("SELECT COUNT(*) FROM subcategorias WHERE id_categoria = ?");
        $check->execute([$id]);
        $tem_sub = $check->fetchColumn();

        if ($tem_sub > 0) {
            // Bloqueia exclusão se existirem subcategorias
            header("Location: ../categorias.php?erro=subcategorias");
            exit;
        }

        // Excluir categoria
        $sql = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
        $sql->execute([$id]);
    }
}

header("Location: ../categorias.php");
exit;
