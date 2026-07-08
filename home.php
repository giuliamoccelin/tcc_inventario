<?php
include "connect.php";
$msg = "";
session_start();

// verifica se existe uma sessão válida, senão redireciona para a página de login
if (!isset($_SESSION['email'])) {
    header('Location: index.php?msg=Acesso negado.');
    exit();
}
// Consulta para obter o nome do usuário logado
$sql = "SELECT nome FROM usuario where email = '$_SESSION[email]'";
$resultado = mysqli_query($conexao, $sql);
$dadosMenu = mysqli_fetch_assoc($resultado);

// Inicializa as variáveis para garantir que o gráfico não quebre se o banco estiver vazio
$total_adm = 0;
$total_uc = 0;
$total_uso = 0;
$total_disponivel = 0;
$total_descarte = 0;
$total_manutencao = 0;

$sql = "SELECT COUNT(*) as total_adm FROM usuario WHERE cargo = 'A'";
$resultado_adm = mysqli_query($conexao, $sql);
if ($resultado_adm) {
    $row = mysqli_fetch_assoc($resultado_adm);
    $total_adm = (int)$row['total_adm'];
}

// Consulta para contar o número de usuários comuns
$sql = "SELECT COUNT(*) as total_uc FROM usuario WHERE cargo = 'U'";
$resultado_uc = mysqli_query($conexao, $sql);
if ($resultado_uc) {
    $row = mysqli_fetch_assoc($resultado_uc);
    $total_uc = (int)$row['total_uc'];
}

// Consulta para contar o número total equipamentos em uso
$sql = "SELECT COUNT(*) as total_uso FROM equipamento where atividade = 'Em uso'";
$resultado_uso = mysqli_query($conexao, $sql);
if ($resultado_uso) {
    $row = mysqli_fetch_assoc($resultado_uso);
    $total_uso = (int)$row['total_uso'];
}

// Consulta para contar o número total equipamentos disponiveis
$sql = "SELECT COUNT(*) as total_disponivel FROM equipamento where atividade = 'Disponível'";
$resultado_disponivel = mysqli_query($conexao, $sql);
if ($resultado_disponivel) {
    $row = mysqli_fetch_assoc($resultado_disponivel);
    $total_disponivel = (int)$row['total_disponivel'];
}

// Consulta para contar o número total equipamentos em descarte
$sql = "SELECT COUNT(*) as total_descarte FROM equipamento where atividade = 'Descarte'";
$resultado_descarte = mysqli_query($conexao, $sql);
if ($resultado_descarte) {
    $row = mysqli_fetch_assoc($resultado_descarte);
    $total_descarte = (int)$row['total_descarte'];
}

// Consulta para contar o número total equipamentos em manutenção
$sql = "SELECT COUNT(*) as total_manutencao FROM equipamento where atividade = 'Manutenção'";
$resultado_manutencao = mysqli_query($conexao, $sql);
if ($resultado_manutencao) {
    $row = mysqli_fetch_assoc($resultado_manutencao);
    $total_manutencao = (int)$row['total_manutencao'];
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
        
    <form action="" method="post" required>
        <div class="fundo-tabela">
            <div class="card-tabela">
                <div class="titulo">
                    <h2><?php echo "Bem-vindo, " . $dadosMenu['nome'] . "!"; ?></h2>
                </div>
                
                <div class="container-graficos">
                    <div class="card-grafico">
                        <canvas id="graficoEquipamentos"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <script>

        // 1. Gráfico de Equipamentos
        const ctxEquipamentos = document.getElementById('graficoEquipamentos').getContext('2d');
        const graficoEquipamentos = new Chart(ctxEquipamentos, {
            type: 'doughnut',
            data: {
                labels: ['Em uso', 'Disponível', 'Descarte', 'Manutenção'],
                datasets: [{
                    data: [
                        <?php echo $total_uso; ?>, 
                        <?php echo $total_disponivel; ?>, 
                        <?php echo $total_descarte; ?>, 
                        <?php echo $total_manutencao; ?>
                    ],
                    backgroundColor: ['#f6c23e', '#1cc88a', '#e74a3b', '#36b9cc'],
                    hoverBackgroundColor: ['#dfa515', '#17a673', '#be2617', '#258391'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    title: {
                        display: true,
                        text: 'Situação dos Equipamentos', // Modifique aqui o Título Geral do gráfico
                        font: { size: 16 }
                    }
                }
            }
        });
    </script>
</body>
</html>