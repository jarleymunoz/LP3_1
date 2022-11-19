<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Modificar usuario</title>
    <link rel="stylesheet" href="libs/style.css">

</head>

<body>
    <?php
    require "encabezado.php";
    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php");
    }

    $idUsuarioActual = $_SESSION['usuario']['id'];
    $usuarios =null;
    $idUsuario=null;
    //Botón para volver a menú usuarios
    if (isset($_POST['btnVolver'])) {
        header("Location: usuarios.php");
    }
    //Botón  para buscar el usuario a modificar
    if (isset($_POST["btnBuscar"])) {
        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {

            //asigno a identificacion
            if (validarDocumento($_POST['txtIdentificacion1']) == true) {
                $identificacion = Limpieza($_POST["txtIdentificacion1"]);
                echo($_POST["txtIdentificacion1"]);
            } else {
                notificaciones('Identificación inválida');
                $identificacion = "";
            }
            //query para extraer usuario para modificar
            $query = $conn->prepare("SELECT id_usuario, 
                                    identificacion,
                                    primer_apellido,
                                    segundo_apellido,
                                    nombre,
                                    rol,
                                    email   
                                     FROM usuario
                                     WHERE id_usuario!=$idUsuarioActual
                                     and identificacion=:identificacion
                                    ");
            $res = $query->execute([
                'identificacion' => $identificacion
            ]);
            if ($res == true) {
                $usuarios = $query->fetchAll(PDO::FETCH_OBJ);
                
                
            }else{
                notificaciones('Identificación inválida');
            }
        }
    }
    
    
    if (isset($_POST["btnActualizar"])) {
        $idUsuario=$_SESSION['usuario']['id_modi'];
        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {
            
            //asigno a identificacion
            if (validarDocumento($_POST['txtIdentificacion']) == true) {
                $identificacion1 = Limpieza($_POST["txtIdentificacion"]);
            } else {
                notificaciones('Identificación inválida');
                $identificacion1 = "";
            }
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
            //asigno correo
            if (validarCorreo($_POST['txtCorreo']) == true) {
                $correo = Limpieza($_POST["txtCorreo"]);
            } else {
                notificaciones('Correo inválido');
                $correo = "";
            }
            //asigno a rol
            $roles = [1, 2, 3];
            if (in_array($_POST['txtRol'], $roles)) {
                $rol = Limpieza($_POST["txtRol"]);
            } else {
                notificaciones('Rol inválido');
                $rol = "";
            }
        
            if ($identificacion1 != "" && $primerApellido != "" && $segundoApellido != "" && $nombre != "" && $rol != ""&& $correo != "") {
                $query1 = $conn->prepare("UPDATE  usuario SET 
                                              identificacion=:identificacion, 
                                              primer_apellido=:primerApellido, 
                                              segundo_apellido=:segundoApellido, 
                                              nombre=:nombre, 
                                              email=:correo
                                              rol=:rol                                  
                                              WHERE id_usuario=:id_usuario");
                $res1 = $query1->execute([
                    'identificacion' => $identificacion1,
                    'primerApellido' => $primerApellido,
                    'segundoApellido' => $segundoApellido,
                    'nombre' => $nombre,
                    'correo' => $correo,
                    'rol' => $rol,
                    'id_usuario' => $idUsuario
                ]);
                if ($res1 == true) {

                    notificaciones('Datos actualizados');
                    header("refresh:2;url=modificarUsuario.php");
                }
            } else {
                notificaciones('Datos faltantes');
                header("refresh:2;url=modificarUsuario.php");
            }
        } else {
            notificaciones('Petición inválida');
            header("refresh:2;url=modificarUsuario.php");
        }
    }

    anticsrf();

    
    ?>
    <!-- partial:index.partial.html -->
    <div class="login">

        <h2 class="register-header">Modificar usuario</h2>
        <form class="register-container" method="post" enctype="multipart/form-data">
                <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
                <p><input type="text" placeholder="Identificación" id="txtIdentificacion1" name="txtIdentificacion1"  pattern="[A-Za-z0-9]+" required="required"></p>
                <p><input type="submit" value="Buscar usuario" name="btnBuscar"></p>
        </form> 
        
            
        <?php if ($usuarios!=null) {
            foreach ($usuarios as $data) {  
                $_SESSION['usuario']['id_modi']=$data->id_usuario;
                
                ?>
            
            <form class="register-container" method="post" enctype="multipart/form-data">
                <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
                <p><input type="text" placeholder="Identificación" id="txtIdentificacion" name="txtIdentificacion" value="<?php echo $data->identificacion ?>" pattern="[A-Za-z0-9]+" required="required"></p>
                <p><input type="text" placeholder="Primer apellido" id="txtPrimerApellido" name="txtPrimerApellido" value="<?php echo $data->primer_apellido ?>" pattern="[A-Za-z]+" required="required"></p>
                <p><input type="text" placeholder="Segundo apellido" id="txtSegundoApellido" name="txtSegundoApellido" value="<?php echo $data->segundo_apellido ?>" pattern="[A-Za-z]+" required="required"></p>
                <p><input type="text" placeholder="Nombre" id="txtNombre" name="txtNombre" value="<?php echo $data->nombre ?>" pattern="[A-Za-z]+" required="required"></p>
                <p>Rol
                    <select id="txtRol" name="txtRol" value="<?php echo $data->rol ?>" required="required">
                        <option value=0></option>    
                        <option value=1>Administrador</option>
                        <option value=2>Docente</option>
                        <option value=3>Alumno</option>
                    </select>
                </p>
                <p><input type="email" placeholder="Correo" id="txtCorreo" name="txtCorreo"value="<?php echo $data->email ?>" required="required"></p>

                <p><input type="submit" value="Actualizar usuario" name="btnActualizar"></p>
            </form>
            <?php
            }   
    }
?>
            <form class="register-container" method="post" enctype="multipart/form-data">
                <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
                <p><input action="usuarios.php" type="submit" value="Volver" name="btnVolver"></p>
            </form>
        </div>
        <!-- partial -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>
