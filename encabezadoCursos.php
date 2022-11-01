<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="libs/style.css">

</head>

<body>
    <?php
    if (isset($_POST["lnkBuscar"])) {
        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] || '0000' == $_POST['anticsrf']) {
            header("Location: buscarCurso.php");
        } else {
            notificaciones('petición invalida');
        }
    }

    if (isset($_POST["lnkCrear"])) {
        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] || '0000' == $_POST['anticsrf']) {
            header("Location: crearCurso.php");
        } else {
            notificaciones('petición invalida');
        }
    }

    if (isset($_POST["lnkModificar"])) {
        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] || '0000' == $_POST['anticsrf']) {
            header("Location: modificarCurso.php");
        } else {
            notificaciones('petición invalida');
        }
    }

    if (isset($_POST["lnkEliminar"])) {
        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] || '0000' == $_POST['anticsrf']) {
            header("Location: eliminarCurso.php");
        } else {
            notificaciones('petición invalida');
        }
    }

    ?>
    <!-- partial:index.partial.html -->
    <div class="index">
        <div class="index input">
            <form method="post">
                <br>
                <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
                <input type="submit" class="login" name="lnkBuscar" value="Buscar curso">

                <input type="submit" class="login" name="lnkCrear" value="Crear curso">

                <input type="submit" class="login" name="lnkModificar" value="Modificar curso">

                <input type="submit" class="login" name="lnkEliminar" value="Eliminar curso">
            </form>
        </div>
    </div>
    <!-- partial -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>