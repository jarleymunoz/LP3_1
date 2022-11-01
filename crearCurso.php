<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Crear curso</title>
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
      header("Location: cursos.php");
    }
  }
  //Boton de registrar
  if (isset($_POST["btnCrear"])) {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = Limpieza($value);
    }


    if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] || '0000' == $_POST['anticsrf']) {

      //asigno a codigo del curso
      if (validarDocumento($_POST['txtCodigo']) == true) {
        $codigo = Limpieza($_POST["txtCodigo"]);
      } else {
        notificaciones('Código inválido');
        $codigo = "";
      }
      //asigno nombre del curso
      if (validarDocumento($_POST['txtNombreCurso']) == true) {
        $nombreCurso = Limpieza($_POST["txtNombreCurso"]);
      } else {
        notificaciones('Nombre curso inválido');
        $nombreCurso = "";
      }
      //asigno a creditos
     
      if (is_numeric($_POST['txtCreditos'])) {
        $creditos = Limpieza($_POST["txtCreditos"]);
      } else {
        notificaciones('Número de créditos inválido');
        $creditos = "";
      }
    
      if ($codigo != "" && $nombreCurso != "" && $creditos != "") {
        //Busco el curso  en la base de datos para que no se repita
        $query = $conn->prepare("SELECT codigo 
                                 FROM   curso 
                                 WHERE  codigo =:codigo");
        $res = $query->execute([
          'codigo' => $codigo
        ]);
        if ($res == true) {
          $usua =  $query->fetchAll(PDO::FETCH_OBJ);
          if (empty($usua)) {
            
            $query1 = $conn->prepare("INSERT INTO curso (  codigo, 
                                                           nombre,
                                                           creditos
                                                           ) 
                                      VALUES(:codigo,
                                             :nombre,
                                             :creditos)"
                                             );
            $res1 = $query1->execute([
              'codigo' => $codigo,
              'nombre' => $nombreCurso,
              'creditos' => $creditos
            ]);
            
            if ($res1 == true) {
              notificaciones('curso creado correctamente');
              header("refresh:1;url: cursos.php");
            }
          } else {
            notificaciones('No se puede crear, el curso ya existe');
            header("refresh:2;url: crearCurso.php");
          }
        }
      } else {
        notificaciones('Datos faltantes');
        header("refresh:2;url: crearCurso.php");
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

    <h2 class="register-header">Crear curso</h2>

    <form class="register-container" method="post" enctype="multipart/form-data">
      <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
      <p><input type="text" placeholder="Codigo" id="txtCodigo" name="txtCodigo" pattern="[A-Za-z0-9]+" required="required"></p>
      <p><input type="text" placeholder="Nombre curso" id="txtNombreCurso" name="txtNombreCurso" required="required"></p>
      <p><input type="number" placeholder="Créditos" id="txtCreditos" name="txtCreditos" pattern="[0-9]+" required="required"></p>
      
      <p><input type="submit" value="Crear curso" name="btnCrear"></p>
    </form>
    <form class="register-container" method="post" enctype="multipart/form-data">
      <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
      <p><input action="cursos.php" type="submit" value="Volver" name="btnVolver"></p>
    </form>
  </div>
  <!-- partial -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>