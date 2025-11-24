<?php
$plano = $_GET['plano'] ?? 'não definido';

switch($plano) {
    case 'prata':
        $nome = 'Prata';
        $taxa = '9,99%';
        break;
    case 'ouro':
        $nome = 'Ouro';
        $taxa = '11,99%';
        break;
    case 'diamante':
        $nome = 'Diamante';
        $taxa = '12,99%';
        break;
    default:
        $nome = 'Plano inválido';
        $taxa = '-';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Comprar Plano - MaxAcess</title>
    <link rel="stylesheet" href="../css/comprar_plano.css">
</head>
<body>
    <div class="container">
        <h1>Plano Selecionado: <span class="plano-nome"><?php echo $nome; ?></span></h1>
        <p class="taxa">Taxa: <strong><?php echo $taxa; ?></strong></p>

        <?php if ($nome !== 'Plano inválido'): ?>
        <form action="finalizar-compra.php" method="POST" class="formulario">
            <input type="hidden" name="plano" value="<?php echo $nome; ?>">
            <input type="hidden" name="taxa" value="<?php echo $taxa; ?>">

            <label for="nome">Nome completo:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" required>


            <button type="submit" class="btn-comprar">Confirmar Compra</button>
        </form>
        <?php else: ?>
            <p class="erro">Plano selecionado é inválido. <a href="servicos.html">Voltar</a></p>
        <?php endif; ?>

        <a href="servicos.php" class="btn-voltar">Voltar</a>
    </div>
    <body class="<?php echo 'plano-' . strtolower($nome); ?>">

</body>
</html>
