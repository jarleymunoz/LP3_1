<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Buscar usuario</title>
  <link rel="stylesheet" href="libs/style.css">

</head>
<body>
<?php
require "encabezado.php";
//limpieza de llave valor del $_POST
LimpiezaKV();

if(!isset($_SESSION['usuario']))
{
   header("Location: index.php");
}
if (isset($_POST["btnBuscar"])) {
    if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {

        //asigno a identificacion
        if (validarUsuario($_POST['txtBuscar']) == true) {
            $buscar = " '% ".Limpieza($_POST["txtBuscar"])." %' ";
        } else {
            notificaciones('Texto inválido');
            $buscar = "";
        }
    var_dump($buscar);
        //query para extraer usuario para modificar
        $query = $conn->prepare("SELECT id_usuario, 
                                identificacion,
                                primer_apellido,
                                segundo_apellido,
                                nombre,
                                rol,
                                usuario   
                                 FROM usuario
                                 WHERE id_usuario!=$idUsuarioActual
                                 and rol=3
                                 and identificacion like :identificacion
                                 /*or primer_apellido like :primer_apellido
                                 or segundo_apellido like :segundo_apellido
                                 or nombre like :nombre
                                 or usuario like :usuario*/
                                ");
        $res = $query->execute([
            'identificacion' => $buscar/*,
            'primer_apellido' => $buscar,
            'segundo_apellido' => $buscar,
            'nombre' => $buscar,
            'usuario' => $buscar*/
        ]);
        if ($res == true) {
            $usuarios = $query->fetchAll(PDO::FETCH_OBJ);
            foreach ($usuarios as $data)
    {
     var_dump($data->nombre)   ;
    }
            
        }else{
            notificaciones('Búsqueda inválida');
        }
    }
}
anticsrf();
     $usuarios = $query->fetchAll(PDO::FETCH_OBJ); 
    ?>



<!-- partial:index.partial.html -->
<form class="register-container" method="post" enctype="multipart/form-data">
                <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
                <p><input type="text" placeholder="Buscar" id="txtBuscar" name="txtBuscar"  pattern="[A-Za-z0-9]+" required="required"></p>
                <p><input type="submit" value="Buscar usuario" name="btnBuscar"></p>
        </form> 

        <?php 
    foreach ($usuarios as $data)
    {
?>
 <div class="index">
     <div class="index input"> 
        <br>
        <label name="lblIdentificacion"><?php echo $data->identificacion;?> </label>
        <br>
        <label name="lblNombre"><?php echo $data->nombre;?> </label>
         
        <label name="lblPrimerA"><?php echo $data->primer_apellido;?> </label>
        <br>
        <label name="lblSegundoA"><?php echo $data->segundo_apellido;?> </label>
        <br>
        <label name="lblUsuario"><?php echo $data->usuario;?> </label>
        <br>
        <label name="lblRol"><?php echo $data->rol;?> </label>
    </div>
</div>
<!-- partial -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  
</body>
</html>
<?php
    //fin foreach
    }
?>
<?php
//fin if

?>