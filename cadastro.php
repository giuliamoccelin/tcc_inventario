<?php
$msg = "";
include 'connect.php';

if ($_POST) {

    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];

    $hash = password_hash("$senha", PASSWORD_DEFAULT);

    $verificar = "SELECT * FROM usuario where cpf = '$cpf' or email = '$email'";
    $resultado = mysqli_query($conexao, $verificar);

    if (mysqli_num_rows($resultado) == 0) {
        $sql = "INSERT INTO usuario (email, senha, nome, cpf) values ('$email', '$hash', '$nome', '$cpf')";
        mysqli_query($conexao, $sql);
        if (mysqli_affected_rows($conexao) > 0) {
            header("location:index.php");
        } else {
            $msg = "<h3> Falha ao cadastrar. </h3>";
        }
    } else {
        $msg = "<h3> CPF ou e-mail já cadastrados. </h3>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<meta charset="utf-8">

<head>
    <title> MNET - IFFar </title>
</head>

<body>
    <link rel="stylesheet" type="text/css" href="style.css">

    <div class="MNET-logo">
        <img src="2MNET-logo.png" name="MNET Logo" width="150" height="100">
        <h1> MNET - IFFar </h1>
    </div>
    <hr>
    <form action="cadastro.php" method="post" required>
        <div class="fundo-perfil">

            <div class="card-perfil">
                <div class="titulo">
                    <h2>Cadastro de Usuário</h2>
                </div>
                <div class="info">
                    <?php echo "<p><b>$msg</b></p>"; ?>
                </div>
                <div class="grupo">
                    Nome: <br>
                    <p><input type="text" name="nome" required></p>
                </div>
                <div class="grupo">
                    CPF: <br>
                    <p><input type="text" placeholder="xxx.xxx.xxx-xx" name="cpf" required></p>
                </div>
                <div class="grupo">
                    E-mail: <br>
                    <p><input type="email" name="email" required></p>
                </div>
                <div class="grupo">
                    Senha: <br>
                    <p><input type="password" name="senha" required></p>
                </div>
                <button class="btn-salvar" type="submit">Cadastrar</button><br>

            </div>
        </div>
    </form>

</body>

</html>
