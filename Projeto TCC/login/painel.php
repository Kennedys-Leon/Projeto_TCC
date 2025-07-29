<?php
    session_start();
    if(!isset($_SESSION['usuario_nome'])){
        header('Location:login.php?error=1');
    }

    echo "<h2> Bem Vindo, ".$_SESSION['usuario_nome']."</h1>";
?>