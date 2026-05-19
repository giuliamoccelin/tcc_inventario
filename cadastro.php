<?php
$msg = "";
require_once 'connect.php';

if ($_POST) {

    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];

    $hash = password_hash("$senha", PASSWORD_DEFAULT);

    $verificar = "SELECT * FROM usuario where cpf = '$cpf' or email = '$email'";
    $resultado = mysqli_query($conexao, $verificar);

    if (mysqli_num_rows($resultado) == 0) {
        if (mysqli_affected_rows($conexao) > 0) {
            header("location:index.php"); //voltar para pág. index.php
        } else {
            $msg = "<h3> Falha ao cadastrar. </h3>";
        }
    } else {
        $msg = "<h3> CPF ou e-mail já cadastrados. </h3>";
    }

    $sql = "INSERT INTO usuario (email, senha, nome, cpf) values ('$email', '$hash', '$nome', '$cpf')";
    mysqli_query($conexao, $sql);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<meta charset="utf-8">

<head>
    <title> MNET - IFFar </title>
</head>

<body>
    <h1> Criar conta</h1>
    <hr>
    <form action="cadastro.php" method="post" required>
        Nome: <br>
        <input type="text" name="nome" required><br><br>
        CPF: <br>
        <input type="text" placeholder="xxx.xxx.xxx-xx" name="cpf" required><br><br>
        E-mail: <br>
        <input type="email" name="email" required><br><br>
        Senha: <br>
        <input type="password" name="senha" required><br><br>

        <input type="submit" value="Cadastrar"><br>

        <?php echo $msg; ?>
    </form>

</body>

</html>
