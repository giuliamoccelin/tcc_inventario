<?php
include('connect.php');
session_start();
// Verifica se a sessão de identificação existe
if (!isset($_SESSION['email'])) {
    header("Location: index.php?msg=Acesso negado.");
    exit();
}
$email = $_SESSION['email'];
$msg = "";

// 1. Busca os dados atuais do usuário usando o email da sessão
$sql = "SELECT * FROM usuario WHERE email = '$email'";
$resultado = mysqli_query($conexao, $sql);
$dados = mysqli_fetch_assoc($resultado);


// 2. Processa a atualização quando o formulário é enviado
if (isset($_POST['atualizar'])) {
    $at_cpf = mysqli_real_escape_string($conexao, $_POST['cpf']);
    $novo_nome = mysqli_real_escape_string($conexao, $_POST['nome']);
    $novo_email = mysqli_real_escape_string($conexao, $_POST['email']);
    $nova_senha = $_POST['senha'];
    $confirmar_senha = $_POST['senha_antiga'];

    // Validação de Segurança: Só altera se o "senha_antiga" digitada for igual à salva no banco
    if (password_verify($confirmar_senha, $dados['senha'])) {

        $sql_update = "UPDATE usuario SET cpf = '$at_cpf', nome = '$novo_nome', email = '$novo_email'";
        // Se a senha não estiver vazia, adiciona ao comando de atualização
        if (!empty($nova_senha)) {
            $hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $sql_update .= ", senha = '$hash'";
        }

        $sql_update .= " WHERE email = '$email'";

        if (mysqli_query($conexao, $sql_update)) {
            $msg = "Perfil atualizado com sucesso!";
            // Atualiza as variáveis para refletir na tela imediatamente
            $dados['cpf'] = $at_cpf;
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
        $sql_delete = "DELETE FROM usuario WHERE email = '$email'";
        if (mysqli_query($conexao, $sql_delete)) {
            session_destroy();
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
    <?php include "head.php"; // Inclui o head de navegação 
    ?>
</head>

<body>
    <?php include "menu_usuario.php"; // Inclui o menu de navegação 
    ?>
    <div class="fundo-perfil">
        <div class="card-perfil">
            <div class="titulo">
                <h2>Configurações de Perfil (Usuário Comum)</h2>
            </div>
            <div class="info">
                <?php echo "<p><b>$msg</b></p>"; ?>
            </div>
            <form method="POST">
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
                    <p><input type="text" name="cargo" value="<?php if ($dados['cargo'] == 'A') {
                                                                    echo 'Administrador';
                                                                } else {
                                                                    echo 'Usuário Comum';
                                                                } ?>" readonly> </p>
                </div>
                <div class="grupo">
                    <label>E-mail de Contato:</label>
                    <p><input type="email" name="email" value="<?php echo $email; ?>"> </p>
                </div>
                <strong>Confirmação de Segurança:</strong>
                <div class="info">
                    <p><b>Para autorizar as mudanças, você deve confirmar sua senha registrada anteriormente.</b></p>
                </div>
                <div class="grupo">
                    <label>Senha Atual:</label>
                    <p><input type="password" name="senha_atual" placeholder="Digite sua senha atual para confirmar" required> </p>
                </div>
                <div class="grupo">
                    <label>Nova Senha:</label>
                    <p><input type="password" name="senha" placeholder="Deixe vazio para manter"> </p>
                </div>
                <button type="submit" class="btn-salvar" name="atualizar">Salvar Alterações</button><br>
                <button type="submit" class="btn-excluir" name="excluir">Excluir Conta Permanentemente</button>
            </form>
        </div>

</body>

</html>