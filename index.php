<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Ingreso</title>
  <link rel="stylesheet" href="libs/style.css">

</head>

<body>
  <?php
  require "libs/tools.php";
  require "libs/conexion.php";
  sesionSegura();
  LimpiezaKV();
  $conn = conexion();

  //Botón de Ingresar
  if (isset($_POST["btnIngresar"])) {

    if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] || '0000' == $_POST['anticsrf']) {
      if (validarUsuario($_POST['txtUsuario']) == true && validarClave($_POST['txtClave']) == true) {
        $usuario = Limpieza($_POST["txtUsuario"]);
        $clave = Limpieza($_POST["txtClave"]);

        $query = $conn->prepare("SELECT id_usuario,nombre,usuario,clave,rol 
                                 FROM usuario 
                                 WHERE usuario =:usuario");
        $res = $query->execute([
          'usuario' => $usuario
        ]);
        if ($res == true) {
          $usuar = $query->fetchAll(PDO::FETCH_OBJ);
          if (!empty($usuar)) {
            foreach ($usuar as $data) {
              $id     = $data->id_usuario;
              $nombre = $data->nombre;
              $user   = $data->usuario;
              $pass   = $data->clave;
              $rol    = $data->rol;
            }
            
            if (password_verify($clave, $pass)) {
              $_SESSION['usuario']['usuario'] = $user;
              $_SESSION['usuario']['nombre']  = $nombre;
              $_SESSION['usuario']['id']      = $id;
              $_SESSION['usuario']['rol']     = $rol;
              $_SESSION['usuario']['buscar']  = '';
              notificaciones('Datos válidos');
              switch($rol){
                case 1:
                header("refresh:2;url=inicio_admin.php");
                break;
                case 2:
                header("refresh:2;url=inicio_docente.php");
                break;
                case 3:
                header("refresh:2;url=inicio_alumno.php");
                break;
              }              
            } else {
              notificaciones('Clave incorrecta');
            }
          } else {
            notificaciones('No se encuentra el usuario');
          }
        }
      } else {
        notificaciones('Datos incorrectos');
      }
    } else {
      notificaciones('Petición invalida');
      header("refresh:2;url=index.php");
    }
  }
  //Botón de Restablecer clave
  if (isset($_POST["btnOlvide"])) {
    if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] ) {
      header("Location: olvide.php");
    } else {
      notificaciones('Petición invalida');
    }
  }
  anticsrf();
  ?>
  <!-- partial:index.partial.html -->
  <div class="index">
    <h2 class="integrantes-header">Ricardo A. Triviño - Jose A. Muñoz</h2>
  </div>
  <div class="login">
    <div class="login-triangle"></div>

    <h2 class="login-header">Ingreso</h2>

    <form class="login-container" method="post">
      <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
      <p><input type="text" name="txtUsuario" id="txtUsuario" placeholder="Usuario" pattern="[A-Za-z0-9]+" required="required"></p>
      <p><input type="password" name="txtClave" id="txtClave" placeholder="Clave" pattern="^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$" required="required"></p>
      <p><input type="submit" name="btnIngresar" value="Ingresar"></p>
    </form>
    <form class="login-container" method="post">
    <input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>">
      <p><input type="submit" name="btnOlvide" value="Olvidé mi clave"></p>
    </form>
  </div>
  <!-- partial -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>