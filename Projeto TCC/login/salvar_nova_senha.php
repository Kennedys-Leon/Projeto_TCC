<?php
require '../conexao.php';

$tipo = $_POST['tipo'] ?? '';
$id   = $_POST['id'] ?? '';
$senha1 = $_POST['senha1'] ?? '';
$senha2 = $_POST['senha2'] ?? '';

// Validar
if (empty($tipo) || empty($id) || empty($senha1) || empty($senha2)) {
    die("Dados incompletos.");
}

if ($senha1 !== $senha2) {
    die("As senhas não conferem.");
}

$hash = password_hash($senha1, PASSWORD_DEFAULT);

// Tabela e ID corretos
if ($tipo === "usuario") {
    $tabela = "usuario";
    $campo_id = "idusuario";
    $redirect = "login.php";
} elseif ($tipo === "vendedor") {
    $tabela = "vendedor";
    $campo_id = "idvendedor";
    $redirect = "../login_vendedor/login_vendedor.php";
} else {
    die("Tipo inválido.");
}

// Atualiza a senha e limpa token
$sql = $pdo->prepare("
    UPDATE $tabela 
    SET senha = ?, reset_token = NULL, token_expira = NULL
    WHERE $campo_id = ?
");

if ($sql->execute([$hash, $id])) {
    echo "
<div class='overlaySucesso'>
    <div class='modalSucesso'>
        <div class='iconSucesso'>✔</div>

        <h2 class='tituloSucesso'>Senha alterada com sucesso!</h2>
        <p class='textoSucesso'>Você já pode acessar sua conta normalmente.</p>

        <a href='$redirect' class='btnSucesso'>Fazer login</a>
    </div>
</div>

<style>
.overlaySucesso {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.65);
    display: flex;
    justify-content: center;
    align-items: center;
    animation: fadeIn .3s ease;
    z-index: 9999;
}

.modalSucesso {
    background: #ffffff;
    padding: 30px 40px;
    width: 420px;
    border-radius: 18px;
    text-align: center;
    box-shadow: 0 10px 35px rgba(0,0,0,0.25);
    animation: scaleIn .35s ease;
}

.iconSucesso {
    font-size: 48px;
    color: #2ecc71;
    margin-bottom: 12px;
}

.tituloSucesso {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 5px;
}

.textoSucesso {
    font-size: 15px;
    color: #444;
    margin-bottom: 22px;
}

.btnSucesso {
    display: inline-block;
    padding: 10px 22px;
    background: #2ecc71;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: .2s;
}

.btnSucesso:hover {
    background: #27ae60;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes scaleIn {
    from { transform: scale(0.75); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>
";
    // Redireciona após 2 segundos
    header("refresh:2; url=$redirect");
    exit;
} else {
    echo "Erro ao atualizar senha.";
}
