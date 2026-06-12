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
    <title> MNET-IFFar </title>
    <link rel="icon" type="image/png" href="2MNET-logo.png">
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="utf-8">
</head>

<body>
    <?php include "menu.php"; // Inclui o menu de navegação 
    ?>
    <div class="fundo-tabela">
        <div class="card-tabela">
            <table>
                <thead>
                    <tr>
                        <th>Técnico</th>
                        <th>Hostname</th>
                        <th>MAC</th>
                        <th>Tipo</th>
                        <th>Descrição</th>
                        <th>Custo</th>
                        <th>Data de registro</th>
                        <th>Relatório</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "connect.php";
                    // SUBSTITUA as 4 queries problemáticas por essa só:
                    $sql = "SELECT manutencao.*, 
               equipamento.hostname, 
               equipamento.mac, 
               usuario.nome 
        FROM manutencao 
        JOIN equipamento ON manutencao.id_equipamento = equipamento.id 
        JOIN usuario ON manutencao.id_usuario = usuario.id 
        ORDER BY manutencao.id DESC";

                    $resultado = mysqli_query($conexao, $sql);

                    while ($dados = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>{$dados['nome']}</td>";        // vem do JOIN com usuario
                        echo "<td>{$dados['hostname']}</td>";    // vem do JOIN com equipamento
                        echo "<td>{$dados['mac']}</td>";        // vem do JOIN com equipamento
                        echo "<td>{$dados['tipo_manutencao']}</td>";
                        echo "<td>{$dados['descricao']}</td>";
                        echo "<td>R$ " . number_format($dados['custo'], 2, ',', '.') . "</td>";
                        echo "<td>{$dados['data_registro']}</td>";
                        echo "<td>
                        <div class='relatorio'>
                        <a href='relatorio_manutencao.php?id={$dados['id']}'custo={$dados['custo']}' target='_blank' title='Visualizar Relatório'>Relatório</a>
                        </div>
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