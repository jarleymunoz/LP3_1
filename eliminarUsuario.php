<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Eliminar usuario</title>
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
            } else {
                notificaciones('Identificación inválida');
                $identificacion = "";
            }
            //query para extraer usuario para modificar
            $query = $conn->prepare("SELECT id_usuario, 
                                    identificacion,
                                    concat(nombre,' ',primer_apellido,' ',segundo_apellido) as nombre
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
    
    
    if (isset($_POST["btnEliminar"])) {
        $idUsuario=$_SESSION['usuario']['id_modi'];
        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {
            
            $query1 = $conn->prepare("DELETE 
                                      FROM usuario
                                      WHERE id_usuario=:id_usuario");
                $res1 = $query1->execute([
                    'id_usuario' => $idUsuario
                ]);
                if ($res1 == true) {

                    notificaciones('Usuario eliminado ');
                    header("refresh:2;url=eliminarUsuario.php");
                }
             else {
                notificaciones('Usuario no eliminado');
                header("refresh:2;url=eliminarUsuario.php");
            }
        } else {
            notificaciones('Petición inválida');
            header("refresh:2;url=eliminarUsuario.php");
        }
    }

    anticsrf();

    
    ?>
    <!-- partial:index.partial.html -->
    <div class="login">

        <h2 class="register-header">Eliminar usuario</h2>
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
                <p><label> Confirmar para eliminar a: </label>
                <p><label> <?php echo $data->nombre ?> </label>
                <p><input type="submit" value="Eliminar usuario" name="btnEliminar"></p>
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
