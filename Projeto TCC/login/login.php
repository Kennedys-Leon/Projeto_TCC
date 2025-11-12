<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/cadastro.css">
</head>
<body>
    <form action="process_login.php" method="post" class="form-cadastro">
        <h2>Olá usuário! Insira seu Login👇</h2>

        <label for="nome">Nome:</label>
        <input type="text" name="nome" required>

        <label for="email">Email:</label>
        <input type="text" name="email" required>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" required>

        <input type="submit" value="Entrar" class="btn-vermelho">

        <div class="botoes-inicio">
            <a href="../cadastro_usuario/cadastro.php" class="btn-primario">Não tem conta? Cadastre-se</a>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const params = new URLSearchParams(window.location.search);
            const email = params.get('email') || '';

            // Verifica se veio ?error=2 (conta desativada)
            if (params.has('error') && params.get('error') === '2' && email) {
                Swal.fire({
                    title: 'Conta desativada',
                    text: 'Sua conta está desativada. Deseja reativá-la agora?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, reativar',
                    cancelButtonText: 'Não, sair'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('reativar_conta.php', {
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
                                    // Redireciona de volta para login limpando os parâmetros
                                    window.location.href = 'login.php';
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
