<?php
$msg = "";
if ($_POST) {
    include "connect.php";
    $hostname = $_POST['hostname'];
    $ipv4 = $_POST['ipv4'];
    $mac = $_POST['mac'];
    $pat = $_POST['pat'];
    $local = $_POST['local'];
    $so = $_POST['so'];
    $atividade = $_POST['atividade'];

    $verificar = "SELECT * FROM maquina where hostname = '$hostname' or mac = '$mac'";
    $resultado = mysqli_query($conexao, $verificar);

    if (mysqli_num_rows($resultado) == 0) {
        $sql = "INSERT INTO maquina (hostname, ipv4, mac, patrimonio, local, sistema_operacional, atividade) VALUES ('$hostname', '$ipv4', '$mac', '$pat', '$local', '$so', '$atividade')";
        mysqli_query($conexao, $sql);
        if (mysqli_affected_rows($conexao) > 0) {
            $msg = "<h3> Máquina cadastrada com sucesso! </h3>";
        } else {
            $msg = "<h3> Falha ao cadastrar. </h3>";
        }
    } else {
        $msg = "<h3> Hostname ou endereço MAC já cadastrado. </h3>";
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
    <div class="topbar">
        <div class="MNET-logo">
            <img src="2MNET-logo.png" name="MNET Logo" width="150" height="100">
            <h1> MNET - IFFar </h1>
        </div>
        <div class="nav-links">
            <a href="admin.php">HOME</a>
            |
            <a href="perfil.php">PERFIL</a>
            |
            <a href="cadastro_maquina.php">CADASTRARㅤMÁQUINA</a>
            |
            <a href="cadastro.php">CADASTRARㅤUSUÁRIO</a>
            |
            <a href="listar_usuarios.php">LISTARㅤUSUÁRIOS</a>
        </div>
    </div>
    <hr>
    <form action="" method="post" required>
        <div class="fundo-perfil">
            <div class="card-perfil">
                <div class="titulo">
                    <h2>Cadastro de Máquina - Manual</h2>
                </div>
                <div class="info">
                    <?php echo "<p><b>$msg</b></p>"; ?>
                </div>
                <div class="grupo">
                    Hostname: <br>
                    <p><input type="text" name="hostname" placeholder="Ex.: Lab1-28"></p>
                </div>
                <div class="grupo">
                    Endereço IPV4: <br>
                    <p><input type="text" name="ipv4" placeholder="Ex.: 192.168.10.21/16" required></p>
                </div>
                <div class="grupo">
                    Endereço MAC: <br>
                    <p><input type="text" name="mac" required></p>
                </div>
                <div class="grupo">
                    Patrimonio: <br>
                    <p><input type="text" name="pat" required></p>
                </div>
                <div class="grupo">
                    Sistema Operacional: <br>
                    <p><input type="text" name="so" placeholder="Ex.: Windows" required></p>
                </div>
                <div class="grupo">
                    Atividade: <br>
                    <p><select name="atividade" required>
                            <option value="A">Ativa</option>
                            <option value="I">Inativa</option>
                        </select></p>

                </div>
                <div class="grupo">
                    Local: <br>
                    <p><input type="text" name="local" placeholder="Laboratório 1"></p>
                </div>
                <button class="btn-salvar" type="submit">Cadastrar Máquina</button><br>
            </div>

        </div>

    </form>

</body>

</html>