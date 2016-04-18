<?php

namespace Tarsier\MosquitoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/mosquito")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/search/{key}",defaults={"key":"Do you want search empty?"})
     * @Method("GET")
     * @Template()
     */
    public function indexAction($key)
    {
        return array('name' => $key);
    }
}
