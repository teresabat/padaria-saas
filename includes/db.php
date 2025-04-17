<?php

$host = 'localhost';
$db = 'padoca_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $db, $user, $pass);
if($conn->connect_error){
    die("Falha ao conectar ao banco de dados " . $conn->connect_error);
}