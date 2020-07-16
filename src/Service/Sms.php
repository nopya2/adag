<?php
// src/Service/Functions.php
namespace App\Service;

class Sms
{
    const API_KEY = '07e533af';
    const API_SECRET = 'e558eeec2b6ed1ca';
    const SENDER_ID = 'ECOMMERCE';

    public function send($to, $text){

        $client = new \Nexmo\Client(new \Nexmo\Client\Credentials\Basic(self::API_KEY, self::API_SECRET));

        $pos1 = strpos($to, '00241');
        $pos2 = strpos($to, '+241');
        if($pos1 === FALSE && $pos2 === FALSE){
            $to = '+241'.$to;
        }
        
        $message = $client->message()->send([
            'to' => $to,
            'from' => self::SENDER_ID,
            'text' => $text
        ]);
    }
    
}