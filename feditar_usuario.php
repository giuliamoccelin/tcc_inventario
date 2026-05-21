<?php
include('connect.php');

// Verifica se o cookie de identificação existe
if (!isset($_COOKIE['email'])) {
    header("Location: admin.php");
    exit();
}
$email = $_COOKIE['email'];
$msg = "";

// 1. Busca os dados atuais do usuário usando o email do cookie
$sql = "SELECT nome, cpf, email, cargo FROM usuario WHERE email = '$email'";
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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

        $sql_update .= " WHERE email = '$email'";

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
<meta charset="utf-8">

<head>
    <title> MNET-IFFar </title>
    <link rel="icon" type="image/png" href="2MNET-logo.png">
</head>

<body>
    <link rel="stylesheet" type="text/css" href="style.css">
    <div class="topbar">
        <div class="MNET-logo">
            <img src="2MNET-logo.png" name="MNET Logo" width="150" height="100">
            <h1> MNET - IFFar </h1>
        </div>
        <div class="nav-links">
            <a href="admin.php">HOME</a>
            |
            <a href="perfil.php">PERFIL</a>
            |
            <a href="cadastro_maquina.php">CADASTRARㅤMÁQUINA</a>
            |
            <a href="cadastro.php">CADASTRARㅤUSUÁRIO</a>
            |
            <a href="listar_usuarios.php">LISTARㅤUSUÁRIOS</a>
        </div>
    </div>
    <hr>
    <div class="fundo-perfil">
        <div class="card-perfil">
            <div class="titulo">
                <h2>Configurações de Perfil (Administrador)</h2>
            </div>
            <div class="info">
                <?php echo "<p><b>$msg</b></p>"; ?>
            </div>
            <form method="POST">
                <div class="grupo">
                    <label>CPF:</label>
                    <p> <input type="text" name="cpf" value="<?php echo $dados['cpf']; ?>" > </p>
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
                <p><input type="email" name="email" value="<?php echo $email; ?>"> </p>
                
                <button type="submit" class="btn-salvar">Salvar Alterações</button><br>
        </div>
    </div>
    </form>

</body>

</html>