<?php
ob_start();
include "connect.php";
$msg = "";
session_start();

// verifica se existe uma sessão válida, senão redireciona para a página de login
if (!isset($_SESSION['email'])) {
    header('Location: index.php?msg=Acesso negado.');
    exit();
}

// 1. Busca os dados atuais do usuário usando o id do GET
$sql = "SELECT * FROM usuario WHERE id = {$_GET['id']}";
$resultado = mysqli_query($conexao, $sql);

$dados = mysqli_fetch_assoc($resultado);

// 2. Processa a atualização quando o formulário é enviado
if ($_POST) {
    $novo_cpf = $_POST['cpf'];
    $novo_nome = $_POST['nome'];
    $novo_email = $_POST['email'];
    $novo_cargo = $_POST['cargo'];
    $nova_senha = $_POST['senha'];
    $sql_update = "UPDATE usuario SET nome = '$novo_nome', email = '$novo_email', cargo = '$novo_cargo'";

    /* Formata um CPF para o padrão 000.000.000-00
     * Aceita entrada com ou sem máscara*/
    function formatarCPF($novo_cpf)
    {
        // Verifica se tem 11 dígitos
        if (strlen($novo_cpf) == 11) {
            // Retorna no formato padrão
            return substr($novo_cpf, 0, 3) . '.' .
                substr($novo_cpf, 3, 3) . '.' .
                substr($novo_cpf, 6, 3) . '-' .
                substr($novo_cpf, 9, 2);
        } else if (strlen($novo_cpf) == 14) {
            return $novo_cpf;
        }
    }
    $cpfFormatado = formatarCPF($novo_cpf);
    $sql_update .= ", cpf = '$cpfFormatado'";


    // Se a senha não estiver vazia, adiciona ao comando de atualização
    if (!empty($nova_senha)) {
        $hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $sql_update .= ", senha = '$hash'";
    }
    $sql_update .= " WHERE id = " . (int)$_GET['id'];
/*
    if (mysqli_query($conexao, $sql_update)) {
        $msg = "Usuario atualizado com sucesso!";
        // Atualiza as variáveis para refletir na tela imediatamente
        $dados['cpf'] = $cpfFormatado;
        $dados['nome'] = $novo_nome;
        $dados['email'] = $novo_email;
        $dados['cargo'] = $novo_cargo;
        $dados['senha'] = $nova_senha;
    } else {
        $msg = "Erro técnico ao atualizar banco de dados.";
    }
        */

    if (mysqli_query($conexao, $sql_update)) {
        echo "<script>
            alert('Usuário atualizado com sucesso!');
            window.location.href = 'listar_usuarios.php';
        </script>";
        exit();
    } else {
        $msg = "Erro técnico: " . mysqli_error($conexao);
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
    <div class="fundo-perfil">
        <div class="card-perfil">
            <div class="titulo">
                <h2>Configurações de Perfil - <?php echo $dados['nome']; ?></h2>
            </div>
            <div class="info">
                <?php echo "<p><b>$msg</b></p>"; ?>
            </div>
            <form method="POST">
                <div class="grupo">
                    <label>Nome Completo:</label>
                    <p><input type="text" name="nome" value="<?php echo $dados['nome']; ?>"> </p>
                </div>

                <div class="grupo">
                    <label>CPF:</label>
                    <p> <input type="text" name="cpf" value="<?php echo $dados['cpf']; ?>"> </p>
                </div>
                <div class="grupo">
                    <label>Novo E-mail de Contato:</label>
                    <p><input type="email" name="email" value="<?php echo $dados['email']; ?>"> </p>
                </div>
                <div class="grupo">
                    Cargo: <br>
                    <p><select name="cargo" required>
                            <option value="A">Administrador</option>
                            <option value="U">Usuário Comum</option>
                        </select></p>
                </div>
                <div class="grupo">
                    Nova senha: <br>
                    <p><input name="senha" ></p>
                </div>
                <button  class="btn-salvar">Salvar Alterações</button><br><br>
                <a id="voltar" href="listar_usuarios.php" name="Voltar a listar usuários">← Voltar para Usuários</a>
            </form>
        </div>
    </div>


</body>

</html>