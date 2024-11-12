<?php

namespace App\Libraries;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SendMailFunction
{
    public static function SendJobMail($receiver,$name,$message,$subject)
    {
        //Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'mail.timetechri.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                  //Enable SMTP authentication
        $mail->Username   = 'career@timerni.com';                     //SMTP username
        $mail->Password   = 'Timerni@2020';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('career@timerni.com', 'Time research & Innovation');
        $mail->addAddress($receiver, $name);     //Add a recipient
        // $mail->addAddress('ellen@example.com');               //Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content

        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->send();
       return true;
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
    }
}
