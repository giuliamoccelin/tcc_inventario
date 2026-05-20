<?php
$msg = "";

if ($_POST) {
    include "connect.php";

    setcookie("email", $_POST['email'], time() + 3600);

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuario where email = '$email'";
    $resultado = mysqli_query($conexao, $sql);

    //verifica se o e-mail existe
    if (mysqli_affected_rows($conexao) > 0) {
        $dados = mysqli_fetch_assoc($resultado);
        //print_r($dados);
        if (password_verify("$senha", $dados['senha'])) {
            if ($dados['cargo'] == "A") {
                header("location:admin.php");
            } else {
                header("location:home.php");
            }
        } else {
            $msg = "E-mail ou senha incorretos!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<meta charset="utf-8">

<head>
    <title> MNET-IFFar </title>
    <link rel="icon" type="image/png" href="2MNET-logo.png">

</head>

<body>
    <link rel="stylesheet" type="text/css" href="style.css">

    <div class="MNET-logo">
        <style>
            .MNET-logo {
                display: flex;
                align-items: center;
                font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                color: rgb(35, 62, 132);
                justify-content: center;
            }
        </style>
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
                    E-mail: <br>
                    <p> <input type="email" name="email" required></p>
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