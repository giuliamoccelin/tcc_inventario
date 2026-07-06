<?php
include "connect.php";
$msg = "";
session_start();

// verifica se existe uma sessão válida, senão redireciona para a página de login
if (!isset($_SESSION['email'])) {
    header('Location: index.php?msg=Acesso negado.');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php include "head.php"; // Inclui o head de navegação 
    ?>
</head>

<body>
    <?php include "menu_usuario.php"; // Inclui o menu de navegação 
    ?>
    <div class="fundo-tabela">
        <div class="card-tabela">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hostname</th>
                        <th>IPv4</th>
                        <th>MAC</th>
                        <th>Patrimônio</th>
                        <th>Local</th>
                        <th>Sistema Operacional</th>
                        <th>Marca</th>
                        <th>Equipamento</th>
                        <th>Atividade</th>
                        <th>Data de registro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "connect.php";
                    $sql = "SELECT * FROM equipamento ORDER BY id DESC";
                    $resultado = mysqli_query($conexao, $sql);

                    while ($dados = mysqli_fetch_assoc($resultado)) {

                        echo "<tr>";
                        echo "<td><strong>{$dados['id']}</strong></td>";
                        echo "<td>{$dados['hostname']}</td>";
                        echo "<td>{$dados['ipv4']}</td>";
                        echo "<td>{$dados['mac']}</td>";
                        echo "<td>{$dados['patrimonio']}</td>";
                        echo "<td>{$dados['local']}</td>";
                        echo "<td>{$dados['sistema_operacional']}</td>";
                        echo "<td>{$dados['marca']}</td>";
                        echo "<td>{$dados['tipo_equipamento']}</td>";
                        echo "<td>{$dados['atividade']}</td>";
                        echo "<td>{$dados['data_registro']}</td>";
                        echo "<td>
                        
                      </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>