<?php
session_start();
require_once 'includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])){
    echo "Produto inválido.";
    exit();
}

$produto_id = $_GET['id'];

//verificar se o produto pertence a padaria do user
$verifica = $conn->prepare("
    SELECT .id, p.nome, p.preco, p.estoque
    FROM produtos p
    JOIN padarias pad ON p.padaria_id = pad.id
    WHERE p.id ? AND pad.user_id = ?
");
$verifica->bind_param("ii", $produto_id, $user_id);
$verifica->execute();
$result = $verifica->get_result();

if ($result->num_rows == 0){
    echo "Produto não encontrado ou você não tem permissão.";
    exit();
}

$produto = $result->fetch_assoc();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];

    $stmt = $conn->prepare("UPDATE produtos SET nome = ?, preco = ?, estoque = ? WHERE id = ?");
    $stmt->bind_param("sdii", $nome, $preco, $estoque, $produto_id);

    if ($stmt->execute()){
        header("Location: produtos.php");
        exit();
    } else {
        $erro = "Erro ao atualizar produto: " . $stmt->error;
    }
}
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
    <h2>Editar Produto</h2>

    <?php if (!empty($erro)) : ?>
        <div class="alert alert-danger"><?= $erro ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="mb-3">
            <label for="">Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="">Preço (R$):</label>
            <input type="number" step="0.01" name="preco" value="<?= $produto['preco'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="">Estoque:</label>
            <input type="number" name="estoque" value="<?= $produto['estoque'] ?>" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="produtos.php" class="btn btn-secondary">Voltar</a>
    </form>
    
</body>
</html>