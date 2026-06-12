<?php
include "connect.php";
$msg = "";
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: index.php?msg=Acesso negado.');
    exit();
}
$dados_equip['id'] = $_GET['id'];
$dados_equip['hostname'] = $_GET['hostname'];
$dados_equip['mac'] = $_GET['mac'];

if ($_POST) {
    $equipamentos = $_POST['hostname'];
    $mac = $_POST['mac'];
    $tipo_manutencao = $_POST['tipo_manutencao'];
    $descricao = $_POST['descricao'];
    $custo = $_POST['custo'];
    $email = $_SESSION['email'];

    $sql_equip = "SELECT * FROM equipamento WHERE hostname = '$equipamentos' AND mac = '$mac'";
    $resultado_equip = mysqli_query($conexao, $sql_equip);

    $sql_usuario = "SELECT * FROM usuario WHERE email = '$email'";
    $resultado_usuario = mysqli_query($conexao, $sql_usuario);

    if (mysqli_num_rows($resultado_equip) > 0 and mysqli_num_rows($resultado_usuario) > 0) {

        $dados_equip = mysqli_fetch_assoc($resultado_equip);
        $dados_user = mysqli_fetch_assoc($resultado_usuario);

        $id_equipamento = $dados_equip['id'];
        $id_usuario = $dados_user['id'];

        $sql_insert = "INSERT INTO manutencao (id_equipamento, id_usuario, tipo_manutencao, descricao, custo) 
                       VALUES ('$id_equipamento', '$id_usuario', '$tipo_manutencao', '$descricao', '$custo')";

        mysqli_query($conexao, $sql_insert);

        if (mysqli_affected_rows($conexao) > 0) {
            $msg = "Manutenção cadastrada com sucesso!";
        } else {
            $msg = "Falha ao cadastrar no banco de dados.";
        }
    } else {
        $msg = "Equipamento não encontrado com o Hostname e IP informados.";
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
    <?php include "menu.php"; ?>

    <form action="" method="post">
        <div class="fundo-perfil">
            <div class="card-perfil">
                <div class="titulo">
                    <h2>Cadastro de Manutenção - Manual</h2>
                </div>

                <div class="info">
                    <p><b><?php echo $msg; ?></b></p>
                </div>
                ID:<br>
                <p><input type="text" name="id" value="<?php echo $dados_equip['id']; ?>" readonly></p>
                <div class="grupo">
                    Equipamento: <br>
                    <p><input type="text" name="hostname" value="<?php echo $dados_equip['hostname']; ?>" readonly></p>
                </div>
                <div class="grupo">
                    Endereço MAC: <br>
                    <p><input type="text" name="mac" value="<?php echo $dados_equip['mac']; ?>" readonly></p>
                </div>
                <div class="grupo">
                    Tipo de manutenção: <br>
                    <p><select name="tipo_manutencao" required>
                            <option value='Preventiva'>Preventiva</option>
                            <option value='Software'>Software</option>
                            <option value='Hardware'>Hardware</option>
                            <option value='Rede'>Rede</option>
                        </select></p>
                </div>
                <div class="grupo">
                    Descrição: <br>
                    <p><input type="text" name="descricao" placeholder="Ex.: Limpeza do computador" required></p>
                </div>
                <div class="grupo">
                    Custo: <br>
                    <p><input type="number" name="custo" placeholder="Ex.: 150.00" step="0.01" required></p>
                </div>

                <button class="btn-salvar" type="submit">Cadastrar Manutenção</button><br>
            </div>
        </div>
    </form>
</body>

</html>