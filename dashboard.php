<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT nome FROM padarias where user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0){
    header("Location: padaria.php");
    exit();
}

$padaria = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Padaria SaaS</title>
</head>
<body class="container mt-5">
    <h1>Bem vindo à sua Padaria!</h1>
    <a href="logout.php" class="btn btn-danger">Sair</a>
</body>
</html>