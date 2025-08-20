<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "banco";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); // <--- alterado aqui
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET SESSION sql_mode='STRICT_TRANS_TABLES'");
} catch(PDOException $e) {
    echo "Erro: " . $e->getMessage();
    die();
}
?>
