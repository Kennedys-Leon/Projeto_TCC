<?php
require __DIR__ . '/includes/admin_header.php';
require __DIR__ . '/../conexao.php';

// =================== CRIAR ===================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'criar') {

    $nome = trim($_POST['nome']);
    $slug = trim($_POST['slug']) ?: strtolower(preg_replace('/[^a-z0-9]+/', '-', $nome));
    $icone = trim($_POST['icone']); // BASE64
    $id_categoria = intval($_POST['id_categoria']);

    if (!$id_categoria) {
        die("Categoria inválida.");
    }

    $sql = $pdo->prepare("
        INSERT INTO subcategorias (nome, slug, icone, id_categoria)
        VALUES (?, ?, ?, ?)
    ");
    $sql->execute([$nome, $slug, $icone, $id_categoria]);

    header("Location: subcategorias.php?add=ok");
    exit;
}

// =================== EDITAR ===================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'editar') {

    $id = intval($_POST['id']);
    $nome = trim($_POST['nome']);
    $slug = trim($_POST['slug']) ?: strtolower(preg_replace('/[^a-z0-9]+/', '-', $nome));
    $icone = trim($_POST['icone']); // BASE64
    $id_categoria = intval($_POST['id_categoria']);

    $up = $pdo->prepare("
        UPDATE subcategorias
        SET nome = ?, slug = ?, icone = ?, id_categoria = ?
        WHERE id = ?
    ");
    $up->execute([$nome, $slug, $icone, $id_categoria, $id]);

    header("Location: subcategorias.php?edit=ok");
    exit;
}

// =================== LISTAR ===================
$subcats = $pdo->query("
    SELECT subcategorias.*, categorias.nome AS categoria_nome
    FROM subcategorias
    LEFT JOIN categorias ON categorias.id = subcategorias.id_categoria
    ORDER BY categorias.nome, subcategorias.nome
")->fetchAll(PDO::FETCH_ASSOC);

// categorias para select
$cats = $pdo->query("SELECT id, nome FROM categorias ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
<h2>Subcategorias</h2>

<!-- CRIAR -->
<h3>Criar Subcategoria</h3>

<form method="post">
    <input type="hidden" name="acao" value="criar">

    <input name="nome" placeholder="Nome da subcategoria" required>
    <input name="slug" placeholder="Slug (opcional)">

    <!-- DROPZONE ícone -->
    <label>Ícone da Subcategoria:</label>
    <div id="dropIconCreate" 
         style="border:2px dashed #888; padding:20px; text-align:center; cursor:pointer;">
         Arraste uma imagem ou clique
    </div>
    <input type="file" id="inputIconCreate" accept="image/*" style="display:none;">
    <input type="hidden" name="icone" id="iconeBase64Create">

    <select name="id_categoria" required>
        <option value="">Categoria...</option>
        <?php foreach ($cats as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
        <?php endforeach; ?>
    </select>

    <button class="btn btn-primary">Criar</button>
</form>

<br><hr><br>

<!-- LISTAR -->
<table>
<thead>
<tr>
    <th>ID</th>
    <th>Nome</th>
    <th>Slug</th>
    <th>Ícone</th>
    <th>Categoria</th>
    <th>Ações</th>
</tr>
</thead>
<tbody>

<?php foreach ($subcats as $s): ?>
<tr>
<td><?= $s['id'] ?></td>
<td><?= htmlspecialchars($s['nome']) ?></td>
<td><?= htmlspecialchars($s['slug']) ?></td>

<td>
<?php if ($s['icone']): ?>
<img src="<?= $s['icone'] ?>" width="40" height="40">
<?php endif; ?>
</td>

<td><?= htmlspecialchars($s['categoria_nome']) ?></td>

<td>
<button class="btn btn-warning" type="button"
    onclick='abrirEdicao(<?= json_encode($s, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>)'>
    Editar
</button>

<form method="post" action="actions/subcategoria_delete.php"
      style="display:inline-block"
      onsubmit="return confirm("Excluir subcategoria?");">
    <input type="hidden" name="id" value="<?= $s['id'] ?>">
    <button class="btn btn-danger">Excluir</button>
</form>
</td>
</tr>
<?php endforeach; ?>

</tbody>
</table>

</section>

<!-- MODAL DE EDIÇÃO -->
<div id="modalEditar" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
background:rgba(0,0,0,0.6); justify-content:center; align-items:center;">

    <div style="background:#fff; padding:20px; width:400px; border-radius:8px;">
        <h3>Editar Subcategoria</h3>

        <form method="post">
            <input type="hidden" name="acao" value="editar">
            <input type="hidden" id="edit_id" name="id">

            <label>Nome:</label>
            <input id="edit_nome" name="nome" required>

            <label>Slug:</label>
            <input id="edit_slug" name="slug">

            <!-- DROPZONE EDITAR -->
            <label>Ícone:</label>
            <div id="dropIconEdit" 
                style="border:2px dashed #888; padding:20px; text-align:center; cursor:pointer;">
                Arraste uma imagem ou clique
            </div>
            <input type="file" id="inputIconEdit" accept="image/*" style="display:none;">
            <input type="hidden" name="icone" id="iconeBase64Edit">

            <label>Categoria:</label>
            <select id="edit_categoria" name="id_categoria">
                <?php foreach ($cats as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
                <?php endforeach; ?>
            </select>

            <br><br>
            <button class="btn btn-primary">Salvar</button>
            <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
        </form>
    </div>
</div>

<script>
// FUNÇÃO DE COMPRESSÃO E CONVERSÃO EM BASE64
function processarImagem(file, dropbox, hiddenInput) {
    if (!file || !file.type.startsWith("image/")) {
        alert("Selecione uma imagem válida.");
        return;
    }

    const reader = new FileReader();
    reader.onload = function(event) {
        const img = new Image();
        img.onload = function() {

            const canvas = document.createElement("canvas");
            const MAX_SIZE = 500;
            let w = img.width;
            let h = img.height;

            if (w > h) {
                if (w > MAX_SIZE) { h *= MAX_SIZE / w; w = MAX_SIZE; }
            } else {
                if (h > MAX_SIZE) { w *= MAX_SIZE / h; h = MAX_SIZE; }
            }

            canvas.width = w;
            canvas.height = h;

            const ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0, w, h);

            const base64 = canvas.toDataURL("image/png");

            hiddenInput.value = base64;

            dropbox.innerHTML = `<img src="${base64}" style="width:200px;height:200px;">`;
        };
        img.src = event.target.result;
    };

    reader.readAsDataURL(file);
}

// ==========================
// CRIAR (DROPZONE)
// ==========================
const dropCreate = document.getElementById("dropIconCreate");
const inputCreate = document.getElementById("inputIconCreate");
const baseCreate = document.getElementById("iconeBase64Create");

dropCreate.addEventListener("click", () => inputCreate.click());
dropCreate.addEventListener("dragover", e => e.preventDefault());
dropCreate.addEventListener("drop", e => {
    e.preventDefault();
    processarImagem(e.dataTransfer.files[0], dropCreate, baseCreate);
});
inputCreate.addEventListener("change", e => {
    processarImagem(e.target.files[0], dropCreate, baseCreate);
});

// ==========================
// EDITAR (DROPZONE)
// ==========================
const dropEdit = document.getElementById("dropIconEdit");
const inputEdit = document.getElementById("inputIconEdit");
const baseEdit = document.getElementById("iconeBase64Edit");

dropEdit.addEventListener("click", () => inputEdit.click());
dropEdit.addEventListener("dragover", e => e.preventDefault());
dropEdit.addEventListener("drop", e => {
    e.preventDefault();
    processarImagem(e.dataTransfer.files[0], dropEdit, baseEdit);
});
inputEdit.addEventListener("change", e => {
    processarImagem(e.target.files[0], dropEdit, baseEdit);
});

// ==========================
// MODAL
// ==========================
function abrirEdicao(dados) {
    document.getElementById("modalEditar").style.display = "flex";

    document.getElementById("edit_id").value = dados.id;
    document.getElementById("edit_nome").value = dados.nome;
    document.getElementById("edit_slug").value = dados.slug;
    document.getElementById("edit_categoria").value = dados.id_categoria;

    if (dados.icone) {
        dropEdit.innerHTML = `<img src="${dados.icone}" style="width:80px;height:80px;">`;
        baseEdit.value = dados.icone;
    } else {
        dropEdit.innerHTML = "Arraste uma imagem ou clique";
        baseEdit.value = "";
    }
}

function fecharModal() {
    document.getElementById("modalEditar").style.display = "none";
}
</script>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
