<?php
/**
 * Created by PhpStorm.
 * User: Huajie
 * Date: 2015/9/28
 * Time: 9:49
 */

namespace Tarsier\HomeBundle\Twig;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
            new \Twig_SimpleFilter('uncp', array($this, 'gzuncompressFilter')),
            new \Twig_SimpleFilter('addBase64ImgHead', array($this, 'addBase64ImgHead')),
        );
    }

    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }

    public function gzuncompressFilter($data){
        return gzuncompress($data);
    }

    public function addBase64ImgHead($img,$img_type='jpg'){
        return "data:image/$img_type;base64,".$img;
    }

    public function getName()
    {
        return 'app_extension';
    }

}