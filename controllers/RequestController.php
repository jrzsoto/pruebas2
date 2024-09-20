<?php

use models\SQLQueries\RequestModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../models/SQLQueries/RequestModel.php';
require '../vendor/PHPMailer-master/src/Exception.php';
require '../vendor/PHPMailer-master/src/PHPMailer.php';
require '../vendor/PHPMailer-master/src/SMTP.php';
//require 'VerifyController.php';
$data = json_decode(file_get_contents('php://input'), true);

$rm = new RequestModel();
$result = $rm->InsertData($data['names'],$data['last_name'],$data['email'],$data['curp'],$data['second_last_name'],
                          $data['id_general'],$data['gender'],$data['birth_date'],$data['birth_place'],$data['mobile_number'],$data['register_date'],
                          $data['nationality'],$data['admission_period'],$data['academic_area'],$data['degree'],$data['country']
                        );

//Si se inserta bien
if ($result['status']) {

    $mail = new PHPMailer(true);
    //Se prueba a mandar el correo
   try {

      /**/
      //$correoDestino = $data['email'];
      $correoDestino = 'josezacatzontelt@gmail.com';           
      $servidorCorreo = "calmecac.inaoep.mx";
      $usuario = "info_sistema";
      $contrasena = "Ch4NS3O24@+/--";

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
        $mail->setFrom('info_sistema@inaoep.mx', 'info_sistema');
        $mail->addAddress($correoDestino);
       // $mail->addCC('info_sistema@inaoep.mx');
        $mail->isHTML(true);  

        // Configurar asunto y mensaje

        $fullName         = $data['names'];

        $nombreUsuario    = $data['email'];
        $nombreUsuario    = explode('@', $nombreUsuario);
        $nombreUsuario    = $nombreUsuario[0];
        
        //TODO: esto va en el settings
        $contrasenaSisgea = "2024#1N4031487";

        $mensaje = "
        <html>
        <head>
          <title>Creación de cuenta</title>
        </head>
        <body>
          <div style='background-color: #007bff; color: white; padding: 10px;'>
            <h1>Creación de cuenta</h1>
          </div>
          <p>Bienvenido $fullName </p>
          <p>Se ha creado su cuenta de usuario en el Sistema de Gestión Académica del INAOE, de acuerdo a los datos que ingresó en la ventanilla única para el proceso de admisión. Sus datos de acceso son:</p>
          <ul>
            <li>Usuario: $nombreUsuario</li>
            <li>Contraseña: $contrasenaSisgea</li>
          </ul>
          <p></p>
          <p>Liga al sistema: <a href='https://sisgea.inaoep.mx/ModuloAdministracion/AccountSga/Login'>https://sisgea.inaoep.mx/ModuloAdministracion/AccountSga/Login</a></p>
          <p>En caso de algun problema ponerse en contacto con admisiones@inaoep.mx </p>
        </body>
        </html>
        ";

        $asunto = "Creación de cuenta del sistema de gestión académica del INAOE";

        $mail->Subject = $asunto;
        $mail->Body = $mensaje;

        // Enviar correo electrónico
        $mail->send();

            
        $result['mail'] = 'Mensaje enviado correctanente';
        $result['status'] = true;
    } catch (Exception $e) {
        $result['mail'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    
    header('Content-Type: application/json; charset=utf-8');   
    exit(json_encode($result));
}else{

  header('Content-Type: application/json; charset=utf-8');  
  exit(json_encode($result));
}


//exit('Error');
