<?php
$msg = "";
if ($_POST) {
    include "connect.php";

    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuario where cpf = '$cpf'";
    $resultado = mysqli_query($conexao, $sql);

    //verifica se o cpf existe
    if (mysqli_affected_rows($conexao) > 0) {
        $dados = mysqli_fetch_assoc($resultado);
        //print_r($dados);
        if (password_verify("$senha", $dados['senha'])) {
            $msg = "Login realizado com sucesso!";
        } else {
            $msg = "CPF ou senha incorretos!";
        }
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
        CPF: <br>
        <input type="text" placeholder="xxx.xxx.xxx-xx" name="cpf" required><br><br>
        Senha: <br>
        <input type="password" name="senha" placeholder="senha"><br><br>

        <input type="submit" value="Entrar"><br>
        <a href='cadastro.php'> Cadastrar</a><br>
        <?php echo "<h1><b> $msg </h1></b>"; ?>

    </form>

</body>

</html>