<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Crear matrícula</title>
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
    $idUsuarioActual = $_SESSION['usuario']['id'];
    //query para extraer los alumnos para la matricula
    $query = $conn->prepare("SELECT id_usuario, 
                                   concat(nombre,' ',primer_apellido,' ',segundo_apellido) as alumno 
                                   FROM usuario
                                   WHERE rol=3
                                   ORDER BY alumno ASC");
    $res = $query->execute([]);
    if ($res == true) {
        $alumnos = $query->fetchAll(PDO::FETCH_OBJ);
    }
    //query para extraer los cursos actuales para la matricula
    $query1 = $conn->prepare("SELECT id_curso, 
                                    nombre 
                             FROM curso 
                            ");
    $res1 = $query1->execute([]);
    if ($res1 == true) {
        $cursos = $query1->fetchAll(PDO::FETCH_OBJ);
    }


    //Boton de volver
    if (isset($_POST['btnVolver'])) {
        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] || '0000' == $_POST['anticsrf']) {
            header("Location: matricula.php");
        }
    }
    //Boton de registrar
    if (isset($_POST["btnCrear"])) {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = Limpieza($value);
        }
        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] || '0000' == $_POST['anticsrf']) {

            //asigno a alumno
            if (is_numeric($_POST['cmbAlumno'])) {
                $id_alumno = Limpieza($_POST["cmbAlumno"]);
            } else {
                notificaciones('Alumno inválido');
                $id_alumno = "";
            }
            //asigno a curso
            if (is_numeric($_POST['cmbCurso'])) {
                $id_curso = Limpieza($_POST["cmbCurso"]);
            } else {
                notificaciones('Curso inválido');
                $id_curso = "";
            }

            if ($id_curso != "" && $id_alumno != "" ) {
                //Busco que el alumno no  tenga matriculado el curso ya
                $query2 = $conn->prepare("SELECT id_matricula 
                                 FROM   matricula 
                                 WHERE  id_usuario=:id_usuario
                                 AND    id_curso=:id_curso");
                $res2 = $query2->execute([
                    'id_usuario' => $id_alumno,
                    'id_curso' => $id_curso
                ]);
                if ($res2 == true) {
                    $matricula =  $query2->fetchAll(PDO::FETCH_OBJ);
                    if (empty($matricula)) {

                        $query3 = $conn->prepare(
                            "INSERT INTO matricula (  id_usuario,
                                                      id_curso
                                                           ) 
                                      VALUES(:id_usuario,
                                             :id_curso
                                             )"
                        );
                        $res3 = $query3->execute([
                            'id_usuario' => $id_alumno,
                            'id_curso' => $id_curso
                        ]);

                        if ($res3 == true) {
                            notificaciones('Matrícula creada correctamente');
                            header("refresh:1;url: crearMatricula.php");
                        }
                    } else {
                        notificaciones('No se puede crear, el alumno ya tiene matriculado el curso');
                        header("refresh:2;url: crearMatricula.php");
                    }
                }
            } else {
                notificaciones('Datos faltantes');
                header("refresh:2;url: crearMatricula.php");
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

        <h2 class="register-header">Crear matrícula</h2>

        <form class="register-container" method="post" enctype="multipart/form-data">
        <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
            <p><label> Alumno:</label>
                <select name="cmbAlumno">
                    <?php foreach ($alumnos as $data) {
                    ?>
                        <option value="<?php echo $data->id_usuario; ?>"><?php echo $data->alumno ?></option>
                    <?php
                    }
                    ?>
                </select>
            </p>
            <p><label> Curso:</label>
                <select name="cmbCurso">
                    <?php foreach ($cursos as $data1) {
                    ?>
                        <option value="<?php echo $data1->id_curso; ?>"><?php echo $data1->nombre ?></option>
                    <?php
                    }
                    ?>
                </select>
            </p>
            <p><input type="submit" value="Crear matrícula" name="btnCrear"></p>
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