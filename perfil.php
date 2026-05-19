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
$sql = "SELECT nome, email FROM usuario WHERE cpf = '$cpf'";
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
    $confirmar_email = $_POST['email_antigo'];

    // Validação de Segurança: Só altera se o "email_antigo" digitado for igual ao salvo no banco
    if ($confirmar_email === $dados['email']) {

        $sql_update = "UPDATE usuario SET nome = '$novo_nome', email = '$novo_email'";

        // Se a senha não estiver vazia, adiciona ao comando de atualização
        if (!empty($nova_senha)) {
            // Em uma versão final, use password_hash para maior resiliência [3, 4]
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
        $msg = "Confirmação falhou: O e-mail antigo não confere";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title> MNET-IFFar </title>
</head>

<body>
    <h1> MNET -IFFar </h1>
    <hr>

    <h2>Configurações de Perfil (Administrador)</h2>

    <?php echo $msg; ?>

    <form method="POST">
        <label>Nome Completo:</label>
        <input type="text" name="nome" class="form-control" value="<?php echo $dados['nome']; ?>" required> <br> <br>

        <label>Novo E-mail de Contato:</label>
        <input type="email" name="email" class="form-control" value="<?php echo $dados['email']; ?>" required><br> <br>

        <label>Nova Senha:</label>
        <input type="password" name="senha" class="form-control" placeholder="Deixe vazio para manter"><br> <br>

        <strong>Confirmação de Segurança:</strong>
        Para autorizar as mudanças, você deve confirmar seu e-mail registrado anteriormente.<br> <br>

        <label>Digite seu E-mail Antigo:</label>
        <input type="email" name="email_antigo" class="form-control" placeholder="E-mail atual do sistema" required><br> <br>

        <hr>

        <a href="home.php">Voltar ao Painel</a><br> <br>
        <button type="submit">Salvar Alterações</button><br> <br>
    </form>

    CPF Identificado: <?php echo $cpf; ?>

</body>

</html>
