<?php
require __DIR__ . '/includes/admin_header.php';
require __DIR__ . '/../conexao.php';

/* ============================================================
   CRIAR CATEGORIA
============================================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'criar') {

    $nome = trim($_POST['nome']);
    $slug = trim($_POST['slug']) ?: strtolower(preg_replace('/[^a-z0-9]+/', '-', $nome));
    $icone = trim($_POST['icone']);

    $sql = $pdo->prepare("
        INSERT INTO categorias (nome, slug, icone)
        VALUES (?, ?, ?)
    ");
    $sql->execute([$nome, $slug, $icone]);

    header("Location: categorias.php?add=ok");
    exit;
}

/* ============================================================
   EDITAR CATEGORIA
============================================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'editar') {

    $id = intval($_POST['id']);
    $nome = trim($_POST['nome']);
    $slug = trim($_POST['slug']) ?: strtolower(preg_replace('/[^a-z0-9]+/', '-', $nome));
    $icone = trim($_POST['icone']);

    $up = $pdo->prepare("
        UPDATE categorias
        SET nome = ?, slug = ?, icone = ?
        WHERE id = ?
    ");
    $up->execute([$nome, $slug, $icone, $id]);

    header("Location: categorias.php?edit=ok");
    exit;
}

/* ============================================================
   LISTAR
============================================================ */
$cats = $pdo->query("SELECT * FROM categorias ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
<h2>Categorias</h2>

<!-- ===================== CRIAR ===================== -->
<h3>Criar Categoria</h3>

<form method="post">
    <input type="hidden" name="acao" value="criar">

    <input name="nome" placeholder="Nome da categoria" required>
    <input name="slug" placeholder="Slug (opcional)">

    <label>Ícone (PNG/JPG):</label>

    <div id="dropIcon_create" 
        style="border:2px dashed #888; padding:20px; text-align:center; cursor:pointer;">
        Arraste uma imagem ou clique aqui
    </div>

    <input type="file" id="inputIcon_create" accept="image/*" style="display:none;">

    <!-- Base64 -->
    <input type="hidden" name="icone" id="iconeBase64_create">

    <button class="btn btn-primary">Criar</button>
</form>

<br><hr><br>

<!-- ===================== LISTAR ===================== -->

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Nome</th>
    <th>Slug</th>
    <th>Ícone</th>
    <th>Ações</th>
</tr>
</thead>
<tbody>

<?php foreach ($cats as $c): ?>
<tr>
<td><?= $c['id'] ?></td>
<td><?= htmlspecialchars($c['nome']) ?></td>
<td><?= htmlspecialchars($c['slug']) ?></td>
<td>
    <?php if ($c['icone']): ?>
        <img src="<?= $c['icone'] ?>" width="40">
    <?php endif; ?>
</td>

<td>

<!-- EDITAR -->
<button class="btn btn-warning" type="button"
    onclick='abrirEdicao(<?= json_encode($c, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>)'>
    Editar
</button>

<!-- EXCLUIR -->
<form method="post" action="actions/categoria_delete.php"
      style="display:inline-block"
      onsubmit="return confirm('Excluir categoria?');">

    <input type="hidden" name="id" value="<?= $c['id'] ?>">
    <button class="btn btn-danger">Excluir</button>
</form>

</td>
</tr>
<?php endforeach; ?>

</tbody>
</table>

</section>

<!-- ============================================================
   MODAL EDITAR
============================================================ -->
<div id="modalEditar" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
background:rgba(0,0,0,0.6); justify-content:center; align-items:center;">

    <div style="background:#fff; padding:20px; width:400px; border-radius:8px;">
        <h3>Editar Categoria</h3>

        <form method="post">
            <input type="hidden" name="acao" value="editar">
            <input type="hidden" id="edit_id" name="id">

            <label>Nome:</label>
            <input id="edit_nome" name="nome" required>

            <label>Slug:</label>
            <input id="edit_slug" name="slug">

            <label>Ícone:</label>

            <div id="dropIcon_edit" 
                style="border:2px dashed #888; padding:20px; text-align:center; cursor:pointer;">
                Arraste uma imagem ou clique aqui
            </div>

            <input type="file" id="inputIcon_edit" accept="image/*" style="display:none;">

            <!-- Base64 -->
            <input type="hidden" name="icone" id="iconeBase64_edit">

            <br><br>
            <button class="btn btn-primary">Salvar</button>
            <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
        </form>
    </div>
</div>

<!-- ============================================================
   JAVASCRIPT MODAL
============================================================ -->
<script>
function abrirEdicao(dados) {
    document.getElementById("modalEditar").style.display = "flex";

    document.getElementById("edit_id").value = dados.id;
    document.getElementById("edit_nome").value = dados.nome;
    document.getElementById("edit_slug").value = dados.slug;

    // preview do ícone se existir
    if (dados.icone) {
        document.getElementById("dropIcon_edit").innerHTML =
            `<img src="${dados.icone}" style="width:200px;height:200px;">`;
        document.getElementById("iconeBase64_edit").value = dados.icone;
    }
}

function fecharModal() {
    document.getElementById("modalEditar").style.display = "none";
}
</script>

<!-- ============================================================
   SCRIPT PARA CRIAR E EDITAR (UPLOAD BASE64)
============================================================ -->
<script>
function setupImageUploader(dropId, inputId, hiddenId) {

    const drop = document.getElementById(dropId);
    const input = document.getElementById(inputId);
    const hidden = document.getElementById(hiddenId);

    drop.addEventListener("click", () => input.click());

    drop.addEventListener("dragover", e => {
        e.preventDefault();
        drop.style.borderColor = "#3b82f6";
    });

    drop.addEventListener("dragleave", () => {
        drop.style.borderColor = "#888";
    });

    drop.addEventListener("drop", e => {
        e.preventDefault();
        drop.style.borderColor = "#888";
        processar(e.dataTransfer.files[0]);
    });

    input.addEventListener("change", e => {
        processar(e.target.files[0]);
    });

    function processar(file) {
        if (!file || !file.type.startsWith("image/")) {
            alert("Selecione uma imagem válida.");
            return;
        }

        const reader = new FileReader();
        reader.onload = e => {
            const img = new Image();
            img.onload = () => {

                const canvas = document.createElement("canvas");
                const MAX = 500;

                let w = img.width, h = img.height;

                if (w > h && w > MAX) { h *= MAX / w; w = MAX; }
                else if (h > MAX) { w *= MAX / h; h = MAX; }

                canvas.width = w;
                canvas.height = h;
                canvas.getContext("2d").drawImage(img, 0, 0, w, h);

                const base64 = canvas.toDataURL("image/png");
                hidden.value = base64;

                drop.innerHTML = `<img src="${base64}" width="200" height="200">`;
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

// Criar
setupImageUploader("dropIcon_create", "inputIcon_create", "iconeBase64_create");

// Editar
setupImageUploader("dropIcon_edit", "inputIcon_edit", "iconeBase64_edit");
</script>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>