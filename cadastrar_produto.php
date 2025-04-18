<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$padariaQuery = $conn->prepare("SELECT id FROM padarias WHERE user_id = ?");
$padariaQuery->bind_param("i", $user_id);
$padariaQuery->execute();
$padariaResult = $padariaQuery->get_result();

if($padariaResult->num_rows == 0){
    echo "Padaria não encontrada.";
    exit();
}

$padaria = $padariaResult->fetch_assoc();
$padaria_id = $padaria['id'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];

    $stmt = $conn->prepare("INSERT INTO produtos (nome, preco, estoque, padaria_id) VALUES (?,?,?,?");
    $stmt->bind_param("sdii", $nome, $preco, $estoque, $padaria_id);

    if ($stmt->execute()){
        header("Location: produtos.php");
        exit();
    } else {
        $erro = "Erro ao cadastrar produto : " . $stmt->error;
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
    <h2>Cadastri de Produto</h2>

    <?php if (!empty($erro)) : ?>
        <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label for="">Nome do produto:</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="">Preço:</label>
                <input type="number" step="0.01" name="preco" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="">Estoque:</label>
                <input type="number" name="estoque" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="produtos.php" class="btn btn-secondary">Voltar</a>
        </form>
</body>
</html>