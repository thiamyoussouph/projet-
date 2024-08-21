<?php
$host='localhost';
$dbname="gestion_de_pointages";
$user="root";
$password="";
try {
    $db= new PDO("mysql:host=$host; dbname=$dbname" , $user , $password);
    $db-> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOExecption $e) {

    die("erreur de connexion:".$e->getMessage());
}





?>