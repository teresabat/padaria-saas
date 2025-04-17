<?php
session_start();
    require_once 'includes/db.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?,?,?)");
        $stmt->bind_param("sss", $nome, $email, $senha);

        if($stmt->execute()){
            $_SESSION['user_id'] = $stmt->insert_id;
            header("Location: padaria.php");
            exit();
        }else{
            echo "Erro ao cadastrar: " . $stmt->error;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=
    , initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Cadastro</title>
</head>
<body class="container mt-5">
    <h2>Cadastro de usuário</h2>
    <form action="" method="post">
        <div class="mb-3">
            <label for="">Nome:</label>
            <input type="text" name="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="">Email:</label>
            <input type="text" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="">Senha:</label>
            <input type="password" name="senha" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Cadastrar</button>
        <a href="index.php" class="btn btn-link">Já tem conta?</a>
    </form>
</body>
</html>