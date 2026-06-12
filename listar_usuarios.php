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
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>E-mail</th>
                        <th>Cargo</th>
                        <th>Data de Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "connect.php";
                    $sql = "SELECT * FROM usuario";
                    $resultado = mysqli_query($conexao, $sql);

                    while ($dados = mysqli_fetch_assoc($resultado)) {

                        echo "<tr>";
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
                        echo "<td>
                        <div class='opcoes'>
                            <a href='feditar_usuario.php?id={$dados['id']}' title='Editar'><img src='editar.png' width='18'></a>
                            <a href='deletar_usuario.php?id={$dados['id']}' onclick='return confirm(\"Deseja excluir este usuário?\")' title='Excluir'><img src='deletar.png' width='18'></a>
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