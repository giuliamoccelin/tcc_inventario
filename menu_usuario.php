<?php
$sql = "SELECT nome FROM usuario where email = '$_SESSION[email]'";
$resultado = mysqli_query($conexao, $sql);
$dadosMenu = mysqli_fetch_assoc($resultado);
?>
<div class="topbar">
    <div class="MNET-logo">
        <img src="2MNET-logo.png" name="MNET Logo" width="110" height="100">
        <h1>ㅤMNET - IFFar</h1>
    </div>

    <div class="nav-links">
        <nav>
            <ul id="menu">
                <li>
                    <a href="home.php">HOME</a>
                </li>
                <li>
                    <a href="usuario_listar_maquinas.php">EQUIPAMENTOS</a>
                </li>
                <!--<li>
                    <a href="usuario_listar_usuarios.php">USUÁRIOS</a>
                </li>-->
                <li>
                    <a href="usuario_listar_manutencao.php">MANUTENÇÕES</a>
                </li>
                <li>
                    <a href="#"><img src="perfil.png" alt="Perfil" width="16" height="16">ㅤPERFIL</a>
                    <ul>
                        <li><a href="usuario_perfil.php">VER PERFIL</a></li>
                        <li><a href="logout.php">SAIR DA CONTA</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>

<hr>

<script>
    document.getElementById("menu-toggle").addEventListener("click", function() {
        const menu = document.getElementById("menu");
        menu.classList.toggle("open");
    });
</script>