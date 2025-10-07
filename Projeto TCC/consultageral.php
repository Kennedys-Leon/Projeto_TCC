<?php
session_start();
if (!isset($_SESSION['usuario_nome']) || $_SESSION['usuario_nome'] !== 'adm') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - MaxAcess</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .painel-admin {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .tabela-container {
            margin-bottom: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #8C5B3F;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        tr:hover {
            background-color: #eee;
        }

        .section-title {
            font-size: 20px;
            margin: 30px 0 10px;
            text-align: left;
            color: #333;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="../img/logo.png" alt="MaxAcess" class="logo-img">
        </div>
        <nav class="navbar">
            <div class="search-cart">
                <span class="user-box">👤 <?php echo $_SESSION['usuario_nome']; ?></span>
                <input class="search-bar" type="text" placeholder="Buscar...">
                <a href="#" class="cart-icon">🛒</a>
                <a href="../logout.php" class="button-login">Sair</a>
                <input type="text" id="toggle-theme" class="toggle-theme" value="🌙 Tema Escuro" readonly>
            </div>
            <ul>
                <li><a href="../index.php">Início</a></li>
                <li><a href="#">Gerenciar</a></li>
                <li><a href="#">Relatórios</a></li>
                <li><a href="../index.php">Retornar ao site</a></li>
            </ul>
        </nav>
    </header>

    <main class="painel-admin">
        <h2>Painel Administrativo</h2>

        <?php
        // ==== USUÁRIOS ====
        include '../conexao.php';
        $stmt = $pdo->query("SELECT * FROM usuario");
        echo '<div class="tabela-container">';
        echo '<h3 class="section-title">Usuários</h3>';
        echo '<table>';
        echo "<tr>
                <th>Código</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>CEP</th>
                <th>Telefone</th>
                <th>Email</th>
              </tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>
                    <td>".$row['idusuario']."</td>
                    <td>".$row['nome']."</td>
                    <td>".$row['cpf']."</td>
                    <td>".$row['cep']."</td>
                    <td>".$row['telefone']."</td>
                    <td>".$row['email']."</td>
                  </tr>";
        }
        echo '</table>';
        echo '</div>';

        // ==== VENDEDORES ====
        include '../conexao.php';
        $stmt = $pdo->query("SELECT * FROM vendedor");
        echo '<div class="tabela-container">';
        echo '<h3 class="section-title">Vendedores</h3>';
        echo '<table>';
        echo "<tr>
                <th>Código</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>CNPJ</th>
              </tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>
                    <td>".$row['idvendedor']."</td>
                    <td>".$row['nome']."</td>
                    <td>".$row['cpf']."</td>
                    <td>".$row['telefone']."</td>
                    <td>".$row['email']."</td>
                    <td>".$row['cnpj']."</td>
                  </tr>";
        }
        echo '</table>';
        echo '</div>';

        // ==== PRODUTOS ====
        $stmt = $pdo->query("SELECT * FROM produto");
        echo '<div class="tabela-container">';
        echo '<h3 class="section-title">Produtos</h3>';
        echo '<table>';
        echo "<tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Categoria</th>
                <th>Quantidade</th>
              </tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>
                    <td>".$row['idproduto']."</td>
                    <td>".$row['nome']."</td>
                    <td>".$row['preco']."</td>
                    <td>".$row['categoria']."</td>
                    <td>".$row['quantidade_estoque']."</td>
                  </tr>";
        }
        echo '</table>';
        echo '</div>';
        ?>
    </main>

    <footer class="rodape">
        Todos os Direitos Reservados - 2025
    </footer>

    <script src="../script.js"></script>
    <div vw-plugin-wrapper>
        <div class="vw-plugin-top-wrapper"></div>
    </div>
</div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
      new window.VLibras.Widget('https://vlibras.gov.br/app');
</body>
</html>
