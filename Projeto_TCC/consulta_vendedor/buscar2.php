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
                <li><a href="../index.php">Início</a></li>
                <li><a href="#">Sobre</a></li>
                <li><a href="#">Serviços</a></li>
                <li><a href="#">Anunciar</a></li>
            </ul>
            <div class="search-cart">
                <input class="search-bar" type="text" placeholder="Buscar...">
                <a href="#" class="cart-icon">🛒</a>
                <a href="../login/login.php" class="button-login-login">Login</a>
                <a href="../php/cadastro.php" class="button-login">Criar Conta</a>
                <input type="text" id="toggle-theme" class="toggle-theme" value="🌙 Tema Escuro" readonly>
            </div>
        </nav>
    </header>

    <main class="conteudo">
    <form action="filtro2.php" method="post" class="form-cadastro">
        <h2>Filtro</h2>

        <label for="nome">Digite o nome:</label>
        <input type="text" name="nome" required>
    </main>

    <footer class="rodape">
        Todos os Direitos Reservados - 2025
    </footer>

    <script src="../script.js"></script>
</body>
</html>