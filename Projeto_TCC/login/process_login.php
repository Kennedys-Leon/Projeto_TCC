<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
        $sql = "SELECT idusuario, nome, senha, foto_de_perfil, ativo, email
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
                header("Location: login.php?error=2&email=" . urlencode($email));
                exit;
            }

            // Verifica senha
            if (password_verify($senha, $usuario['senha'])) {

                // Cria a sessão
                $_SESSION['idusuario'] = (int)$usuario['idusuario'];
                $_SESSION['usuario_nome'] = htmlspecialchars($usuario['nome'], ENT_QUOTES);
                $_SESSION['usuario_foto'] = $usuario['foto_de_perfil'];

                // ===============================
                // 🚨 REDIRECIONAMENTO DO ADMIN
                // ===============================

                $nome = strtolower($usuario['nome']);
                $emailUser = strtolower($usuario['email']);

                // Se quiser usar apenas nome:
                if ($nome === 'adm') {
                    header("Location: ../adm/index.php");
                    exit;
                }

                // Se quiser também permitir adm por email (opcional)
                if ($emailUser === 'kennedyleon203@gmail.com') { 
                    header("Location: ../adm/index.php");
                    exit;
                }

                // Redireciona usuário comum
                header("Location: ../index.php");
                exit;
            }
        }

        // Se chegou aqui → falhou
        header("Location: login.php?error=1");
        exit;

    } catch (PDOException $e) {
        error_log("process_login erro: " . $e->getMessage());
        header("Location: login.php?error=1");
        exit;
    }
}
?>
