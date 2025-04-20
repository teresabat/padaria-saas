<?php 
session_start();
include_once 'includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$padariaQuery = $conn->prepare("SELECT id FROM padarias WHERE user_id = ?");
$padariaQuery->bind_param("i", $user_id);
$padariaQuery->execute();
$padariaResult = $padariaQuery->get_result();

if ($padariaResult->num_rows == 0){
    echo "Padaria não encontrada.";
    exit();
}

$padaria = $padariaResult->fetch_assoc();
$padaria_id = $padaria['id'];

$stmt = $conn->prepare("SELECT * FROM produtos WHERE padaria_id = ?");
$stmt->bind_param("i", $padaria_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    
<div class="d-flex justify-content-between mb-3">
    <a href="dashboard.php" class="btn btn-secondary mb-3">Voltar</a>
    <a href="criar_produto.php" class="btn btn-primary mb-3">Adicionar Produto</a>
</div>

    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while($produto = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($produto['nome']) ?></td>
                    <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                    <td><?= $produto['estoque'] ?></td>
                    <td>
                        <a href="editar_produto.php?id=<?= $produto['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="excluir_produto.php?id=<?= $produto['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
