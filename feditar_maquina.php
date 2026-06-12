<?php
include "connect.php";

$msg = "";
session_start();

// verifica se existe uma sessão válida, senão redireciona para a página de login
if (!isset($_SESSION['email'])) {
    header('Location: index.php?msg=Acesso negado.');
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
    <div class="topbar">
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
                        ID: <br>
                        <p><input type="text" name="id" value="<?php echo $dados['id']; ?>" readonly></p>
                    </div>
                    <div class="grupo">
                        Hostname: <br>
                        <p><input type="text" name="hostname" value="<?php echo $dados['hostname']; ?>" "></p>
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
                        <p><input type="text" name="pat" value="<?php echo $dados['patrimonio']; ?>"></p>
                    </div>
                    <div class="grupo">
                        Sistema Operacional: <br>
                        <p><input type="text" name="so" value="<?php echo $dados['sistema_operacional']; ?>" </p>
                    </div>
                    <div class="grupo">
                        Atividade: <br>
                        <p><select name="atividade" value="<?php echo $dados['atividade']; ?>" required>
                                <option value="A">Ativa</option>
                                <option value="I">Inativa</option>
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
                        Equipamento: <br>
                        <p><input type="text" name="equipamento" value="<?php echo $dados['equipamento']; ?>"></p>
                    </div>
                    <button class="btn-salvar" type="submit">Cadastrar Máquina</button><br>
                </div>

            </div>

        </form>

</body>

</html>