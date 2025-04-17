<?php 
    session_start();
    require_once 'includes/db.php';
    if(!isset($_SESSION['user_id'])){
        header("Location: index.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    $check = $conn->prepare("SELECT id, nome FROM padarias WHERE user_id = ?");
    $check->bind_param("id", $user_id);
    $check->execute();
    $result = $check->get_result();

    if($result->num_rows > 0){
        $padaria = $result->fetch_assoc();
        header("Location: dashboard.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $nome = $_POST['nome'];

        $stmt = $conn->prepare("INSERT INTO padarias (nome, user_id) VALUES (?,?)");
        $stmt->bind_param("si", $nome, $user_id);

        if ($stmt->execute()){
            header("Location: dashboard.php");
            exit();
        } else {
            $erro = "Erro ao cadastrar padaria: " . $stmt->error;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body class="container mt-5">
    <h2>Cadastrar sua Padaria</h2>
    <?php if(!empty($erro)) : ?>
        <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="mb-3">
                <label for="">Nome da Padaria:</label>
                <input type="text" name="nome" class="form-control" require>
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
        </form>
</body>
</html>