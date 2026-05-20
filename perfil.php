<?php
include('connect.php');

// Verifica se o cookie de identificação existe
if (!isset($_COOKIE['cpf'])) {
    header("Location: home.php");
    exit();
}

$cpf = $_COOKIE['cpf'];
$msg = "";

// 1. Busca os dados atuais do usuário usando o CPF do cookie
$sql = "SELECT nome, email, senha FROM usuario WHERE cpf = '$cpf'";
$resultado = mysqli_query($conexao, $sql);

if ($resultado and mysqli_num_rows($resultado) > 0) {
    $dados = mysqli_fetch_assoc($resultado);
} else {
    // Caso o CPF do cookie não exista no banco
    setcookie("cpf", "", time() - 3600, "/"); // Deleta cookie inválido
    header("Location: home.php");
    exit();
}

// 2. Processa a atualização quando o formulário é enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $novo_nome = mysqli_real_escape_string($conexao, $_POST['nome']);
    $novo_email = mysqli_real_escape_string($conexao, $_POST['email']);
    $nova_senha = $_POST['senha'];
    $confirmar_senha = $_POST['senha_antiga'];

    // Validação de Segurança: Só altera se o "senha_antiga" digitada for igual à salva no banco
    if (password_verify($confirmar_senha, $dados['senha'])) {

        $sql_update = "UPDATE usuario SET nome = '$novo_nome', email = '$novo_email'";
        // Se a senha não estiver vazia, adiciona ao comando de atualização
        if (!empty($nova_senha)) {
            $hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $sql_update .= ", senha = '$hash'";
        }

        $sql_update .= " WHERE cpf = '$cpf'";

        if (mysqli_query($conexao, $sql_update)) {
            $msg = "Perfil atualizado com sucesso!";
            // Atualiza as variáveis para refletir na tela imediatamente
            $dados['nome'] = $novo_nome;
            $dados['email'] = $novo_email;
        } else {
            $msg = "Erro técnico ao atualizar banco de dados.";
        }
    } else {
        $msg = "Confirmação falhou: A senha antiga não confere";
    }
}

//excluir conta permanentemente
if (isset($_POST['excluir'])) {
    $confirmar_senha = $_POST['senha_antiga'];

    if (password_verify($confirmar_senha, $dados['senha'])) {
        $sql_delete = "DELETE FROM usuario WHERE cpf = '$cpf'";
        if (mysqli_query($conexao, $sql_delete)) {
            setcookie("cpf", "", time() - 3600, "/"); // Deleta cookie
            header("Location: index.php");
            exit();
        } else {
            $msg = "Erro técnico ao excluir conta.";
        }
    } else {
        $msg = "Confirmação falhou: A senha antiga não confere";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title> MNET-IFFar </title>
</head>

<body>
    <link rel="stylesheet" type="text/css" href="style.css">

    <div class="MNET-logo">
        <img src="2MNET-logo.png" name="MNET Logo" width="150" height="100">
        <h1> MNET - IFFar </h1>
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
                    <p> <input type="text" name="cpf" value="<?php echo $cpf; ?>" readonly> </p>
                </div>
                <div class="grupo">
                    <label>Nome Completo:</label>
                    <p><input type="text" name="nome" value="<?php echo $dados['nome']; ?>"> </p>
                </div>
                <div class="grupo">
                    <label>Novo E-mail de Contato:</label>
                    <p><input type="email" name="email" value="<?php echo $dados['email']; ?>"> </p>
                </div>
                <div class="grupo">
                    <label>Senha Atual:</label>
                    <p><input type="password" name="senha_atual" placeholder="Digite sua senha atual para confirmar" required> </p>
                </div>
                <div class="grupo">
                    <label>Nova Senha:</label>
                    <p><input type="password" name="senha" placeholder="Deixe vazio para manter"> </p>
                </div>

                <strong>Confirmação de Segurança:</strong>
                Para autorizar as mudanças, você deve confirmar sua senha registrada anteriormente.

                <label>Digite sua Senha Antiga:</label>
                <p><input type="password" name="senha_antiga" placeholder="Senha atual do sistema" required></p>
                <button type="submit" class="btn-salvar">Salvar Alterações</button><br>
                <button type="submit" class="btn-excluir" name="excluir">Excluir Conta Permanentemente</button>
                <a href="home.php" class="voltar">← Voltar para Home</a>
            </form>
        </div>

</body>

</html>
