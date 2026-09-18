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

// Consultas para contar o número de administradores e usuários comuns
$sql_adm = "SELECT COUNT(*) as total_adm FROM usuario WHERE cargo = 'A'";
$resultado_adm = mysqli_query($conexao, $sql_adm);
if ($resultado_adm) {
    $row = mysqli_fetch_assoc($resultado_adm);
    $total_adm = (int)$row['total_adm'];
}

$sql_uc = "SELECT COUNT(*) as total_uc FROM usuario WHERE cargo = 'U'";
$resultado_uc = mysqli_query($conexao, $sql_uc);
if ($resultado_uc) {
    $row = mysqli_fetch_assoc($resultado_uc);
    $total_uc = (int)$row['total_uc'];
}

// Consulta para contar o número total equipamentos em uso
$sql_uso = "SELECT COUNT(*) as total_uso FROM equipamento where atividade = 'Em uso'";
$resultado_uso = mysqli_query($conexao, $sql_uso);
if ($resultado_uso) {
    $row = mysqli_fetch_assoc($resultado_uso);
    $total_uso = (int)$row['total_uso'];
}

// Consulta para contar o número total equipamentos disponiveis
$sql_disp = "SELECT COUNT(*) as total_disponivel FROM equipamento where atividade = 'Disponível'";
$resultado_disponivel = mysqli_query($conexao, $sql_disp);
if ($resultado_disponivel) {
    $row = mysqli_fetch_assoc($resultado_disponivel);
    $total_disponivel = (int)$row['total_disponivel'];
}

// Consulta para contar o número total equipamentos em descarte
$sql_desc = "SELECT COUNT(*) as total_descarte FROM equipamento where atividade = 'Descarte'";
$resultado_descarte = mysqli_query($conexao, $sql_desc);
if ($resultado_descarte) {
    $row = mysqli_fetch_assoc($resultado_descarte);
    $total_descarte = (int)$row['total_descarte'];
}

// Consulta para contar o número total equipamentos em manutenção
$sql_manut = "SELECT COUNT(*) as total_manutencao FROM equipamento where atividade = 'Manutenção'";
$resultado_manutencao = mysqli_query($conexao, $sql_manut);
if ($resultado_manutencao) {
    $row = mysqli_fetch_assoc($resultado_manutencao);
    $total_manutencao = (int)$row['total_manutencao'];
}

// Cálculos baseados no "dashboard.png"
$total_equipamentos = $total_uso + $total_disponivel + $total_manutencao + $total_descarte;
$total_ativos = $total_uso + $total_disponivel; // Considerados "Ativos" pela imagem
$total_usuarios = $total_adm + $total_uc;

// (Simulação) Consulta para Equipamentos por Tipo (Gráfico de Barras)
// Requer que exista a coluna tipo_equipamento na tabela 'equipamento'
$tipos_labels = "['Computador', 'Servidor', 'Switch', 'Impressora', 'Access Point']";
$tipos_dados = "[3, 2, 1, 1, 1]"; // Substitua por loop em fetch_assoc para dados reais do BD
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include "head.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-container { padding: 20px; background-color: #f8f9fc; }
        .row-cards { display: flex; gap: 20px; margin-bottom: 20px; }
        .card-kpi { 
            flex: 1; background: #fff; padding: 20px; border-radius: 10px; 
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            display: flex; justify-content: space-between; align-items: center;
        }
        .card-kpi h4 { margin: 0; font-size: 14px; color: #858796; text-transform: uppercase; font-weight: bold; }
        .card-kpi .valor { font-size: 32px; font-weight: bold; margin-top: 5px; }
        
        /* Cores conforme imagem */
        .color-equip { color: #4e73df; }
        .color-ativos { color: #1cc88a; }
        .color-manut { color: #f6c23e; }
        .color-users { color: #8a2be2; }
        
        .row-charts { display: flex; gap: 20px; }
        .card-chart { 
            background: #fff; border-radius: 10px; padding: 20px; 
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        .chart-rosca { flex: 1; }
        .chart-barra { flex: 2; }
    </style>
</head>
<body>
    <?php include "menu.php"; ?>
    
    <div class="dashboard-container">
        <h2><?php echo "Bem-vindo, " . $dadosMenu['nome'] . "!"; ?></h2>
        
        <!-- Cards Superiores -->
        <div class="row-cards">
            <div class="card-kpi">
                <div>
                    <h4>Equipamentos</h4>
                    <div class="valor color-equip"><?php echo $total_equipamentos; ?></div>
                </div>
                <!-- Insira ícone svg de monitor aqui -->
            </div>
            <div class="card-kpi">
                <div>
                    <h4>Ativos</h4>
                    <div class="valor color-ativos"><?php echo $total_ativos; ?></div>
                </div>
                <!-- Insira ícone svg de check aqui -->
            </div>
            <div class="card-kpi">
                <div>
                    <h4>Em Manutenção</h4>
                    <div class="valor color-manut"><?php echo $total_manutencao; ?></div>
                </div>
                <!-- Insira ícone svg de ferramenta aqui -->
            </div>
            <div class="card-kpi">
                <div>
                    <h4>Usuários</h4>
                    <div class="valor color-users"><?php echo $total_usuarios; ?></div>
                </div>
                <!-- Insira ícone svg de usuários aqui -->
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row-charts">
            <!-- Gráfico de Status (Rosca) -->
            <div class="card-chart chart-rosca">
                <h4 style="color: #4e73df; font-weight: bold;">Status dos Equipamentos</h4>
                <p style="color: #858796; font-size: 14px;">Distribuição por atividade</p>
                <div style="position: relative; height: 250px;">
                    <canvas id="graficoEquipamentos"></canvas>
                </div>
            </div>

            <!-- Gráfico por Tipo (Barras) -->
            <div class="card-chart chart-barra">
                <h4 style="color: #4e73df; font-weight: bold;">Equipamentos por Tipo</h4>
                <p style="color: #858796; font-size: 14px;">Quantidade de dispositivos cadastrados</p>
                <div style="position: relative; height: 250px;">
                    <canvas id="graficoTipos"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 1. Gráfico de Status dos Equipamentos (Rosca)
        const ctxEquipamentos = document.getElementById('graficoEquipamentos').getContext('2d');
        const graficoEquipamentos = new Chart(ctxEquipamentos, {
            type: 'doughnut',
            data: {
                labels: ['Ativos', 'Em Manutenção'],
                datasets: [{
                    data: [
                        <?php echo $total_ativos; ?>, 
                        <?php echo $total_manutencao; ?>
                    ],
                    backgroundColor: ['#1cc88a', '#e74a3b'],
                    hoverBackgroundColor: ['#17a673', '#be2617'],
                    borderWidth: 2,
                    cutout: '70%' // Ajuste para ficar com visual fino conforme imagem
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: { usePointStyle: true, boxWidth: 8 }
                    }
                }
            }
        });

        // 2. Gráfico de Equipamentos por Tipo (Barra)
        const ctxTipos = document.getElementById('graficoTipos').getContext('2d');
        const graficoTipos = new Chart(ctxTipos, {
            type: 'bar',
            data: {
                labels: <?php echo $tipos_labels; ?>,
                datasets: [{
                    label: 'Quantidade',
                    data: <?php echo $tipos_dados; ?>,
                    backgroundColor: '#2e59d9',
                    borderRadius: 5, // Canto arredondado nas barras
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</body>
</html>