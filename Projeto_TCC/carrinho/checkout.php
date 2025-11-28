<?php
session_start();

// 🔒 Verifica se o usuário está logado
if (!isset($_SESSION['usuario_nome'])) {
    echo "
    <html lang='pt-BR'>
    <head>
        <meta charset='UTF-8'>
        <title>Redirecionando...</title>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background: #f7f7f7;
                color: #333;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                text-align: center;
                flex-direction: column;
            }
            .caixa {
                background: white;
                padding: 40px;
                border-radius: 15px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            h2 {
                color: #d9534f;
            }
            p {
                margin-top: 10px;
            }
        </style>
    </head>
    <body>
        <div class='caixa'>
            <h2>⚠️ Você precisa estar logado para finalizar sua compra!</h2>
            <p>Você será redirecionado para a página de cadastro em alguns segundos...</p>
        </div>
        <script>
            setTimeout(() => {
                window.location.href = '../cadastro_usuario/cadastro.php';
            }, 3000);
        </script>
    </body>
    </html>
    ";
    exit;
}


// 🛒 Captura o carrinho enviado via POST (enviado pelo JS do index)
$carrinho = [];
if (isset($_POST['cart'])) {
    $json = $_POST['cart'];
    $decoded = json_decode($json, true);

    if (is_array($decoded)) {
        foreach ($decoded as $item) {
            if (!isset($item['nome'], $item['preco'])) continue;
            $carrinho[] = [
                'id' => isset($item['id']) ? $item['id'] : null,
                'nome' => strip_tags($item['nome']),
                'preco' => (float)$item['preco'],
                'quantidade' => isset($item['quantidade']) ? (int)$item['quantidade'] : 1
            ];
        }
    }
}

// 🧭 Caso o carrinho não venha pelo POST (fallback)
if (empty($carrinho) && isset($_SESSION['carrinho'])) {
    $carrinho = $_SESSION['carrinho'];
}

// 💾 Atualiza a sessão para manter sincronizado
$_SESSION['carrinho'] = $carrinho;

// 💰 Calcula o total
$total = 0;
foreach ($carrinho as $item) {
    $total += $item['preco'] * $item['quantidade'];
}
$_SESSION['total'] = $total;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Checkout - MaxAcess</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/checkout.css">
</head>
<body>
<?php
// incluir conexão ao banco para buscar imagens dos produtos (opcional)
include_once __DIR__ . '/../conexao.php';

// preparar statement para buscar imagem por id (preferível) e por nome como fallback
$stmtImgById = null;
$stmtImgByName = null;
if (isset($pdo)) {
    $stmtImgById = $pdo->prepare("SELECT imagem FROM imagens WHERE idproduto = ? LIMIT 1");
    $stmtImgByName = $pdo->prepare("SELECT i.imagem FROM produto p LEFT JOIN imagens i ON p.idproduto = i.idproduto WHERE p.nome = ? LIMIT 1");
}
?>
    <div class="checkout-container">
        <div class="checkout-header">
            <h2>Seu Carrinho</h2>
        </div>

        <?php if (empty($carrinho)): ?>
            <p>Seu carrinho está vazio.</p>
            <a href="../index.php" class="btn-voltar">Voltar à Loja</a>

        <?php else: ?>
            <table class="checkout-tabela">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th>Qtd</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrinho as $item): 
                        // tenta buscar imagem no banco usando id (se disponível) ou nome como fallback
                        $imgSrc = null;
                        if (isset($pdo)) {
                            try {
                                if (!empty($item['id']) && $stmtImgById) {
                                    $stmtImgById->execute([ $item['id'] ]);
                                    $rowImg = $stmtImgById->fetch(PDO::FETCH_ASSOC);
                                    if ($rowImg && !empty($rowImg['imagem'])) {
                                        $imgData = $rowImg['imagem'];
                                        if (is_resource($imgData)) {
                                            $imgData = stream_get_contents($imgData);
                                        }
                                        if ($imgData !== null && $imgData !== '') {
                                            $finfo = false;
                                            if (function_exists('finfo_buffer')) {
                                                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                                $mime = finfo_buffer($finfo, $imgData) ?: 'image/jpeg';
                                                finfo_close($finfo);
                                            } else {
                                                $mime = 'image/jpeg';
                                            }
                                            $imgSrc = 'data:' . $mime . ';base64,' . base64_encode($imgData);
                                        }
                                    }
                                }

                                if (empty($imgSrc) && $stmtImgByName) {
                                    $stmtImgByName->execute([ $item['nome'] ]);
                                    $rowImg = $stmtImgByName->fetch(PDO::FETCH_ASSOC);
                                    if ($rowImg && !empty($rowImg['imagem'])) {
                                        $imgData = $rowImg['imagem'];
                                        if (is_resource($imgData)) {
                                            $imgData = stream_get_contents($imgData);
                                        }
                                        if ($imgData !== null && $imgData !== '') {
                                            $finfo = false;
                                            if (function_exists('finfo_buffer')) {
                                                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                                $mime = finfo_buffer($finfo, $imgData) ?: 'image/jpeg';
                                                finfo_close($finfo);
                                            } else {
                                                $mime = 'image/jpeg';
                                            }
                                            $imgSrc = 'data:' . $mime . ';base64,' . base64_encode($imgData);
                                        }
                                    }
                                }
                            } catch (Exception $e) {
                                // falha na busca da imagem — ignorar e usar placeholder
                                $imgSrc = null;
                            }
                        }

                        if (empty($imgSrc)) {
                            $imgSrc = '../img/usuario.png';
                        }
                    ?>
                        <tr>
                            <td>
                                <div class="produto-cell">
                                    <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($item['nome']) ?>" class="produto-thumb" />
                                    <span class="produto-nome"><?= htmlspecialchars($item['nome']) ?></span>
                                </div>
                            </td>
                            <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                            <td><?= intval($item['quantidade']) ?></td>
                            <td>R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="checkout-total">
                <h3>Total: <span>R$ <?= number_format($total, 2, ',', '.') ?></span></h3>
            </div>

            <div class="checkout-acoes">
                <a href="../index.php" class="btn-voltar">← Continuar Comprando</a>
                <a href="pagamento.php" class="btn-finalizar">Finalizar Pagamento</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Plugin VLibras -->
    <div vw-plugin-wrapper>
        <div class="vw-plugin-top-wrapper"></div>
    </div>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btnFinalizar = document.querySelector('.btn-finalizar');

            if (btnFinalizar) {
                btnFinalizar.addEventListener('click', async (e) => {
                    e.preventDefault();

                    try {
                        const response = await fetch('verifica_dados_usuario.php', { method: 'POST' });
                        const data = await response.json();

                        if (data.success) {
                            // Tudo certo -> ir para pagamento
                            window.location.href = 'pagamento.php';
                        } else {
                            // Faltam informações -> alerta
                            Swal.fire({
                                icon: 'warning',
                                title: 'Informações incompletas',
                                text: data.msg || 'Por favor, complete seus dados antes de continuar.',
                                confirmButtonText: 'Ir para o perfil',
                                confirmButtonColor: '#163b72'
                            }).then(() => {
                                window.location.href = '../editar_perfil/perfil.php';
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: 'Ocorreu um problema ao verificar seus dados. Tente novamente.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    </script>

</body>
</html>
