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
  } 

  ?>
