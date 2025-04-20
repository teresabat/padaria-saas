<?php
session_start();
require_once 'includes/db.php';

// Verificar se o usuário está logado
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}

// ID do usuário
$user_id = $_SESSION['user_id'];

// Consultar a padaria vinculada ao usuário
$padariaQuery = $conn->prepare("SELECT id FROM padarias WHERE user_id = ?");
$padariaQuery->bind_param("i", $user_id);
$padariaQuery->execute();
$padariaResult = $padariaQuery->get_result();

if ($padariaResult->num_rows == 0) {
    echo "Padaria não encontrada.";
    exit();
}

$padaria = $padariaResult->fetch_assoc();
$padaria_id = $padaria['id'];

// Consultar os produtos da padaria e calcular o resumo de estoque
$stmt = $conn->prepare("SELECT SUM(estoque) AS total_estoque, COUNT(id) AS total_produtos FROM produtos WHERE padaria_id = ?");
$stmt->bind_param("i", $padaria_id);
$stmt->execute();
$result = $stmt->get_result();
$estoqueResumo = $result->fetch_assoc();

$totalEstoque = $estoqueResumo['total_estoque'];
$totalProdutos = $estoqueResumo['total_produtos'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Resumo de Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="mb-4">Dashboard - Resumo de Estoque</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total de Produtos no Estoque</h5>
                    <p class="card-text"><?= $totalEstoque ?> unidades</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total de Produtos Cadastrados</h5>
                    <p class="card-text"><?= $totalProdutos ?> produtos</p>
                </div>
            </div>
        </div>
    </div>
    <a href="produtos.php" class="btn btn-secondary mt-3">Gerenciar Produtos</a>
    <a href="logout.php" class="btn btn-danger mt-3">Sair</a>
</body>
</html>
