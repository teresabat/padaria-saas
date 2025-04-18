<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])){
    echo "Produto inválido.";
    exit();
}

$produto_id = $_GET['id'];

//query p verificar se o produto pertence ao user
$verifica = $conn->prepare("
    SELECT p.id FROM produtos p
    JOIN padarias pad ON p.padria_id = pad.id
    WHERE p.id = ? AND pad.user_id?
");
$verifica->bind_param("ii", $produto_id, $user_id);
$verifica->execute();
$result = $verifica->get_result();

if ($result->num_rows == 0){
    echo "Produto nao encontrado ou vc n tem permissao.";
    exit();
}

//delete product
$delete = $conn->prepare("DELETE FROM produtos WHERE id = ?");
$delete->bind_param("i", $produto_id);

if ($delete->execute()){
    header("Location: produtos.php");
    exit();
} else {
    echo "Erro ao excluir produto: " . $delete->error;
}
?>