<?php
include "connect.php";
// Verifica se o cookie de identificação existe
if (!isset($_COOKIE['email'])) {
    header("Location: admin.php");
    exit();
}
$email = $_COOKIE['email'];
$msg = "";

if ($email) {
    $sql = "DELETE FROM usuario WHERE email = ?";

    // Prepara a instrução
    if ($stmt = mysqli_prepare($conexao, $sql)) {

        // 3. Vincula o parâmetro $email à interrogação ('s' indica que o tipo é string)
        mysqli_stmt_bind_param($stmt, "s", $email);

        // 4. Executa de fato o comando DELETE
        if (mysqli_stmt_execute($stmt)) {
            // Verifica se alguma linha foi realmente apagada
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                setcookie("email", "", time() - 3600, "/"); // Deleta cookie
                header("Location: listar_usuarios.php");
                exit();
            } else {
                $msg = "Nenhum usuário encontrado com este e-mail.";
            }
        } else {
            $msg = "Erro técnico ao excluir usuário.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $msg = "Erro interno no banco de dados.";
    }
} else {
    $msg = "E-mail não fornecido.";
}
