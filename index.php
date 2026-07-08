<?php
$msg = "";
session_start();

if ($_GET) {
    $msg = $_GET['msg'];
}

if ($_POST) {
    include "connect.php";

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuario where email = '$email'";
    $resultado = mysqli_query($conexao, $sql);

    //verifica se o e-mail existe
    if (mysqli_num_rows($resultado) > 0) {
        // armazena os dados do usuário em um array associativo
        $dados = mysqli_fetch_assoc($resultado);
        //print_r($dados);
        if (password_verify("$senha", $dados['senha'])) {
            $_SESSION['email'] = $email;
            if ($dados['cargo'] == "A") {
                header("location:admin.php");
            } else {
                header("location:home.php");
            }
        } else {
            $msg = "E-mail ou senha incorretos!";
        }
    } else {
        $msg = "Você não tem cadastro! <br> Converse com o administrador para criar uma conta.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php include "head.php"; // Inclui o head de navegação 
    ?>
</head>

<body>
    <div class="MNET-logo">
        <style>
            .MNET-logo {
                display: flex;
                align-items: center;
                font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                color: rgb(35, 62, 132);
                justify-content: center;
                padding: 20px;
            }
        </style>
        <img src="2MNET-logo.png" name="MNET Logo" width="110" height="100">
        <h1>ㅤMNET - IFFar </h1>
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