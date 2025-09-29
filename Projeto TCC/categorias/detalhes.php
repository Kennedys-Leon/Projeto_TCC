<?php
// categorias/detalhes.php

$produtos = [
  1 => [
    "nome" => "Conta De Brawl Stars De 63 mil trofeus",
    "preco" => "R$ 150,97",
    "descricao" => "Pacote exclusivo com benefícios especiais.",
    "imagens" => ["../jogos/brawl.png", "../jogos/bralwer.png", "../jogos/brawls.png"]
  ],
  2 => [
    "nome" => "Conta De Brawl Stars de 34 mil trofeus",
    "preco" => "R$ 50,00",
    "descricao" => "Pacote exclusivo com benefícios especiais.",
    "imagens" => ["../jogos/conta_rian.png", "../jogos/braw.png", "../jogos/bra.png", "../jogos/stu.png","../jogos/loud.png"]
  ],
  // Adicione mais produtos conforme necessário
];

$id = $_GET['id'] ?? null;

if ($id && isset($produtos[$id])) {
  $produto = $produtos[$id];
} else {
  die("Produto não encontrado!");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title><?= $produto['nome'] ?> - Detalhes</title>
  <link rel="stylesheet" href="../css/detalhes.css">
</head>
<body>
  <header>
    <a href="brawlstars.php">⬅ Voltar</a>
  </header>

  <div class="produto-main">
    <!-- Galeria -->
    <div class="galeria">
      <img src="<?= $produto['imagens'][0] ?>" class="imagem-principal" id="img-principal">
      <div class="miniaturas">
        <?php foreach ($produto['imagens'] as $img): ?>
          <img src="<?= $img ?>" onclick="document.getElementById('img-principal').src=this.src">
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Infos do produto -->
    <div class="produto-info">
      <h1><?= $produto['nome'] ?> <span class="tag"></span></h1>
      <p class="estoque">Disponível: <strong>1</strong></p>
      <div class="preco"><?= $produto['preco'] ?></div>
      <button class="btn-comprar">Comprar</button>
    </div>

    <!-- Vendedor -->
    <aside class="vendedor-card">
      <h3>Vendedor</h3>
      <div class="vendedor-info">
        <img src="../jogos/avatar.png" alt="Vendedor">
        <p><strong>Rian Bryan</strong> (15)</p>
        <p>Membro desde: 24/08/2023</p>
        <p>Avaliações positivas: 100%</p>
        <p>Último acesso: há 16 minutos</p>
      </div>
    </aside>
  </div>

  <!-- Características -->
  <section class="caracteristicas">
    <h2>Características</h2>
    <table>
      <tr><td>Tipo do Anúncio</td><td>Conta</td></tr>
      <tr><td>Procedência</td><td>Possui dados de recuperação</td></tr>
      <tr><td>Entrega</td><td>Imediata após confirmação</td></tr>
    </table>
  </section>

  <!-- Descrição -->
  <section class="descricao">
    <h2>Descrição do Anúncio</h2>
    <p><?= $produto['descricao'] ?></p>
    <p>✔️ Loga em todas as plataformas</p>
    <p>✔️ Nick alterável</p>
    <p>✔️ Conta com benefícios exclusivos</p>
    <p><small>Criado em: <?= date("d/m/Y H:i") ?></small></p>

    <!-- Compartilhar -->
    <div class="compartilhar">
      <span>Compartilhar:</span>
      <a href="#">WhatsApp</a> | 
      <a href="#">Telegram</a> | 
      <a href="#">Facebook</a> | 
      <a href="#">Twitter</a>
    </div>
  </section>

  <!-- Perguntas -->
  <section class="perguntas">
    <h2>Perguntas</h2>
    <p>Nenhuma pergunta até o momento 😊</p>
    <p class="aviso">Você precisa estar logado para fazer uma pergunta.</p>
  </section>
</body>
</html>
