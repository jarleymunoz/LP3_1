<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ver cursos</title>
    <link rel="stylesheet" href="libs/style.css">

</head>

<body>
    <?php
    require "encabezado.php";
    LimpiezaKV();
    $idUsuarioActual = $_SESSION['usuario']['id'];

    $_SESSION['buscar'] = '';
    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php");
    }

    //Botón para volver a menú usuarios
    if (isset($_POST['btnVolver'])) {
        header("Location: cursos.php");
    }


    if ($idUsuarioActual != null) {
        //query para extraer usuario para modificar
        $query = $conn->prepare("SELECT 
                                 c.codigo,
                                 c.nombre,
                                 c.creditos,
                                 'ver curso'as enlace
                                 from curso c
                                
         ");
        $res = $query->execute([]);
        if ($res == true) {
            $usuarios = $query->fetchAll(PDO::FETCH_OBJ);
        } else {
            notificaciones('Búsqueda inválida');
        }
    } else {
        $usuarios = [];
    }
    anticsrf();

    ?>
    <!-- partial:index.partial.html -->
    <div class="index input">

        <form class="login-container" method="post" enctype="multipart/form-data">
            <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
            <input type="submit" value="Volver" name="btnVolver">

            <table BORDER CELLPADDING=10 CELLSPACING=10 class="default">
                <CAPTION ALIGN=top>LISTA DE CURSOS</CAPTION>
                <tr ALIGN=center style="background-color: #3498DB ; color:#17202A ;">
                    <td style="text-align: center;">Código curso</td>
                    <td style="text-align: center;">Nombre curso</td>
                    <td style="text-align: center;">Créditos</td>
                    <td style="text-align: center;">Enlace curso</td>
                </tr>


                <?php

                foreach ($usuarios as $data) {
                ?>
                    <tr ALIGN=center style="background-color: #00695c; color:#FFFFFF;">
                        <td style="text-align: center;"><?php echo $data->codigo; ?></td>
                        <td style="text-align: center;"><?php echo $data->nombre; ?></td>
                        <td style="text-align: center;"><?php echo $data->creditos; ?></td>
                        <td class="link" onclick="location='enlace.php'" target="_blank" style="text-align: center;"><?php echo $data->enlace; ?></td>

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