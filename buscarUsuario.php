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

    //limpieza de llave valor del $_POST
    LimpiezaKV();

    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php");
    }
    if (isset($_POST["btnVolver"])) {
        header("Location: usuarios.php");
    }
    if (isset($_POST["btnBuscar"])) {
        if (validarDocumento($_POST['txtIdentificacion']) == true) {
            $identificacion = Limpieza($_POST["txtIdentificacion"]);
        } else {
            notificaciones('Identificación inválida');
            $identificacion = "";
        }
        if($identificacion!=""){
            $query = $conn->prepare("SELECT identificacion,
                                    primer_apellido,
                                    segundo_apellido,
                                    nombre,
                                    rol,
                                    usuario
                                    FROM usuario WHERE identificacion=:identificacion ");
    $res = $query->execute([
        'identificacion' => $identificacion
      ]);
    if ($res == true) {
        $usuarios = $query->fetchAll(PDO::FETCH_OBJ);
        }
        

    }
}

    anticsrf();

    
    ?>
        


            <!-- partial:index.partial.html -->
            <div class="index">
                <form class="register-container" method="post" enctype="multipart/form-data">
                    <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
                    <p><input type="text" placeholder="Buscar" id="txtIdentificacion" name="txtIdentificacion" pattern="[A-Za-z0-9]+" ></p>
                    <p><input type="submit" value="Buscar usuario" name="btnBuscar"></p>
                    <p><input type="submit" value="Volver" name="btnVolver"></p>
                </form>
                
            </div>
            </div>
            <!-- partial -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
           
        
</body>

</html>
<?php
            //fin foreach
        
?>
<?php
        //fin if
    
?>