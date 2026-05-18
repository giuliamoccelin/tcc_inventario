<?php
include 'connect.php';
$msg = "";
if ($_POST) {
    $cpf = $_POST['cpf'];
    $sql = "SELECT * FROM usuario where cpf = '$cpf'";
    $resultado = mysqli_query($conexao, $sql);
    if (mysqli_affected_rows($conexao) > 0) {
        $dados = mysqli_fetch_assoc($resultado);
        //print_r($dados);
        $msg = "<h1><b> Nome: " . $dados['nome'] . "</h1></b><br>" . "<h1><b> CPF: " . $dados['cpf'] . "</h1></b><br>" . "<h1><b> E-mail: " . $dados['email'] . "</h1></b><br>";
    } else {
        $msg = "CPF não encontrado!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<meta charset="utf-8">

<head>
    <title> MNET-IFFar </title>
</head>

<body>
    <h1> MNET -IFFar </h1>
    <hr>
    <form action="" method="post" required>
       

        <input type="submit" value="Entrar"><br>
        <?php echo "<h1><b> $msg </h1></b>"; ?>

    </form>

</body>

</html>