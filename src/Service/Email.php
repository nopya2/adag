<?php
// src/Service/Functions.php
namespace App\Service;

class Email
{
    const MAILER_HOST = 'mail11.lwspanel.com';
    const MAILER_PORT = 587;
    const USERNAME = 'infos@neosystechnologie.ga';
    const PASSWORD = 'Azerty2018!';
    const SENDERID = 'NEOSYS TECHNOLOGIE';

    public function send($sujet, $destinataire, $renderView){

        // Mail transport GMAIL
        // $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
        //   ->setUsername('nopya2@gmail.com')
        //   ->setPassword('otogue86')
        // ;

        $transport = (new \Swift_SmtpTransport(self::MAILER_HOST, self::MAILER_PORT, null))
          ->setUsername(self::USERNAME)
          ->setPassword(self::PASSWORD)
        ;
        $mailer = new \Swift_Mailer($transport);
        $message = (new \Swift_Message($sujet))
            ->setFrom([self::USERNAME => self::SENDERID])
            ->setTo($destinataire)
            ->setBody($renderView, 'text/html')
        ;

        $mailer->send($message);
    }
    
}