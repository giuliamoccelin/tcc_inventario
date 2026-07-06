<?php
include "connect.php";
$msg = "";
session_start();

// verifica se existe uma sessão válida, senão redireciona para a página de login
if (!isset($_SESSION['email'])) {
    header('Location: index.php?msg=Acesso negado.');
    exit();
}
$id = $_GET['id'];
// 1. Busca os dados atuais da máquina usando o id do GET
$sql = "SELECT * FROM equipamento WHERE id = '$id'";
$resultado = mysqli_query($conexao, $sql);
$dados = mysqli_fetch_assoc($resultado);

// 2. Processa a atualização quando o formulário é enviado
if ($_POST) {
    $hostname = mysqli_real_escape_string($conexao, $_POST['hostname']);
    $ipv4 = mysqli_real_escape_string($conexao, $_POST['ipv4']);
    $mac = mysqli_real_escape_string($conexao, $_POST['mac']);
    $patrimonio = mysqli_real_escape_string($conexao, $_POST['patrimonio']);
    $local = mysqli_real_escape_string($conexao, $_POST['local']);
    $sistema_operacional = mysqli_real_escape_string($conexao, $_POST['sistema_operacional']);
    $marca = mysqli_real_escape_string($conexao, $_POST['marca']);
    $tipo_equipamento = mysqli_real_escape_string($conexao, $_POST['tipo_equipamento']);
    $atividade = mysqli_real_escape_string($conexao, $_POST['atividade']);

    $sql_update = "UPDATE equipamento SET hostname = '$hostname', ipv4 = '$ipv4', mac = '$mac', patrimonio = '$patrimonio', local ='$local', sistema_operacional='$sistema_operacional', atividade='$atividade', marca='$marca', tipo_equipamento = '$tipo_equipamento' where id = $id";

    if (mysqli_query($conexao, $sql_update)) {
        $msg = "Equipamento atualizado com sucesso!";

        $dados['hostname'] = $hostname;
        $dados['ipv4'] = $ipv4;
        $dados['mac'] = $mac;
        $dados['patrimonio'] = $patrimonio;
        $dados['local'] = $local;
        $dados['sistema_operacional'] = $sistema_operacional;
        $dados['marca'] = $marca;
        $dados['tipo_equipamento'] = $tipo_equipamento;
        $dados['atividade'] = $atividade;
    } else {
        $msg = "Erro técnico ao atualizar banco de dados.";
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
    <?php include "menu.php"; // Inclui o menu de navegação 
    ?>
    <form action="" method="post" required>
        <div class="fundo-perfil">
            <div class="card-perfil">
                <div class="titulo">
                    <h2>Configurações de equipamento - <?php echo $dados['hostname']; ?></h2>
                </div>
                <div class="info">
                    <?php echo "<p><b>$msg</b></p>"; ?>
                </div>
                <div class="grupo">
                    Hostname: <br>
                    <p><input type="text" name="hostname" value="<?php echo $dados['hostname']; ?>"></p>
                </div>
                <div class=" grupo">
                    Endereço IPV4: <br>
                    <p><input type="text" name="ipv4" value="<?php echo $dados['ipv4']; ?>"></p>
                </div>
                <div class="grupo">
                    Endereço MAC: <br>
                    <p><input type="text" name="mac" value="<?php echo $dados['mac']; ?>"></p>
                </div>
                <div class="grupo">
                    Patrimonio: <br>
                    <p><input type="text" name="patrimonio" value="<?php echo $dados['patrimonio']; ?>"></p>
                </div>
                <div class="grupo">
                    Sistema Operacional: <br>
                    <p><input type="text" name="sistema_operacional" value="<?php echo $dados['sistema_operacional']; ?>" 1> </p>
                </div>
                <div class="grupo">
                    Atividade: <br>
                    <p><select name="atividade" value="<?php echo $dados['atividade']; ?>" required>
                            <option value='Em uso'>Em uso</option>
                            <option value='Disponível'>Disponível</option>
                            <option value='Descarte'>Descarte</option>
                            <option value='Manutenção'>Manutenção</option>
                        </select></p>

                </div>
                <div class="grupo">
                    Local: <br>
                    <p><input type="text" name="local" value="<?php echo $dados['local']; ?>"></p>
                </div>
                <div class="grupo">
                    Marca: <br>
                    <p><input type="text" name="marca" value="<?php echo $dados['marca']; ?>"></p>
                </div>
                <div class="grupo">
                    Tipo de equipamento: <br>
                    <p><input type="text" name="tipo_equipamento" value="<?php echo $dados['tipo_equipamento']; ?>"></p>
                </div>
                <button class="btn-salvar" type="submit">Editar Máquina</button><br>
                <div class="btn-voltar">
                <a href="listar_maquinas.php"> <-- Voltar</a>
                </div>
            </div>

        </div>

    </form>

</body>

</html>