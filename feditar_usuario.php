<?php
include "connect.php";
$msg = "";
session_start();

// verifica se existe uma sessão válida, senão redireciona para a página de login
if (!isset($_SESSION['email'])) {
    header('Location: index.php?msg=Acesso negado.');
    exit();
}

// 1. Busca os dados atuais do usuário usando o email do cookie
$sql = "SELECT id, nome, cpf, email, cargo FROM usuario WHERE email = '{$_SESSION['email']}'";
$resultado = mysqli_query($conexao, $sql);

if ($resultado and mysqli_num_rows($resultado) > 0) {
    $dados = mysqli_fetch_assoc($resultado);
} else {
    // Caso o CPF do cookie não exista no banco
    setcookie("email", "", time() - 3600, "/"); // Deleta cookie inválido
    header("Location: admin.php");
    exit();
}

// 2. Processa a atualização quando o formulário é enviado
if ($_POST) {
    $novo_cpf = mysqli_real_escape_string($conexao, $_POST['cpf']);
    $novo_nome = mysqli_real_escape_string($conexao, $_POST['nome']);
    $novo_email = mysqli_real_escape_string($conexao, $_POST['email']);
    $novo_cargo = mysqli_real_escape_string($conexao, $_POST['cargo']);

    $sql_update = "UPDATE usuario SET nome = '$novo_nome', email = '$novo_email', cargo = '$novo_cargo'";
    // Se a senha não estiver vazia, adiciona ao comando de atualização
    if (!empty($nova_senha)) {
        $hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $sql_update .= ", senha = '$hash'";
    }

    $sql_update .= " WHERE email = '{$_SESSION['email']}'";

    if (mysqli_query($conexao, $sql_update)) {
        $msg = "Usuario atualizado com sucesso!";
        // Atualiza as variáveis para refletir na tela imediatamente
        $dados['nome'] = $novo_nome;
        $dados['email'] = $novo_email;
        $dados['cargo'] = $novo_cargo;
    } else {
        $msg = "Erro técnico ao atualizar banco de dados.";
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
                    <label>ID:</label>
                    <p> <input type="text" name="id" value="<?php echo $dados['id']; ?>" readonly> </p>
                </div>
                <div class="grupo">
                    <label>CPF:</label>
                    <p> <input type="text" name="cpf" value="<?php echo $dados['cpf']; ?>"> </p>
                </div>
                <div class="grupo">
                    <label>Nome Completo:</label>
                    <p><input type="text" name="nome" value="<?php echo $dados['nome']; ?>"> </p>
                </div>

                <div class="grupo">
                    Cargo: <br>
                    <p><select name="cargo" required>
                            <option value="A">Administrador</option>
                            <option value="U">Usuário Comum</option>
                        </select></p>
                </div>

                <div class="grupo"></div>
                <label>Novo E-mail de Contato:</label>
                <p><input type="email" name="email" value="<?php echo $dados['email']; ?>"> </p>

                <button type="submit" class="btn-salvar">Salvar Alterações</button><br>
        </div>
    </div>
    </form>

</body>

</html>