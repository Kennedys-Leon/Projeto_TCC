<?php

    $db = 1;

if( $db == 2){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "banco";
}else{
    $servername = "localhost";
    $username = "u557720587_2025_php04";
    $password = "Mtec@php4";
    $dbname = "u557720587_2025_php04";
}


try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET SESSION sql_mode='STRICT_TRANS_TABLES'");
} catch(PDOException $e) {
    echo "Erro: " . $e->getMessage();
    die();
}
?>
