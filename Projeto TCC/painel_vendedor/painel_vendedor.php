<?php
session_start();
include '../cadastro/conexao.php';

// ============================
// Verifica se vendedor está logado
// ============================
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../login_vendedor/login_vendedor.php");
    exit;
}

$vendedor_id = $_SESSION['usuario_logado'];

// ============================
// Buscar informações do vendedor
// ============================
$stmt = $pdo->prepare("SELECT * FROM vendedor WHERE idvendedor = ?");
$stmt->execute([$vendedor_id]);
$vendedor = $stmt->fetch(PDO::FETCH_ASSOC);

// ============================
// Total de vendas do vendedor
// ============================
$stmt = $pdo->prepare("SELECT COUNT(*) as total_vendas FROM vendas WHERE idvendedor = ?");
$stmt->execute([$vendedor_id]);
$total_vendas = $stmt->fetch(PDO::FETCH_ASSOC)['total_vendas'] ?? 0;

$stmt = $pdo->query("SELECT COUNT(*) as todas_vendas FROM vendas");
$todas_vendas = $stmt->fetch(PDO::FETCH_ASSOC)['todas_vendas'] ?? 1;

if ($todas_vendas == 0) {
    $percentual = 0;
} else {
    $percentual = ($total_vendas / $todas_vendas) * 100;
}

// ============================
// Produtos cadastrados pelo vendedor
// ============================
$stmt = $pdo->prepare("SELECT * FROM produto WHERE idvendedor = ?");
$stmt->execute([$vendedor_id]);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Vendedor - MaxAcess</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        /* Layout geral */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f8f8f8;
        }
        header, footer {
            background: #3c2c20;
            color: #fff;
            padding: 15px;
            text-align: center;
        }
        footer { margin-top: 20px; }
        .container {
            width: 90%;
            max-width: 1100px;
            margin: 20px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,.2);
            padding: 20px;
        }
        h2 { color: #3c2c20; }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 2px solid #ccc;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
            background: #e2d3c4;
            margin-right: 5px;
        }
        .tab.active {
            background: #3c2c20;
            color: #fff;
        }
        .tab-content {
            display: none;
            padding: 20px 0;
        }
        .tab-content.active { display: block; }

        /* Tabelas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        table th {
            background: #3c2c20;
            color: #fff;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background: #3c2c20;
            color: #fff;
        }
        .btn:hover { background: #5a4030; }

        .percentual {
            font-size: 22px;
            font-weight: bold;
            color: #3c2c20;
        }
    </style>
    <script>
        // Função JS para trocar abas
        function openTab(tabName) {
            let tabs = document.querySelectorAll('.tab-content');
            let buttons = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            buttons.forEach(btn => btn.classList.remove('active'));
            document.getElementById(tabName).classList.add('active');
            document.querySelector('[data-tab="'+tabName+'"]').classList.add('active');
        }
    </script>
</head>
<body>
    <header>
        <h1>MaxAcess - Painel do Vendedor</h1>
        <p>Bem-vindo, <?php echo htmlspecialchars($vendedor['nome']); ?>!</p>
    </header>

    <div class="container">
        <!-- Abas -->
        <div class="tabs">
            <div class="tab active" data-tab="vendas" onclick="openTab('vendas')">Resumo de Vendas</div>
            <div class="tab" data-tab="produtos" onclick="openTab('produtos')">Meus Produtos</div>
            <div class="tab" data-tab="perfil" onclick="openTab('perfil')">Meu Perfil</div>
        </div>

        <!-- Resumo de Vendas -->
        <div id="vendas" class="tab-content active">
            <h2>Resumo de Vendas</h2>
            <p>Total de vendas realizadas: <b><?php echo $total_vendas; ?></b></p>
            <p>Total do site: <b><?php echo $todas_vendas; ?></b></p>
            <p class="percentual">Participação: <?php echo number_format($percentual, 2); ?>%</p>
        </div>

        <!-- Produtos -->
        <div id="produtos" class="tab-content">
            <h2>Meus Produtos</h2>
            <table>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Categoria</th>
                    <th>Qtd</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($produtos as $p): ?>
                <tr>
                    <td><?php echo $p['idproduto']; ?></td>
                    <td><?php echo htmlspecialchars($p['nome']); ?></td>
                    <td>R$ <?php echo number_format($p['preco'],2,",","."); ?></td>
                    <td><?php echo htmlspecialchars($p['categoria']); ?></td>
                    <td><?php echo $p['quantidade_estoque']; ?></td>
                    <td>
                        <button class="btn">Editar</button>
                        <button class="btn">Excluir</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <!-- Perfil -->
        <div id="perfil" class="tab-content">
            <h2>Meu Perfil</h2>
            <form method="post" action="editar_perfil.php">
                <label>Nome:</label><br>
                <input type="text" name="nome" value="<?php echo htmlspecialchars($vendedor['nome']); ?>"><br><br>
                <label>Email:</label><br>
                <input type="email" name="email" value="<?php echo htmlspecialchars($vendedor['email']); ?>"><br><br>
                <label>Telefone:</label><br>
                <input type="text" name="telefone" value="<?php echo htmlspecialchars($vendedor['telefone']); ?>"><br><br>
                <label>CNPJ:</label><br>
                <input type="text" name="cnpj" value="<?php echo htmlspecialchars($vendedor['cnpj']); ?>"><br><br>
                <button type="submit" class="btn">Salvar Alterações</button>
            </form>
        </div>
    </div>

    <footer>
        <p>Todos os Direitos Reservados - MaxAcess © 2025</p>
    </footer>
</body>
</html>
