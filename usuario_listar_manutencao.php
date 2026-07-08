<?php
include "connect.php";
$msg = "";
session_start();

// verifica se existe uma sessão válida, senão redireciona para a página de login
if (!isset($_SESSION['email'])) {
    header('Location: index.php?msg=Acesso negado.');
    exit();
}

//  CONFIGURAÇÃO DA PAGINAÇÃO 

// quantidade máxima de linhas exibidas por página
$por_pagina = 10;

// verifica se a URL veio com o parâmetro "pagina" (ex: listar_usuarios.php?pagina=2)
if (isset($_GET['pagina']) and is_numeric($_GET['pagina'])) {
    $pagina_atual = (int) $_GET['pagina'];
} else {
    $pagina_atual = 1; // se não veio nada na URL, começa na página 1
}

// garante que a página não seja menor que 1 (evita erro com números negativos ou zero)
if ($pagina_atual < 1) {
    $pagina_atual = 1;
}

// conta quantos usuários existem no total, para saber quantas páginas serão necessárias
$sql_total = "SELECT COUNT(*) AS total FROM manutencao";
$resultado_total = mysqli_query($conexao, $sql_total);
$linha_total = mysqli_fetch_assoc($resultado_total);
$total_registros = $linha_total['total'];

// calcula quantas páginas existem no total (arredondando para cima)
if ($total_registros > 0) {
    //a função ceil() arredonda para cima, garantindo que mesmo que haja um número "sobrando" de registros, uma nova página seja criada para eles
    $total_paginas = ceil($total_registros / $por_pagina);
} else {
    $total_paginas = 1;
}

// se o usuário tentar acessar uma página maior do que existe, ajusta para a última página válida
if ($pagina_atual > $total_paginas) {
    $pagina_atual = $total_paginas;
}

// calcula a partir de qual registro a busca deve começar (o "offset" do SQL)
$inicio = ($pagina_atual - 1) * $por_pagina;
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
            <div class="fundo-opções">
                <a href="relatorio_manutencao.php" target='_blank' title="Visualizar Relatório">RELATÓRIO</a>
            </div>
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
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "connect.php";
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
                        echo "</tr>";
                    }

                    ?>
                </tbody>
            </table>
            <!-- NAVEGAÇÃO DA PAGINAÇÃO -->
            <div class="paginacao">
                <?php
                // botão "Anterior": só fica clicável se não estivermos na primeira página
                if ($pagina_atual > 1) {
                    $pagina_anterior = $pagina_atual - 1;
                    echo "<a href='listar_manutencao.php?pagina=$pagina_anterior'>&laquo; Anterior</a>";
                } else {
                    // se estivermos na primeira página, o botão "Anterior" fica desabilitado (sem link)
                    echo "<a class='desabilitado'>&laquo; Anterior</a>";
                }

                // gera um botão para cada página existente
                for ($i = 1; $i <= $total_paginas; $i++) {
                    if ($i == $pagina_atual) {
                        // página atual recebe a classe "ativo" (já destacada no CSS)
                        echo "<a href='listar_manutencao.php?pagina=$i' class='ativo'>$i</a>";
                    } else {
                        // páginas diferentes da atual ficam com link normal
                        echo "<a href='listar_manutencao.php?pagina=$i'>$i</a>";
                    }
                }

                // botão "Próxima": só fica clicável se não estivermos na última página
                if ($pagina_atual < $total_paginas) {
                    $proxima_pagina = $pagina_atual + 1;
                    // se ainda houver páginas seguintes, o botão "Próxima" recebe um link para a próxima página
                    echo "<a href='listar_manutencao.php?pagina=$proxima_pagina'>Próxima &raquo;</a>";
                } else {
                    // se estivermos na última página, o botão "Próxima" fica desabilitado (sem link)
                    echo "<a class='desabilitado'>Próxima &raquo;</a>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>