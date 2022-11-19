<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
    <link rel="stylesheet" href="libs/style.css">

</head>

<body>
    <?php
    require "encabezado.php";
    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php");
    }

    $idUsuarioActual = $_SESSION['usuario']['id'];
    $rolActual = $_SESSION['usuario']['rol'];

    $query = $conn->prepare("SELECT primer_apellido,
                                    segundo_apellido,
                                    nombre                                    
                                    FROM usuario WHERE id_usuario=:id_usuario 
                                                 AND rol != 1");
    $res = $query->execute([
        'id_usuario' => $idUsuarioActual
    ]);
    if ($res == true) {
        $usuario = $query->fetchAll(PDO::FETCH_OBJ);
    }

    if (isset($_POST['btnVolver'])) {

        if ($rolActual == 'Docente') {
            header("Location: inicio_docente.php");
        } else {
            header("Location: inicio_alumno.php");
        }
    }
    if (isset($_POST['btnCambio'])) {
        header("Location: cambioClave.php");
    }


    if (isset($_POST["btnActualizar"])) {

        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {

            //asigno a primer apellido
            if (validarTexto($_POST['txtPrimerApellido']) == true) {
                $primerApellido = Limpieza($_POST["txtPrimerApellido"]);
            } else {
                notificaciones('Primer apellido inválido');
                $primerApellido = "";
            }
            //asigno a segundo apellido
            if (validarTexto($_POST['txtSegundoApellido']) == true) {
                $segundoApellido = Limpieza($_POST["txtSegundoApellido"]);
            } else {
                notificaciones('Segundo apellido inválido');
                $segundoApellido = "";
            }
            //asigno a nombre
            if (validarTexto($_POST['txtNombre']) == true) {
                $nombre = Limpieza($_POST["txtNombre"]);
            } else {
                notificaciones('Nombre inválido');
                $nombre = "";
            }

            if ($primerApellido != "" && $segundoApellido != "" && $nombre != "") {
                $query1 = $conn->prepare("UPDATE  usuario SET 
                                              primer_apellido=:primerApellido, 
                                              segundo_apellido=:segundoApellido, 
                                              nombre=:nombre
                                              WHERE id_usuario=:id_usuario");
                $res1 = $query1->execute([
                    'primerApellido' => $primerApellido,
                    'segundoApellido' => $segundoApellido,
                    'nombre' => $nombre,
                    'id_usuario' => $idUsuarioActual
                ]);
                if ($res1 == true) {

                    notificaciones('Datos actualizados');
                    header("refresh:2;url=inicio_alumno.php");
                }
            } else {
                notificaciones('Datos faltantes');
                header("refresh:2;url=perfil.php");
            }
        } else {
            notificaciones('Petición inválida');
            header("refresh:2;url=perfil.php");
        }
    }

    anticsrf();

    foreach ($usuario as $data) {
    ?>

        <!-- partial:index.partial.html -->
        <div class="login">

            <h2 class="register-header">Perfil</h2>

            <form class="register-container" method="post" enctype="multipart/form-data">
                <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
                <p><input type="text" placeholder="Primer apellido" id="txtPrimerApellido" name="txtPrimerApellido" value="<?php echo $data->primer_apellido ?>" pattern="[A-Za-z]+" required="required"></p>
                <p><input type="text" placeholder="Segundo apellido" id="txtSegundoApellido" name="txtSegundoApellido" value="<?php echo $data->segundo_apellido ?>" pattern="[A-Za-z]+" required="required"></p>
                <p><input type="text" placeholder="Nombre" id="txtNombre" name="txtNombre" value="<?php echo $data->nombre ?>" pattern="[A-Za-z]+" required="required"></p>
                <p><input type="submit" value="Actualizar datos" name="btnActualizar"></p>
            </form>
            <form class="register-container" method="post" enctype="multipart/form-data">
                <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
                <p><input action="cambioClave.php" type="submit" value="Cambio clave" name="btnCambio"></p>
                <p><input action="inicio_alumno.php" type="submit" value="Volver" name="btnVolver"></p>
            </form>
        </div>
        <!-- partial -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>
<?php
    }
?>