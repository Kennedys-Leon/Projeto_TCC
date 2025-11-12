<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vendedor</title>
    <link rel="stylesheet" href="../css/cadastro.css">
</head>
<body>
    <form action="process_login_vendedor.php" method="post" class="form-cadastro">
        <h2>Olá vendedor! Faça seu login 👇</h2>

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" placeholder="Nome do Vendedor" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Seu Email de Vendas" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" placeholder="Sua Senha" required><br><br>
        
        <input type="submit" value="Entrar" class="btn-vermelho">

        <div class="botoes-inicio">
            <a href="../cadastro_vendedor/cadastrovendedor.php" class="btn-primario">Não tem conta? Cadastre-se agora!</a>
        </div>

        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <p style="color: red; margin-top: 10px;">Email ou senha incorretos.</p>
        <?php endif; ?>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const params = new URLSearchParams(window.location.search);

            if (params.has('reativar') && params.get('reativar') === '1') {
                const email = params.get('email') || '';

                Swal.fire({
                    title: 'Conta desativada',
                    text: 'Sua conta está desativada. Deseja reativá-la agora?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, reativar',
                    cancelButtonText: 'Não, sair'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('reativar_conta_vendedor.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'email=' + encodeURIComponent(email)
                        })
                        .then(res => res.json())
                        .then(json => {
                            if (json.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Conta reativada!',
                                    text: json.msg || 'Você pode fazer login novamente.',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = 'login_vendedor.php';
                                });
                            } else {
                                Swal.fire('Erro', json.msg, 'error');
                            }
                        })
                        .catch(() => Swal.fire('Erro', 'Erro ao processar a requisição.', 'error'));
                    } else {
                        window.location.href = '../index.php';
                    }
                });
            }
        });
    </script>

</body>
</html>
