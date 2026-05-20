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
            <a href="home.php">HOME</a>
            |
            <a href="usuario_perfil.php">PERFIL</a>
            |
            <a href="usuario_listar_usuarios.php">LISTARㅤUSUÁRIOS</a>
        </div>
    </div>
    <hr>
    <form action="" method="post" required>

        <div class="info"></div>
        <?php echo "<h1><b> $msg </h1></b>"; ?>
        </div>
    </form>

</body>

</html>