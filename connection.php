<?php

    $host = "localhost";    
    $user = "root";
    $password = "";
    $db = "tienda";

    $dsn = "mysql:host=$host;dbname=$db";

    $link = new PDO($dsn, $user, $password);

    // print_r($link);