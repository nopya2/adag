<?php
// src/Service/Functions.php
namespace App\Service;

use App\Service\Functions;

class Functions
{

    public function generatePassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function encryptPassword($password)
    {
        $options = [
            'cost' => 12,
        ];
        // $encryptedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
        $encryptedPassword = md5($password);
        return $encryptedPassword;
    }

    public function sendMail($from, $senderID, $to, $message_txt, $sujet){
        
    }

    public function setFirstItemIndex($page, $limit){
        if(!isset($page) || $page == 1){
            return 1;
        }else{
            return (($page-1)*$limit) + 1;
        }
    }

    public function setLastItemIndex($page, $limit, $firtItemIndex, $total){
        if($limit >= $total){
            return $total;
        }
        if(!isset($page) || $page == 1){
            return $limit;
        }
        else{
            $value = $firtItemIndex + ($limit - 1);
            if($diff = $total - $value < 0)
                return $total;
            return $value;
        }
    }

    public function tableauDates($periode){
        $tableau = explode(" - ", $periode);
        return $tableau;
    }

    public function genererNumCompte($compteClientRepos){
        $dernierCompte = $compteClientRepos->getLast();
        if(empty($dernierCompte))
            return $compte = 6800560017;
        else
            return $dernierCompte->getNumero() + 1;
    }

    public function genererNumTransaction($transactionRepos){
        $derniere = $transactionRepos->getLast();
        if(empty($derniere))
            return $numero = 100000001;
        else
            return $derniere->getNumero() + 1;
    }

    public function genererNumCommande($commandeRepos){
        $derniere = $commandeRepos->getLast();
        if(empty($derniere))
            return $numero = 205002001;
        else
            return $derniere->getNumero() + 1;
    }

    public function formatMontant($montant){
        $number = strval($montant);
        $array = str_split ( $number );

        $res = "";
        $length = count($array);
        $compteur = 0;
        for($i=$length-1; $i >= 0; $i--){
            $res = $array[$i] . $res;
            $compteur++;
            if(($compteur % 3) == 0){
                $res = " ". $res;
            }
            if($i < 0 || $i >= $length)
                break;
        }

        return $res;
    }

    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

}