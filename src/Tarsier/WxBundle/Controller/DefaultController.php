<?php

namespace Tarsier\WxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
/**
 * @Route("/wx")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}",defaults={"name":"what's your name?"})
     * @Method("GET")
     * @Template()
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }
}
