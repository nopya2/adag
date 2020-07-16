<?php
// src/Twig/AppExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
            new TwigFilter('truncate', [$this, 'truncateText']),
            new TwigFilter('decima', [$this, 'decimaFormat']),
            new TwigFilter('phone', [$this, 'formatNumberPhone'])
        ];
    }

    public function formatPrice($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }

    public function truncateText($text, $chars = 120) {
        if(strlen($text) > $chars) {
            $text = $text.' ';
            $text = substr($text, 0, $chars);
            // $text = substr($text, 0, strrpos($text ,' '));
            $text = $text.'...';
        }
        return $text;
    }

    public function formatNumberPhone($phone){
      $pos1 = strpos($phone, '+241');
      $pos2 = strpos($phone, '00241');

      if($pos1 !== false){
        $parts = explode('+241', $phone);
        return '(+241) '.$parts[1];
      }
      if($pos2 !== false){
        $parts = explode('00241', $phone);
        return '(+241) '.$parts[1];
      }
      return '(+241)'.$phone;
    }
}