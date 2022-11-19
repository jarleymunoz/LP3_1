<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Buscar usuario</title>
    <link rel="stylesheet" href="libs/style.css">

</head>

<body>
    <?php
    require "encabezado.php";
    LimpiezaKV();
    $idUsuarioActual = $_SESSION['usuario']['id'];

    $_SESSION['buscar']='';
    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php");
    }
    
     //Botón para volver a menú usuarios
     if (isset($_POST['btnVolver'])) {
        header("Location: usuarios.php");
    }

    if (isset($_POST["btnBuscar"])) {
        
        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {
            //asigno a identificacion
            if (validarDocumento($_POST['txtBuscar']) == true) {            
                $_SESSION['buscar'] = Limpieza($_POST["txtBuscar"]);
                               
            } else {
                notificaciones('Texto inválido');
                $_SESSION['buscar']='';
            }
        }
    }
    $busqueda = $_SESSION['buscar'];
    if ($busqueda != null) {
        //query para extraer usuario para modificar
        $query = $conn->prepare("SELECT u.id_usuario, 
                                         u.identificacion,
                                         u.primer_apellido,
                                         u.segundo_apellido,
                                         u.nombre,
                                         u.usuario,
                                         u.email,
                                         r.nombre as rol  
          FROM usuario u
          JOIN rol r on u.rol=r.id_rol
          WHERE 
          u.id_usuario != $idUsuarioActual
          and u.rol in (3)
          and u.identificacion like  '%$busqueda%' 
          or u.primer_apellido like '%$busqueda%' 
          or u.segundo_apellido like '%$busqueda%' 
          or u.nombre like '%$busqueda%' 
          or u.usuario like '%$busqueda%' 
         ");
        $res = $query->execute([]);
        if ($res == true) {
            $usuarios = $query->fetchAll(PDO::FETCH_OBJ);
        
        } else {
            notificaciones('Búsqueda inválida');
        }
    }else{
        $usuarios=[];
    }
    anticsrf();

    ?>
    <!-- partial:index.partial.html -->
    <div class="index input">    

        <form class="login-container" method="post" enctype="multipart/form-data">
        <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
        <p><input type="text" placeholder="Buscar usuario" id="txtBuscar" name="txtBuscar" pattern="[A-Za-z0-9]+" ></p>
        <p><input type="submit" value="Buscar usuario" name="btnBuscar">
        <input type="submit" value="Volver" name="btnVolver">
     
             <table BORDER CELLPADDING=10 CELLSPACING=10 class="default">
             <CAPTION ALIGN=top>LISTA DE BUSQUEDA</CAPTION>    
                <tr ALIGN=center style="background-color: #3498DB ; color:#17202A ;">
                    <td style="text-align: center;">Identificación</td>
                    <td style="text-align: center;">Nombre</td>
                    <td style="text-align: center;">Primer apellido</td>
                    <td style="text-align: center;">Segundo apellido</td>
                    <td style="text-align: center;">Usuario</td>
                    <td style="text-align: center;">Rol</td>
                </tr>


                <?php

                foreach ($usuarios as $data) {
                ?>
                    <tr ALIGN=center style="background-color: #00695c; color:#FFFFFF;">
                        <td style="text-align: center;"><?php echo $data->identificacion; ?></td>
                        <td style="text-align: center;"><?php echo $data->nombre; ?></td>
                        <td style="text-align: center;"><?php echo $data->primer_apellido; ?></td>
                        <td style="text-align: center;"><?php echo $data->segundo_apellido; ?></td>
                        <td style="text-align: center;"><?php echo $data->usuario; ?></td>
                        <td style="text-align: center;"><?php echo $data->rol; ?></td>
                    </tr>
                    <?php
                    //fin foreach
                }
?>
            </table>
        </form>
        
    </div>
    <!-- partial -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>