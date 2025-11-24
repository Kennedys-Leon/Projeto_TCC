<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pagamento via Cartão - MaxAcess</title>
    <link rel="stylesheet" href="../css/cartao.css">
    <style>
        /* Duas colunas */
.duas-colunas {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
}

.coluna {
  flex: 1;
}

@media (max-width: 600px) {
  .duas-colunas {
    flex-direction: column;
  }
}

</style>
</head>
<body>
    <div class="container-pagamento">
        <h2>Pagamento com Cartão</h2>
        <p>Preencha os dados do seu cartão abaixo:</p>
        <form action="#" method="POST" class="form-cartao">
  <div class="form-grid">
    <div class="form-group">
      <label for="numero">Número do Cartão</label>
      <input type="text" id="numero" maxlength="16" placeholder="0000 0000 0000 0000" required>
    </div>

    <div class="form-group">
      <label for="nome">Nome Impresso no Cartão</label>
      <input type="text" id="nome" placeholder="Nome Completo" required>
    </div>

    <div class="form-group">
      <label for="validade">Validade</label>
      <input type="text" id="validade" placeholder="MM/AA" required>
    </div>

    <div class="form-group">
      <label for="cvv">Código de Segurança</label>
      <input type="text" id="cvv" placeholder="123" required>
    </div>
  </div>

        </form>
            <button class="btn" type="submit" onclick="alert('Simulado Concretizado !')">Pagar Agora</button>
        </form>

        <a href="pagamento.php" class="voltar">Voltar</a>
    </div>
</body>
<div vw-plugin-wrapper>
        <div class="vw-plugin-top-wrapper"></div>
    </div>
</div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
      new window.VLibras.Widget('https://vlibras.gov.br/app');
</html>
