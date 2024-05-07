<?php
$dsn='mysql:host=localhost;dbname=jobapp';
$user='root';
$password='';

try {
    $pdo=new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    echo 'CONNECTION FAILED'. $e->getMessage();
}


?>