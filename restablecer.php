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

  //$token = Limpieza($_GET['token']);
  //echo $token;
  if (!isset($_GET['id'])) {
    header("Location: index.php");
  } else {
    $token = Limpieza($_GET['id']);
    $toke = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
    //var_dump($token);
    //echo "<br>";
    $now = new DateTimeImmutable();
    $now->getTimestamp();
    foreach ($toke as $key => $value) {
      if ($key == 'iat') {
        if ( $value <= time()) {
          echo "Sesion expiradada";
          //forzar salida al login
          //header("Location: index.php");
          cerrarSesion();
        } /*else {
          echo "Token no ha expirado";
        }*/
      } else if ($key == 'usuario') {
        $usuario = $value;
        echo "restablece tu clave: ";
        echo $usuario;
        echo "<br>";
      }
    }
    $query = $conn->prepare("SELECT token 
                               FROM usuario 
                               WHERE usuario=:usuario ");
    $res = $query->execute([
      'usuario' => $usuario
    ]);
    if ($res == true) {
      $toke = $query->fetchAll(PDO::FETCH_OBJ);
      $tok = array($toke[0]);
      //var_dump($tok);
      $to = $tok[0];
      //var_dump($to);
      foreach ($to as $key => $value) {
        //echo "$key: $value\n";
        if ($value == $token) {
          //Boton de nueva clave
          if (isset($_POST["btnRestablecer"])) {
            //asigno a clave
            if (validarClave($_POST['txtClave'])) {
              $clave = Limpieza($_POST["txtClave"]);
              //actualizar clave en bd
              $query1 = $conn->prepare("UPDATE usuario 
                                                      SET clave=:clave 
                                                      WHERE usuario=:usuario");
              $claveNuevaHash =  password_hash($clave, PASSWORD_DEFAULT);
              $res1 = $query1->execute([
                'clave' => $claveNuevaHash,
                'usuario' => $usuario
              ]);
              if ($res1 == true) {
                notificaciones('Exito, se actualizó la clave');
                //poner el token de la bd en null de nuevo
                $token = null;
                $query1 = $conn->prepare("UPDATE usuario 
                                                      SET token=:token 
                                                      WHERE usuario=:usuario");
                $res1 = $query1->execute([
                  'token' => $token,
                  'usuario' => $usuario
                ]);
                if ($res1 == true) {
                  //redirigir a index
                  cerrarSesion();
                } else {
                  notificaciones('Lo sentimos, algo falló 1');
                  //redirigir a index
                  cerrarSesion();
                }
              } else {
                notificaciones('Lo sentimos, algo falló 2');
                //redirigir a index
                cerrarSesion();
              }
            } else {
              notificaciones('Contraseña inválida, la clave debe tener al menos una letra minúscula, una mayúscula, un número y un caracter especial');
            }
          }
        } else {
          notificaciones('No autorizado');
          //redirigir a index
          cerrarSesion();
        }
      }
    } else {
      notificaciones('No autorizado');
      //redirigir a index
      cerrarSesion();
    }
    //Boton de volver
    if (isset($_POST['btnVolver'])) {
      if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] || '0000' == $_POST['anticsrf']) {
        header("Location: index.php");
      }
    }
    //Boton de restablecer
    anticsrf();
  }

  ?>
  <!-- partial:index.partial.html -->
  <div class="login">

    <h2 class="register-header">Restablecer clave</h2>

    <form class="register-container" method="post" enctype="multipart/form-data">
      <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
      <p><input type="password" placeholder="Nueva clave" id="txtClave" name="txtClave" required="required"></p>
      <p><input type="submit" value="Confirmar clave" name="btnRestablecer"></p>
      <p><input action="index.php" type="submit" value="Volver" name="btnVolver"></p>
    </form>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>