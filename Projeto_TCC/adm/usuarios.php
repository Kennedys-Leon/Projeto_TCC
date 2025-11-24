<?php
require __DIR__ . '/includes/admin_header.php';

// LISTAR TODOS CAMPOS
$stmt = $pdo->query("
    SELECT 
        idusuario,
        nome,
        cpf,
        cep,
        endereco,
        cidade,
        estado,
        bairro,
        telefone,
        email,
        ativo,
        status_conta,
        foto_de_perfil
    FROM usuario
    ORDER BY idusuario DESC
");

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
<h2>Usuários</h2>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Nome</th>
    <th>Email</th>
    <th>Telefone</th>
    <th>CPF</th>
    <th>Cidade</th>
    <th>Status</th>
    <th>Foto</th>
    <th>Ações</th>
</tr>
</thead>

<tbody>
<?php foreach($users as $u): ?>
<tr>

<td><?= $u['idusuario'] ?></td>
<td><?= htmlspecialchars($u['nome']) ?></td>
<td><?= htmlspecialchars($u['email']) ?></td>
<td><?= htmlspecialchars($u['telefone']) ?></td>

<td><?= htmlspecialchars($u['cpf']) ?></td>
<td><?= htmlspecialchars($u['cidade']) ?></td>

<td>
    <?= htmlspecialchars($u['status_conta'] ?? ($u['ativo'] ? 'ativo' : 'desativado')) ?>
</td>

<td>
    <?php if (!empty($u['foto_de_perfil'])): ?>
        <img src="data:image/jpeg;base64,<?= base64_encode($u['foto_de_perfil']) ?>" width="50" height="50" style="border-radius:50%;">
    <?php else: ?>
        <span>Sem foto</span>
    <?php endif; ?>
</td>

<td>
    <form method="post" action="actions/usuario_toggle.php" style="display:inline">
        <input type="hidden" name="idusuario" value="<?= $u['idusuario'] ?>">
        <button class="btn btn-primary" type="submit">Ativar/Desativar</button>
    </form>

    <form method="post" action="actions/usuario_delete.php" style="display:inline" onsubmit="return confirm('Excluir usuário?');">
        <input type="hidden" name="idusuario" value="<?= $u['idusuario'] ?>">
        <button class="btn btn-danger" type="submit">Excluir</button>
    </form>
</td>

</tr>
<?php endforeach; ?>
</tbody>
</table>
</section>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
