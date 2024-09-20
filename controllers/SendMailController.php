<?php

namespace controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/PHPMailer-master/src/Exception.php';
require '../vendor/PHPMailer-master/src/PHPMailer.php';
require '../vendor/PHPMailer-master/src/SMTP.php';

class SendMailController {

    private $mail;

    public function __construct() {

        $this->mail = new PHPMailer(true);
        // Configurar SMTP
        $this->mail->isSMTP();
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Host = 'smtp.mailtrap.io';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'c60d976e789988';
        $this->mail->Password = 'c24a9762fc71b4';
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = 2525;

        // Configurar remitente y destinatario
        $this->mail->setFrom('info_sistema@inaoep.mx', 'info_sistema');
    }


    /**
     * @param string $email
     * @param string $fullName
     * 
     * @return Mixed
     */
    public function send(string $email, string $fullName) {

        try {

            $this->mail->addAddress($email);
            $this->mail->isHTML(true);

            // Configurar asunto y mensaje
            $nombreUsuario    = explode('@', $email);
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

            $this->mail->Subject = $asunto;
            $this->mail->Body = $mensaje;

            // Enviar correo electrónico
            $this->mail->send();

            $status = true;
            $data = 'Mail Send';

            return compact('status','data');
        
        } catch (Exception $e) {

            $status = false;
            $data = "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";

            return compact('status','data');
        }
    }
}
