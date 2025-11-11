<?php
session_start();
include('../conexao.php'); // conexão PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        header("Location: login.php?error=1");
        exit;
    }

    try {
        $sql = "SELECT idusuario, nome, senha, foto_de_perfil, ativo
                FROM usuario
                WHERE email = ?
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Usuário encontrado?
        if ($usuario) {

            // Caso a conta esteja DESATIVADA
            if ((int)$usuario['ativo'] === 0) {
                // Redireciona com mensagem específica e opção de reativar
                header("Location: login.php?error=2&email=" . urlencode($email));
                exit;
            }

            // Verifica senha normalmente
            if (password_verify($senha, $usuario['senha'])) {
                $_SESSION['idusuario'] = (int)$usuario['idusuario'];
                $_SESSION['usuario_nome'] = htmlspecialchars($usuario['nome'], ENT_QUOTES);
                $_SESSION['usuario_foto'] = $usuario['foto_de_perfil'];

                header("Location: ../index.php");
                exit;
            }
        }

        // Se chegou aqui → falha
        header("Location: login.php?error=1");
        exit;
    } catch (PDOException $e) {
        error_log("process_login erro: " . $e->getMessage());
        header("Location: login.php?error=1");
        exit;
    }
}
?>
