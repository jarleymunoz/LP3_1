<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Crear usuario</title>
  <link rel="stylesheet" href="libs/style.css">

</head>

<body>
  <?php
  require "encabezado.php";
      LimpiezaKV();
    
    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php");
    }
  
  $conn = conexion();

  //Boton de volver
  if (isset($_POST['btnVolver'])) {
    if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] || '0000' == $_POST['anticsrf']) {
      header("Location: usuarios.php");
    }
  }
  //Boton de registrar
  if (isset($_POST["btnCrear"])) {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = Limpieza($value);
    }


    if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] || '0000' == $_POST['anticsrf']) {

      //asigno a identificacion
      if (validarDocumento($_POST['txtIdentificacion']) == true) {
        $identificacion = Limpieza($_POST["txtIdentificacion"]);
      } else {
        notificaciones('Identificación inválida');
        $identificacion = "";
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
     //asigno a usuario
      if (validarUsuario($_POST['txtUsuario'])) {
        $usuario = Limpieza($_POST["txtUsuario"]);
      } else {
        notificaciones('Usuario inválido');
        $usuario = "";
      }
      //asigno a clave
      if (validarClave($_POST['txtClave'])) {
        $clave = Limpieza($_POST["txtClave"]);
        $claveHash =  password_hash($clave, PASSWORD_DEFAULT);
      } else {
        notificaciones('Contraseña inválida, la clave debe tener al menos una letra minúscula, una mayúscula, un número y un caracter especial');
        $clave = "";
        $claveHash = "";
      }
      
      //asigno a rol
      $roles = [1,2,3];
      if (in_array($_POST['txtRol'], $roles)) {
        $rol = Limpieza($_POST["txtRol"]);
      } else {
        notificaciones('Rol inválido');
        $rol = "";
      }
    
      if ($identificacion != "" && $primerApellido != "" && $segundoApellido != "" && $nombre != "" &&
             $correo != "" && $usuario != "" && $clave != "" && $rol != "" ) {
        //Busco el usuario en la base de datos para que no se repita
        $query = $conn->prepare("SELECT usuario, email 
                                 FROM   usuario 
                                 WHERE  usuario =:usuario
                                 or email =:correo");
        $res = $query->execute([
          'usuario' => $usuario,
          'correo' => $correo
        ]);
        if ($res == true) {
          $usua =  $query->fetchAll(PDO::FETCH_OBJ);
          if (empty($usua)) {
            
            $query1 = $conn->prepare("INSERT INTO usuario (identificacion, 
                                                           primer_apellido,
                                                           segundo_apellido,
                                                           nombre,
                                                           email,
                                                           usuario,
                                                           clave,
                                                           rol
                                                           ) 
                                      VALUES(:identificacion,
                                             :primerApellido,
                                             :segundoApellido,
                                             :nombre,
                                             :correo,
                                             :usuario,
                                             :clave,
                                             :rol)");
            $res1 = $query1->execute([
              'identificacion' => $identificacion,
              'primerApellido' => $primerApellido,
              'segundoApellido' => $segundoApellido,
              'nombre' => $nombre,
              'correo' => $correo,
              'usuario' => $usuario,
              'clave' => $claveHash,
              'rol' => $rol
            ]);
            
            if ($res1 == true) {
              notificaciones('Usuario creado correctamente');
              header("refresh:1;url: usuarios.php");
            }
          } else {
            notificaciones('Usuario o correo ya existe');
            header("refresh:2;url: crearUsuario.php");
          }
        }
      } else {
        notificaciones('Datos faltantes');
        header("refresh:2;url: crearUsuario.php");
      }
    } else {
      notificaciones('Petición invalida');
      //header("refresh:2;url: crearUsuario.php");
    }
  }

  anticsrf();
  ?>
  <!-- partial:index.partial.html -->
  <div class="login">

    <h2 class="register-header">Crear usuario</h2>

    <form class="register-container" method="post" enctype="multipart/form-data">
      <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
      <p><input type="text" placeholder="Identificación" id="txtIdentificacion" name="txtIdentificacion" pattern="[A-Za-z0-9]+" required="required"></p>
      <p><input type="text" placeholder="Primer apellido" id="txtPrimerApellido" name="txtPrimerApellido" pattern="[A-Za-z]+" required="required"></p>
      <p><input type="text" placeholder="Segundo apellido" id="txtSegundoApellido" name="txtSegundoApellido" pattern="[A-Za-z]+" required="required"></p>
      <p><input type="text" placeholder="Nombre" id="txtNombre" name="txtNombre" pattern="[A-Za-z]+" required="required"></p>
      <p>Rol  
        <select id="txtRol" name="txtRol" required="required">
          <option value=1>Administrador</option>
          <option value=2>Docente</option>
          <option value=3>Alumno</option>
        </select>
      </p>
      <p><input type="email" placeholder="Correo" id="txtCorreo" name="txtCorreo" required="required"></p>
      <p><input type="text" placeholder="Usuario" id="txtUsuario" name="txtUsuario" pattern="[A-Za-z0-9]+" required="required"></p>
      <p><input type="password" placeholder="Clave" id="txtClave" name="txtClave"></p>

      <p><input type="submit" value="Crear usuario" name="btnCrear"></p>
    </form>
    <form class="register-container" method="post" enctype="multipart/form-data">
      <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
      <p><input action="usuarios.php" type="submit" value="Volver" name="btnVolver"></p>
    </form>
  </div>
  <!-- partial -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>