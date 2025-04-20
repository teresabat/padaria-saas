<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'sistema_padarias';

$conn = new mysqli($host, $user, $pass, $db);
if($conn->connect_error){
    die("Falha ao conectar ao banco de dados " . $conn->connect_error);
}