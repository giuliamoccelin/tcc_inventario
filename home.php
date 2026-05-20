<?php
$msg = "";
if ($_POST) {
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<meta charset="utf-8">

<head>
    <title> MNET-IFFar </title>
</head>

<body>
    <link rel="stylesheet" type="text/css" href="style.css">

    <div class="MNET-logo">
        <img src="2MNET-logo.png" name="MNET Logo" width="150" height="100">
        <h1> MNET - IFFar </h1>
    </div>
    <div class="nav-links">
        <a href="perfil.php">Perfil</a>
        <a href="cadastrar_rede.php">Cadastrar Rede</a>
        <a href="cadastro.php">Cadastrar Usuário</a>

    </div>
    <hr>
    <form action="" method="post" required>


        <?php echo "<h1><b> $msg </h1></b>"; ?>

    </form>

</body>

</html>
