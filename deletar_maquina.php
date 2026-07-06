<?php
include "connect.php";
$msg = "";
session_start();

// verifica se existe uma sessão válida, senão redireciona para a página de login
if(!isset($_SESSION['email'])){
    header('Location: index.php?msg=Acesso negado.');
    exit();
}
// recebe id via link
$id= $_GET['id'];

// DELETE - deleta o contato com o ID informado
$sql = "DELETE FROM equipamento WHERE id = $id";
mysqli_query($conexao, $sql);

//executar o comando sql
if (mysqli_affected_rows($conexao) > 0) {
    header("Location: listar_maquinas.php");
} else {
    echo "<h3> Falha ao deletar. </h3>";
}

//refazer o codigo

?>