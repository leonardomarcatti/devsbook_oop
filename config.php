<?php
    
    session_start();
    $base = '/programacao/curso/B7Web/php/devsbook_oop/';
    $host = 'localhost';
    $db = 'devsbook';
    $user = 'root';
    $password = '';

    try {
        $conection = new PDO("mysql:host=$host;dbname=$db", "$user", "$password");
    } catch (Throwable $th) {
        echo 'Erro linha: ' . $th->getLine() . "<br>";
        echo ('Código: ' . $th->getMessage());
    };
    
?>