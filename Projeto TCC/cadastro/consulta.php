<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site nosso</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="../img/logo.png" alt="MaxAcess" class="logo-img">
        </div>        
        <nav class="navbar">
            <ul>
                <li><a href="#">Início</a></li>
                <li><a href="#">Sobre</a></li>
                <li><a href="#">Serviços</a></li>
                <li><a href="#">Anunciar</a></li>
                <li><a href="../index.php">Retornar</a></li>
            </ul>
            <div class="search-cart">
                <input class="search-bar" type="text" placeholder="Buscar...">
                <a href="#" class="cart-icon">🛒</a>
                <a href="../login/login.php" class="button-login-login">Login</a>
                <a href="cadastro.php" class="button-login">Criar Conta</a>
                <input type="text" id="toggle-theme" class="toggle-theme" value="🌙 Tema Escuro" readonly>
            </div>
        </nav>
    </header>

    <main class="conteudo">
        <h2>Consulta</h2>
        <?php
        include 'conexao.php';
        $stmt = $conn->query("SELECT * FROM cadastro");
        echo '
        <style>
            table {
                width: 80%;
                margin: 20px auto;
                border-collapse: collapse;
            }

            th, td {
                border: 1px solid #555;
                padding: 10px;
                text-align: left;
            }

            th {
                background-color: #8C5B3F;
                color: white;
            }

            tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            tr:hover {
                background-color: #ddd;
            }
        </style>
    ';
        echo '<table border="1">';
            echo "<tr>";
                echo "<th>Código</th>";
                echo "<th>Nome</th>";
                 echo "<th>CPF</th>";
                echo "<th>CEP</th>";
                 echo "<th>Telefone</th>";
                echo "<th>Email</th>";
                echo "<th>Senha</th>";
            echo "</tr>"; 
        while ($row = $stmt->fetch()) {
            echo "<tr>";
                echo "<td>".$row['idcadastro']."</td>";
                echo "<td>".$row['nome']."</td>";
                 echo "<td>".$row['cpf']."</td>";
                echo "<td>".$row['cep']."</td>";
                 echo "<td>".$row['telefone']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['senha']."</td>";
            echo "</tr>";
            }
        echo '</table>';
    ?>
    </main>

    <footer class="rodape">
        Todos os Direitos Reservados - 2025
    </footer>

    <script src="../script.js"></script>
</body>
</html>
