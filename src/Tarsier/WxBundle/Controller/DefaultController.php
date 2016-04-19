<?php

namespace Tarsier\WxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Tarsier\WxBundle\Controller\WxBaseController;
/**
 * @Route("/wx")
 */
class DefaultController extends WxBaseController
{



    /**
     * @Route("/hello/{name}",defaults={"name":"what's your name?"})
     * @Template()
     */
    public function indexAction($name)
    {
          $r=$this->valid();
          if($r!==false){
              $this->wxMain();
              echo $r;
          }
           else{
               echo "connect error";
           }
//        此处exit不可省去，因为修改配置最终会发送数据验证$r且最后$r必须输出
        exit;
//        return array('name' => $name);
    }



}
