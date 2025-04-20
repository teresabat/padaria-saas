<?php 
session_start();
require_once 'includes/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT id, senha FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $stmt->store_result();
    if ($stmt->num_rows === 1){
        $stmt->bind_result($id, $senhaHash);
        $stmt->fetch();

        if  (password_verify($senha, $senhaHash)){
            $_SESSION['user_id'] = $id;
            header("Location: dashboard.php");
            exit();
        }else{
            $erro= "Senha incorreta!";
        }
    }   else {
        $erro = "Usuário não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Padaria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Login</h2>
    <?php if(!empty($erro)) : ?>
        <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="mb-3">
                <label for="">Email:</label>
                <input type="email" name="email" class="form-control" require>
            </div>
            <div class="mb-3">
                <label for="">Senha:</label>
                <input type="password" name="senha" class="form-control" require>
            </div>
            <button type="submit" class="btn btn-primary">Entrar</button>
            <a href="register.php" class="btn btn-link">Criar conta</a>
        </form>
</body>
</html>