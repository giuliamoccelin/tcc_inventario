<?php
$msg = "";

if ($_POST) {
    include "connect.php";

    setcookie("cpf", $_POST['cpf'], time() + 3600);

    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuario where cpf = '$cpf'";
    $resultado = mysqli_query($conexao, $sql);
    if (isset($_SESSION['cpf'])) {
        header("location:home.php");
    } else {
        //verifica se o cpf existe
        if (mysqli_affected_rows($conexao) > 0) {
            $dados = mysqli_fetch_assoc($resultado);
            //print_r($dados);
            if (password_verify("$senha", $dados['senha'])) {
                header("location:home.php");
            } else {
                $msg = "CPF ou senha incorretos!";
            }
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
    <link rel="stylesheet" type="text/css" href="style.css">

    <div class="MNET-logo">
        <img src="2MNET-logo.png" name="MNET Logo" width="150" height="100">
        <h1> MNET - IFFar </h1>
    </div>
    <hr>
    <form action="" method="post" required>
        <div class="fundo-perfil">
            <div class="card-perfil">
                <div class="titulo">
                    <h2>Login</h2>
                </div>
                <div class="info">
                    <?php echo "<p><b>$msg</b></p>"; ?>
                </div>

                <div class="grupo">
                    CPF: <br>
                    <p> <input type="text" placeholder="xxx.xxx.xxx-xx" name="cpf" required></p>
                </div>
                <div class="grupo">
                    Senha: <br>
                    <p> <input type="password" name="senha" placeholder="senha"></p>
                </div>
                <button class="btn-salvar" type="submit">Entrar</button><br>
            </div>

        </div>

    </form>

</body>

</html>
