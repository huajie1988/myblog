<?php
/**
 * Created by PhpStorm.
 * User: Huajie
 * Date: 2015/9/27
 * Time: 20:52
 */

namespace Tarsier\HomeBundle\Service;

class Common {

    function HelloWord($value){
        echo 'hello'.$value;
    }

    public function getCurrentMonth(){
        $timestamp=time();
//        $timestamp=strtotime('2016-08-21');
        $firstWeek=date('w', strtotime(date("Y-m-01",$timestamp)));

        $current_month=array(
            'year'=>date('Y',$timestamp),
            'month'=>date('m',$timestamp),
            'days'=>date("t",$timestamp),
            'day'=>date("d",$timestamp),
            'firstWeek'=>($firstWeek-1>0?$firstWeek-1:0),
        );

        return $current_month;

    }

    public function createRandStr($num=6){
        $string="qwertyuioplkjhgfdsazxcvbnm0987654321ZXCVBNMLKJHGFDSAQWERTYUIOP!@#$%";

        $code='';

        for($i=0;$i<$num;$i++){
            $code[]=$string[mt_rand(0,strlen($string)-1)];
        }

        $code=join('',$code);

        return $code;

    }

    public static function addBase64ImgHead($img,$img_type='jpg',$auto=true){

        if($auto)
            $img_type="image/$img_type";

        return "data:$img_type;base64,".$img;
    }

}