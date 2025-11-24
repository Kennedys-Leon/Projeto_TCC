<?php
require __DIR__ . '/includes/admin_header.php';

// LISTAR VENDEDOR COM TODOS OS CAMPOS IMPORTANTES
$stmt = $pdo->query("
    SELECT 
        idvendedor,
        nome,
        cpf,
        telefone,
        email,
        cnpj,
        foto_de_perfil,
        idparticipacao_percentual,
        ativo,
        status_conta
    FROM vendedor
    ORDER BY idvendedor DESC
");

$vendedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
<h2>Vendedores</h2>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Nome</th>
    <th>Email</th>
    <th>Telefone</th>
    <th>CPF</th>
    <th>CNPJ</th>
    <th>Nível</th>
    <th>Status</th>
    <th>Foto</th>
    <th>Ações</th>
</tr>
</thead>

<tbody>
<?php foreach($vendedores as $v): ?>
<tr>

<td><?= $v['idvendedor'] ?></td>
<td><?= htmlspecialchars($v['nome']) ?></td>
<td><?= htmlspecialchars($v['email']) ?></td>
<td><?= htmlspecialchars($v['telefone']) ?></td>
<td><?= htmlspecialchars($v['cpf']) ?></td>
<td><?= htmlspecialchars($v['cnpj']) ?></td>
<td><?= htmlspecialchars($v['idparticipacao_percentual']) ?></td>

<td>
    <?= htmlspecialchars($v['status_conta'] ?? ($v['ativo'] ? 'ativo' : 'desativado')) ?>
</td>

<td>
    <?php if (!empty($v['foto_de_perfil'])): ?>
        <img src="data:image/jpeg;base64,<?= base64_encode($v['foto_de_perfil']) ?>" width="50" height="50" style="border-radius:50%;">
    <?php else: ?>
        <span>Sem foto</span>
    <?php endif; ?>
</td>

<td>

    <form method="post" action="actions/vendedor_toggle.php" style="display:inline">
        <input type="hidden" name="idvendedor" value="<?= $v['idvendedor'] ?>">
        <button class="btn btn-primary" type="submit">Ativar/Desativar</button>
    </form>

    <form method="post" action="actions/vendedor_delete.php" style="display:inline" onsubmit="return confirm('Excluir vendedor?');">
        <input type="hidden" name="idvendedor" value="<?= $v['idvendedor'] ?>">
        <button class="btn btn-danger" type="submit">Excluir</button>
    </form>

</td>

</tr>
<?php endforeach; ?>
</tbody>
</table>
</section>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
