<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Restablecer clave</title>
  <link rel="stylesheet" href="libs/style.css">

</head>

<body>
  <?php
  require "libs/tools.php";
  require "libs/conexion.php";
  sesionSegura();
  $conn = conexion();

  //Boton de volver
  if (isset($_POST['btnVolver'])) {
    if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] || '0000' == $_POST['anticsrf']) {
      header("Location: index.php");
    }
  }
  //Boton de restablecer
  

  anticsrf();
  ?>
  <!-- partial:index.partial.html -->
  <div class="login">

    <h2 class="register-header">Restablecer clave</h2>

    <form class="register-container" method="post" enctype="multipart/form-data">
      <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
      <p><input action="index.php" type="submit" value="Volver" name="btnVolver"></p>
    </form>
  </div>
   
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>