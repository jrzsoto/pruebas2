<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Cargar el autoload de PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Función para enviar correo electrónico
function enviarCorreo($correoDestino, $asunto, $mensaje, $usuario, $contrasena, $servidorCorreo) {
    // Instanciar PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configurar SMTP
        $mail->isSMTP();
		$mail->CharSet = 'UTF-8';

        $mail->Host = $servidorCorreo;
        $mail->SMTPAuth = true;
        $mail->Username = $usuario;
        $mail->Password = $contrasena;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Configurar remitente y destinatario
        $mail->setFrom('m.valencia@inaoep.mx', 'Miguel');
        $mail->addAddress($correoDestino);

        // Configurar asunto y mensaje
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;

        // Enviar correo electrónico
        $mail->send();
        return true; // Éxito al enviar el correo
    } catch (Exception $e) {
        return false; // Error al enviar el correo
    }
}

// Ejemplo de uso
$correoDestino = "miguel.valencia.017@gmail.com";
$asunto = "Prueba de correo electrónico 2";
$mensaje = "Este es un mensaje de prueba.";
$usuario = "m.valencia";
$contrasena = "2023&m1.V4l.";
$servidorCorreo = "ccc.inaoep.mx";

if (enviarCorreo($correoDestino, $asunto, $mensaje, $usuario, $contrasena, $servidorCorreo)) {
    echo "Correo enviado exitosamente a $correoDestino.";
} else {
    echo "Error al enviar el correo.";
}
?>
