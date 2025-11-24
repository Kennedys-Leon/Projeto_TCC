<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
// verifica se é admin
if (!isset($_SESSION['usuario_nome']) || $_SESSION['usuario_nome'] !== 'adm') {
header('Location: ../../login/login.php');
exit;
}
include __DIR__ . '/../../conexao.php';
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Painel Admin - MaxAcess</title>
<link rel="stylesheet" href="/../../css/estilo.css">
<style>
/* estilos simples para o painel */
body{font-family:Arial, sans-serif;background:#f4f6fb;color:#222}
.admin-wrap{max-width:1100px;margin:24px auto;padding:20px;background:#fff;border-radius:10px}
nav.admin-nav{display:flex;gap:8px;margin-bottom:16px}
nav.admin-nav a{padding:8px 12px;background:#111;color:#fff;border-radius:6px;text-decoration:none}
table{width:100%;border-collapse:collapse}
th,td{padding:10px;border-bottom:1px solid #eee}
.btn{padding:6px 10px;border-radius:6px;border:none;cursor:pointer}
.btn-danger{background:#e74c3c;color:#fff}
.btn-primary{background:#8C5B3F;color:#fff}
</style>
</head>
<body>
<div class="admin-wrap">
<header>
<h1>Painel Administrativo</h1>
<p>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></p>
</header>
<nav class="admin-nav">
<a href="index.php">Dashboard</a>
<a href="usuarios.php">Usuários</a>
<a href="vendedores.php">Vendedores</a>
<a href="produtos.php">Produtos</a>
<a href="categorias.php">Categorias</a>
<a href="subcategorias.php">Subcategorias</a>
</nav>