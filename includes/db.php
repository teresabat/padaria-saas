<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'padaria_saas';

$conn = new mysqli($host, $user, $pass, $db);
if($conn->connect_error){
    die("Falha ao conectar ao banco de dados " . $conn->connect_error);
}