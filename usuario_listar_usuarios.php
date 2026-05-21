<?php
$msg = "";
if ($_POST) {
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
    <div class="topbar">
        <div class="MNET-logo">
            <img src="2MNET-logo.png" name="MNET Logo" width="150" height="100">
            <h1> MNET - IFFar </h1>
        </div>
        <div class="nav-links">
            <a href="home.php">HOME</a>
            |
            <a href="usuario_perfil.php">PERFIL</a>
            |
            <a href="usuario_listar_usuarios.php">LISTARㅤUSUÁRIOS</a>
        </div>
    </div>
    <hr>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>E-mail</th>
                <th>Cargo</th>
                <th>Data de Cadastro</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "connect.php";
            $sql = "SELECT * FROM usuario";
            $resultado = mysqli_query($conexao, $sql);

            while ($dados = mysqli_fetch_assoc($resultado)) {

                echo "<tr>";
                echo "<td><strong>{$dados['id']}</strong></td>";
                echo "<td>{$dados['nome']}</td>";
                echo "<td>{$dados['cpf']}</td>";
                echo "<td>{$dados['email']}</td>";
                echo "<td>";
                if ($dados['cargo'] == 'A') {
                    echo "Administrador";
                } else {
                    echo "Usuário Comum";
                }
                echo "</td>";
                echo "<td>{$dados['data_registro']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    </div>

</body>

</html>