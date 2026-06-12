<?php
include 'connect.php';
$msg = "";
session_start();

// verifica se existe uma sessão válida, senão redireciona para a página de login
if(!isset($_SESSION['email'])){
    header('Location: index.php?msg=Acesso negado.');
    exit();
}

if ($_POST) {

    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $cargo = $_POST['cargo'];

    $hash = password_hash("$senha", PASSWORD_DEFAULT);

    $verificar = "SELECT * FROM usuario where cpf = '$cpf' or email = '$email'";
    $resultado = mysqli_query($conexao, $verificar);

    if (mysqli_num_rows($resultado) == 0) {
        $sql = "INSERT INTO usuario (email, senha, nome, cpf, cargo) values ('$email', '$hash', '$nome', '$cpf', '$cargo')";
        mysqli_query($conexao, $sql);
        if (mysqli_affected_rows($conexao) > 0) {
            $msg = "<h3> Usuário cadastrado com sucesso! </h3>";
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

<head>
    <title> MNET-IFFar </title>
    <link rel="icon" type="image/png" href="2MNET-logo.png">
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="utf-8">

</head>

<body>
   <?php include "menu.php"; // Inclui o menu de navegação 
    ?>
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
                    Cargo: <br>
                    <p><select name="cargo" required>
                            <option value="A">Administrador</option>
                            <option value="U">Usuário Comum</option>
                        </select></p>
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