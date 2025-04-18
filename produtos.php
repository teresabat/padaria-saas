<?php 
session_start();
include_once 'includes/db.php';


if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}

//id da padaria vinculada ao user
$user_id = $_SESSION['user_id'];

$padariaQuery = $conn->prepare("SELECT id FROM padarias WHERE user_id = ?");
$padariaQuery->bind_param("id", $user_id);
$padariaQuery->execute();
$padariaQuery = $padariaQuery->get_result();

if ($padariaQuery->num_rows == 0){
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <a href="" class="btn btn-primary mb-3">Adicionar Produto</a>

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
                    <td><?= number_format($produto['preco'], 2, ',', '.') ?></td>
                    <td><?= $produto['estoque'] ?></td>
                    <td>
                        <a href="editar_produto.php?id=<?= $produto['id'] ?>" class="btn btn-warning btn-sm">Excluir</a>
                        <a href="excluir_produto.php?id=<?= $produto['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                    </td>
                </tr>
                <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>