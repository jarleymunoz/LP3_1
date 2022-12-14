<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Inicio</title>
  <link rel="stylesheet" href="libs/style.css">

</head>

<body>
  <?php
  require "libs/conexion.php";
  require "encabezado.php";
  LimpiezaKV();
  $conn = conexion();
  //si no existe la sesión va al index,
  if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
  } else {
    //Boton de mis cursos

    if (isset($_POST["lnkCursos"])) {
      
      if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {
        header("Location: misCursos.php");
      } else {
        notificaciones('Petición inválida');
        //header("refresh:2;url=inicio.php");
      }
    }
    //Boton de perfil
    if (isset($_POST["lnkPerfil"])) {
      if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {
        header("Location: perfil.php");
      } else {
        notificaciones('Petición inválida');
        //header("refresh:2;url=inicio.php");
      }
    }
    
  }

  ?>
  <!-- partial:index.partial.html -->
  <div class="index">
    <div class="index input">
      <form method="post">
        <br>
        <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
        <input type="submit" class="login" name="lnkCursos" value="Mis cursos">

        <input type="submit" class="login" name="lnkPerfil" value="Mi perfil">
        <!--//var_dump($_SESSION['usuario']['foto']);-->
      </form>
    </div>
  </div>
  <!-- partial -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>