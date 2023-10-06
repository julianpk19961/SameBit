<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

class CorreoHandler
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.office365.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = "thisAlertSam@outlook.com";
        $this->mail->Password = "samein123";
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = 587;
        $this->mail->setFrom($this->mail->Username, 'Alertas Samein');
    }

    public function enviarCorreo($destinatario, $asunto, $cuerpo, $archivoCSV)
    {
        try {
            $this->mail->addAddress($destinatario);
            $this->mail->Subject = $asunto;
            $this->mail->Body = $cuerpo;

            if ($archivoCSV) {
                $this->mail->addAttachment($archivoCSV['path'], $archivoCSV['name']);
            }
            // var_dump($this->mail);
            $this->mail->send();
        } catch (Exception $e) {
            echo "El correo no pudo ser enviado. Error de envío: {$this->mail->ErrorInfo}";
        }
    }
}
