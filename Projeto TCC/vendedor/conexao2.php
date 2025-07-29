<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "banco_vendedor";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET SESSION sql_mode='STRICT_TRANS_TABLES'");
    //$conn->exec("SET SESSION sql_mode=''");
    //echo "Conectado com sucesso!";
} catch(PDOException $e) {
    echo "Erro: " . $e->getMessage();
    die();
}
?>