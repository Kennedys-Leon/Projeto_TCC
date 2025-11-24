<?php
require __DIR__ . '/includes/admin_header.php';
// consultas rápidas
$total_users = $pdo->query('SELECT COUNT(*) FROM usuario')->fetchColumn();
$total_vendedores = $pdo->query('SELECT COUNT(*) FROM vendedor')->fetchColumn();
$total_produtos = $pdo->query('SELECT COUNT(*) FROM produto')->fetchColumn();
?>
<section>
<h2>Dashboard</h2>
<ul>
<li>Usuários: <?= $total_users ?></li>
<li>Vendedores: <?= $total_vendedores ?></li>
<li>Produtos: <?= $total_produtos ?></li>
</ul>
<div class="botoes-inicio">
    <a href="../index.php" class="btn-primario">⬅ Voltar para Página Inicial</a>
</div>
</section>
<?php include __DIR__ . '/includes/admin_footer.php'; ?>