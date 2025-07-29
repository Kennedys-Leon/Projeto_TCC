<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Login</h1>
    <form action="process_login.php" method="post">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required><br><br>

        <label for="senha">Senha:</label>
        <input type="text" id="senha" name="senha" required><br><br>

        <input type="submit" value="Login">
    </form>
    <?php
        if(isset($GET['error'])){ ?>
            <p style="color:red;">Usúario ou senha Incorreta.</p>
        <?php }    ?>

</body>
</html>