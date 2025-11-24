<?php
require __DIR__ . '/includes/admin_header.php';
// listar com vendedor e categoria/subcategoria
$sql = "SELECT p.*, v.nome AS vendedor_nome, c.nome AS categoria_nome, s.nome AS subcategoria_nome
FROM produto p
LEFT JOIN vendedor v ON v.idvendedor = p.idvendedor
LEFT JOIN categorias c ON c.id = p.id_categoria
LEFT JOIN subcategorias s ON s.id = p.id_subcategoria
ORDER BY p.idproduto DESC";
$stmt = $pdo->query($sql);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<section>
<h2>Produtos</h2>
<table>
<thead><tr><th>ID</th><th>Nome</th><th>Vendedor</th><th>Categoria</th><th>Subcategoria</th><th>Preço</th><th>Estoque</th><th>Ações</th></tr></thead>
<tbody>
<?php foreach($produtos as $p): ?>
<tr>
<td><?= $p['idproduto'] ?></td>
<td><?= htmlspecialchars($p['nome']) ?></td>
<td><?= htmlspecialchars($p['vendedor_nome']) ?></td>
<td><?= htmlspecialchars($p['categoria_nome']) ?></td>
<td><?= htmlspecialchars($p['subcategoria_nome']) ?></td>
<td>R$ <?= number_format($p['preco'],2,',','.') ?></td>
<td><?= intval($p['quantidade_estoque']) ?></td>
<td>
<a class="btn btn-primary" href="editar_produto.php?id=<?= $p['idproduto'] ?>">Editar</a>
<form method="post" action="actions/produto_delete.php" style="display:inline" onsubmit="return confirm('Excluir produto?');">
<input type="hidden" name="idproduto" value="<?= $p['idproduto'] ?>">
<button class="btn btn-danger" type="submit">Excluir</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</section>
<?php include __DIR__ . '/includes/admin_footer.php'; ?>