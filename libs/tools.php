<?php
//require_once '../vendor/autoload.php'; //libreria de composer
use Firebase\JWT\JWT; //libreria de jwt
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use ReallySimpleJWT\Token;

//require '../vendor/PHPMailer/PHPMailer/src/Exception.php';
//require '../vendor/PHPMailer/PHPMailer/src/PHPMailer.php';
//require '../vendor/PHPMailer/PHPMailer/src/SMTP.php';
require 'vendor/autoload.php';

/**
 * Función que retorna el usuario actual con inicio de sesión con token
 */
function usuarioActual()
{
    $jwt = $_SERVER['HTTP_AUTHORIZATION'];
    $key = 'my_secret_key';
    if (substr($jwt, 0, 6) === "Bearer") {
        $jwt = str_replace("Bearer ", "", $jwt);
        
        try {
            $data = JWT::decode($jwt,$key, array('HS256'));
            $datos = $data->data;
            return $datos->usuario;
        } catch (\Throwable $th) {
            echo 'error: ';
            return '';
        }
    } 
        return '';
    
}


/**
 * Función que limpia la llave valor del metodo _POST
 */
function LimpiezaKV()
{
    foreach ($_POST as $key => $value) {
        $_POST[$key] = Limpieza($value);
    }
}
/**
 * Función que limpia todos los datos de entrada
 * @param cadena: Recibe la cadena a limpiar.
 */
// Limpieza de datos de entrada
function Limpieza($cadena)
{
    $patron = array('/<script>.*<\/script>/');
    $cadena = preg_replace($patron, '', $cadena);
    $cadena = htmlspecialchars($cadena);
    return $cadena;
}

/**
 * Función que limpia todos los datos de entrada de tokens
 * @param cadena: Recibe la cadena a limpiar.
 */
// Limpieza de datos de entrada
function LimpiezaToken($cadena)
{
    $patron = array('/<script>*<\/script>/');
    $cadena = preg_replace($patron, '', $cadena);
    $cadena = htmlspecialchars($cadena);
    return $cadena;
}

/**
 * Función para limpiar parametros de entrada 
 * 
 * */
function limpiarEntradas()
{
    if (isset($_POST)) {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = Limpieza($value);
        }
    }
}
/**
 * Función que permite el aseguramiento de la sesión
 * 
 */
function sesionSegura()
{
    //obtener los parametros de la cookie de sesión
    $cookieParams = session_get_cookie_params();
    $path = $cookieParams["path"];

    //inicio y control de la sesion
    $secure = true;
    $httpOnly = true;
    $sameSite = 'strict';

    session_set_cookie_params([
        'lifetime' => $cookieParams["lifetime"],
        'path'    => $path,
        'domain'  => $_SERVER['HTTP_HOST'],
        'secure'  => $secure,
        'httponly' => $httpOnly,
        'samesite' => $sameSite
    ]);
    session_start();
    session_regenerate_id(true); //permite que cada llamado se genere una nueva sesión

}
/**
 * Función que cierra la sesión del usuario
 */
function cerrarSesion()
{
    session_destroy();
    header("refresh:3;url=index.php");
}

/**
 * funcion que genera un número aleatorio cada vez que haga un envío de datos.
 */
function anticsrf()
{
    $anticsrf = random_int(1000000, 9999999);
    $_SESSION['anticsrf'] = $anticsrf;
}

/**
 * Función que recibe una cadena y retorna true si es texto
 * @param texto: Recibe la cadena y valida si cumple con el patron de texto 
 */
function validarTexto($texto)
{
    $tex = trim($texto);
    if ($tex == "" && trim($tex) == "") {
        return false;
    } else {
        $patron = '/^[a-zA-Z, ]*$/';
        if (preg_match($patron, $tex)) {
            return true;
        } else {
            return false;
        }
    }
}
/**
 * Función que recibe una cadena y retorna true si es un usuario
 * @param user: Recibe la cadena y valida si cumple con el patron de usuario 
 */
function validarUsuario($user)
{
    $us = trim($user);
    if ($us == "" && trim($us) == "") {
        return false;
    } else {
        $patron = '/^[a-zA-Z0-9, ]*$/';
        if (preg_match($patron, $us)) {
            return true;
        } else {
            return false;
        }
    }
}
/**
 * Función que recibe una cadena y retorna true si es un documento válido.
 * @param doc: Recibe la cadena y valida si cumple con el patron del número de documento.
 */
function validarDocumento($doc)
{
    $us = trim($doc);
    if ($us == "" && trim($us) == "") {
        return false;
    } else {
        $patron = '/^[a-zA-Z0-9, ]*$/';
        if (preg_match($patron, $us)) {
            return true;
        } else {
            return false;
        }
    }
}
/**
 * Función que recibe una cadena y retorna true si es una clave
 * @param user: Recibe la cadena y valida si cumple con el patron de clave
 */
function validarClave($clave)
{
    if (strlen($clave) < 6) {
        echo 'La clave debe tener al menos 6 caracteres';
        return false;
    }
    if (strlen($clave) > 16) {
        echo 'La clave no puede tener más de 16 caracteres';
        return false;
    }
    if (!preg_match('`[a-z]`', $clave)) {
        return false;
    }
    if (!preg_match('`[A-Z]`', $clave)) {
        return false;
    }
    if (!preg_match('`[0-9]`', $clave)) {
        return false;
    }
    if (!preg_match('`[*,+,/,#]`', $clave)) {        
        return false;
    }
    return true;
}
/**
 * Función que muestra las echo al usuario
 * @param notificacion: Mensaje para mostrar.
 */
function notificaciones($notificacion)
{

?>
    <div><label name="notificacion"> <?php echo $notificacion ?></label></div>
<?php

}
/**
 * Función que recibe una cadena y retorna true si es texto válido y menor de 140 caracteres
 * @param texto: Recibe la cadena y valida si cumple con el patron de texto 
 */
function validarArticulo($texto)
{
    if (strlen($texto) <= 140) {
        $tex = trim($texto);
        if ($tex == "" && trim($tex) == "") {
            return false;
        } else {
            $patron = '/^[a-zA-Z0-9!¡¿?.,*áéíóúÁÉÍÓÚñÑ, ]*$/';
            if (preg_match($patron, $tex)) {
                return true;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}
/**
 * Función que recibe una cadena y retorna true si es un mensaje válido
 * @param texto: Recibe la cadena y valida si cumple con el patron de texto 
 */
function validarMensaje($texto)
{
    if (strlen($texto) <= 140) {
        $tex = trim($texto);
        if ($tex == "" && trim($tex) == "") {
            return false;
        } else {
            $patron = '/^[a-zA-Z0-9!¡¿?.,*áéíóúÁÉÍÓÚñÑ, ]*$/';
            if (preg_match($patron, $tex)) {
                return true;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}
/**
 * Función que recibe una cadena y retorna true si es una fecha
 * @param fecha: Recibe la cadena y valida si cumple con el patron de fecha en el formato solicitado 
 */
function validarFecha($fecha)
{
    $fe = explode('-', $fecha);
    if (count($fe) != 3) {
        return false;
    } else if (checkdate($fe[1], $fe[2], $fe[0]) == true) {
        return true;
    } else {
        return false;
    }
}

/**
 * Función que recibe una cadena y retorna true si es un correo
 * @param correo: Recibe la cadena y valida si cumple con el patron de correo 
 */
function validarCorreo($correo)
{
    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Función que recibe una cadena y retorna true si es un color
 * @param color: Recibe la cadena y valida si cumple con el patron de color 
 */
function validarColor($color)
{
    if (preg_match("((#)[0-9a-fA-F]{6})", $color)) {
        return true;
    } else {
        return false;
    }
}




/**
 * Función que recibe una cadena y retorna true si es una direccion
 * @param clave: Recibe la cadena y valida si cumple con el patron de la dirección
 */
function validarDireccion($direccion)
{
    $cla = trim($direccion);
    if ($cla == "" && trim($cla) == "") {
        return false;
    } else {
        $patron = '/^[a-zA-Z0-9 # - ]*$/';
        if (preg_match($patron, $cla)) {
            return true;
        } else {
            return false;
        }
    }
}
/**
 * Función que recibe una cadena y retorna true si es una direccion
 * @param tuit: Recibe la cadena y valida si cumple con el patron del tuit
 */
function validarTuit($tuit)
{
    if (strlen($tuit) <= 140) {
        return true;
    } else {
        return false;
    }
}

/**
 * Función para decodificar una imagen enviada desde el servidor
 * @param: base64_string: Cadena en base 64 del archivo
 * @param: output_file: Ruta donde se aloja la imagen
 */
function base64_to_jpeg($base64_string, $output_file) {
    // open the output file for writing
    $ifp = fopen( $output_file, 'wb' ); 

    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode( ',', $base64_string );

    // we could add validation here with ensuring count( $data ) > 1
    fwrite( $ifp, base64_decode( $data[ 1 ] ) );

    // clean up the file resource
    fclose( $ifp ); 
    return $output_file; 
}

/**
 * Función para buscar email
 */
function buscarEmail($emailRec) {
    //var_dump($emailRec);
    //Buscamos email en bd
    $conn = conexion();
    $query = $conn->prepare("SELECT usuario 
                            FROM usuario 
                            WHERE email=:email ");
    $res = $query->execute([
        'email' => $emailRec
    ]);

    if ($res == true) {
        $usuario = $query->fetchAll(PDO::FETCH_OBJ);
        if (sizeof($usuario) > 0) {
            //var_dump($usuario);
            //echo "<br>";
            $usu = array($usuario[0]);
            //var_dump($usu);
            //echo "<br>";
            $us = $usu[0];
            //echo "usuario de la consulta: ";
            //var_dump($us);
            //echo "<br>";
            foreach ($us as $key => $val) {
                $u = $val;
                //echo "usuario extraído del objeto: ";
                //var_dump($u);
                //echo "<br>";
            }
            $jwt =& crearToken($u);
            //print_r(json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $jwt)[1])))));
            //echo "jwt: ";
            //echo $jwt;
            //echo "<br>";
            $token = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $jwt)[1]))));
            //echo "objeto con id, usuario e iat: ";
            //var_dump($token);
            //echo "<br>";
            foreach ($token as $key => $value) {
                if ($key == 'usuario') {
                    $user = $value;
                    //echo "usuario decodificado: ";
                    //echo $user;
                    //echo "<br>";
                    enviarInstrucciones($user, $emailRec, $jwt);
                } /*else if ($key == 'iat') {
                    echo "iat: ";
                    echo $value;
                    echo "<br>";
                }*/
            }
        }
        else {
            //notificación segura
            notificaciones('Si tienes una cuenta se te envío un correo, revisalo');
            //notificaciones('Email no encontrado');
        }
    }
    else {
        notificaciones('Hubo un problema');
    }
}

function enviarInstrucciones($usuario, $email, $token) {
    define("EMAIL_ADMIN", 'servidor2lp3@gmail.com');
    define("EMAIL_USER", $email);
    $mail = new PHPMailer();
    $mail->isSMTP();
    //server gmail
    $mail->Host = 'smtp.gmail.com';
    //$mail->Host = 'smtp.mailtrap.io';
    $mail->SMTPAuth = true;
    //gmail port
    $mail->Port = 465;
    //$mail->Port = 2525;

    //gmail username and pasword
    $mail->Username = 'servidor2lp3@gmail.com';
    $mail->Password = 'svafaikxxitpiidu';
    //$mail->Username = '3c1408f64d5eef';
    //$mail->Password = '5126b6257cf846';
    //$mail->SMTPSecure = 'tls';
    $mail->SMTPSecure = 'ssl';

    //contenido del email
    //$mail->setFrom('admin@linea3.com');
    $mail->setFrom(EMAIL_ADMIN);
    //$mail->addAddress('user@linea3.com', 'LP3');
    $mail->addAddress(EMAIL_USER, 'lp3');
    $mail->Subject = 'Restablece tu clave';

    //habilitar html
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    //definir contenido
    $contenido = '<html>';
    $contenido .= "<p><strong>Hola " . $usuario . "</strong> Has solicitado restablecer tu clave, 
    da click en el siguiente enlace para restablecerla.</p>";
    $contenido .= "<p>Recuerda que tienes 5 minutos para hacerlo o deberas hacer una nueva solicitud</p>";
    $contenido .= "<p>Presiona aquí: <a href='http://localhost/Git/LP3_1/restablecer.php?id=" .
    $token . "'>Restablecer clave</a></p>";
    $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
    $contenido .= "</html>";

    $mail->Body = $contenido;
    $mail->AltBody = 'Texto alternativo sin HTML';

    //enviar email
    if($mail->send()) {
        $conn = conexion();
        //Insertar token al usuario
        try {
            $query1 = $conn->prepare("UPDATE usuario 
                                                    SET token=:token 
                                                    WHERE usuario=:usuario");
                            $res1 = $query1->execute([
                                'token' => $token,
                                'usuario' => $usuario
                            ]);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        }
        //notificación segura
        notificaciones('Si tienes una cuenta se te envío un correo, revisalo');
    }
    else {
        notificaciones('Hubo un problema de conexón');
    }
}

// Generar un Token
function &crearToken($usuario) {
    $encrypt = uniqid();
    //return $token;

    $now = new DateTimeImmutable();
    $now2 = $now->modify('+300 seconds');

    // Create token header as a JSON string
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

    $arr = array('id' => $encrypt, 'iat' => $now2->getTimestamp(), 'usuario' => $usuario);

    // Create token payload as a JSON string
    $payload = json_encode($arr);

    // Encode Header to Base64Url String
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

    // Encode Payload to Base64Url String
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

    // Create Signature Hash
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'abC123!', true);

    // Encode Signature to Base64Url String
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    // Create JWT
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    return $jwt;
}

?>