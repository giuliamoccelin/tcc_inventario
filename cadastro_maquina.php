<?php
include "connect.php";
$msg = "";
session_start();

// verifica se existe uma sessão válida, senão redireciona para a página de login
if (!isset($_SESSION['email'])) {
    header('Location: index.php?msg=Acesso negado.');
    exit();
}
if ($_POST) {
    $hostname = $_POST['hostname'];
    $ipv4 = $_POST['ipv4'];
    $mac = $_POST['mac'];
    $pat = $_POST['pat'];
    $local = $_POST['local'];
    $so = $_POST['so'];
    $atividade = $_POST['atividade'];
    $marca = $_POST['marca'];
    $tipo_equipamento = $_POST['tipo_equipamento'];

    $verificar = "SELECT * FROM equipamento where hostname = '$hostname' or mac = '$mac'";
    $resultado = mysqli_query($conexao, $verificar);

    if (mysqli_num_rows($resultado) == 0) {
        $sql = "INSERT INTO equipamento (hostname, ipv4, mac, patrimonio, local, sistema_operacional, atividade, marca, tipo_equipamento) VALUES ('$hostname', '$ipv4', '$mac', '$pat', '$local', '$so', '$atividade', '$marca', '$tipo_equipamento')";
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

<head>
    <title> MNET-IFFar </title>
    <link rel="icon" type="image/png" href="2MNET-logo.png">
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="utf-8">

</head>

<body>
    <?php include "menu.php"; // Inclui o menu de navegação 
    ?>
    <form action="" method="post" required>
        <div class="fundo-perfil">
            <div class="card-perfil">
                <div class="titulo">
                    <h2>Cadastro de Equipamento - Manual</h2>
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
                            <option value='Em uso'>Em uso</option>
                            <option value='Disponível'>Disponível</option>
                            <option value='Descarte'>Descarte</option>
                            <option value='Manutenção'>Manutenção</option>
                        </select></p>

                </div>
                <div class="grupo">
                    Local: <br>
                    <p><input type="text" name="local" placeholder="Laboratório 1"></p>
                </div>
                <div class="grupo">
                    Marca: <br>
                    <p><input type="text" name="marca" placeholder="Ex.: Dell"></p>
                </div>
                <div class="grupo">
                    Tipo de Equipamento: <br>
                    <p><input type="text" name="tipo_equipamento" placeholder="Ex.: Notebook"></p>
                </div>
                <button class="btn-salvar" type="submit">Cadastrar Equipamento</button><br>
            </div>

        </div>

    </form>

</body>

</html>