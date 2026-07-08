<?php
include "connect.php";
$msg = "";
session_start();

// verifica se existe uma sessão válida, senão redireciona para a página de login
if (!isset($_SESSION['email'])) {
    header('Location: index.php?msg=Acesso negado.');
    exit();
}
$id = $_GET['id'];
$sql = "DELETE FROM usuario WHERE id = '$id'";
mysqli_query($conexao, $sql);

//executar o comando sql
if (mysqli_affected_rows($conexao) > 0) {
    header("Location: listar_usuarios.php");
    exit();
} else {
    $msg = 'Falha ao deletar';
}
