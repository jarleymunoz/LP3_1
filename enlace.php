<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ficha Curso</title>
    <link rel="stylesheet" href="libs/style.css">

</head>

<body>
    <?php
    require "encabezado.php";
    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php");
    }

    $idUsuarioActual = $_SESSION['usuario']['id'];
    
    //Botón para volver a menú usuarios
    if (isset($_POST['btnVolver'])) {
        if ($idUsuarioActual == 1) {
            header("Location: cursos.php");
        } else {
            header("Location: misCursos.php");
        }
    }

    anticsrf();
    ?>
    <!-- partial:index.partial.html -->
    <div class="login">

        <h2 class="register-header">Mensaje</h2>
        <form class="register-container" method="post" enctype="multipart/form-data">
            <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
            <label name="lblTexto_1"><?php echo $_SESSION['usuario']['nombre'];  ?>!, La página se encuentra en mantenimiento </label>
        </form>

        <form class="register-container" method="post" enctype="multipart/form-data">
            <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
            <p><input action="index_alumno.php" type="submit" value="Volver" name="btnVolver"></p>
        </form>
    </div>
    <!-- partial -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>