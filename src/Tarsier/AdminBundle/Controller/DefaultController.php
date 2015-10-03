<?php

namespace Tarsier\AdminBundle\Controller;

use Tarsier\AdminBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tarsier\HomeBundle\Entity\user;

class DefaultController extends BaseController
{
    /**
     * @Route("/admin/index",name="adminindex")
     * @Template()
     */
    public function indexAction()
    {
        return array('name' => 'ddd');
    }

    /**
     * @Route("/login")
     * @Template()
     */
    public function loginAction()
    {
        $user=new user();
        $form=$this->createFormBuilder($user,['attr'=>['id'=>'form-signin']])
            ->add('username','text',['label_attr'=>['class'=>'sr-only'],'attr'=>['class'=>'form-control input-signin','placeholder'=>"Email address"]])
            ->add('password','password',['label_attr'=>['class'=>'sr-only'],'attr'=>['class'=>'form-control input-signin','placeholder'=>"password"]])
            ->add('Sign in','submit',['attr'=>['class'=>'btn btn-lg btn-primary btn-block']])
            ->getForm();

        $form->handleRequest($this->getRequest());

        if($form->isValid()){
            return $this->redirect($this->generateUrl('adminindex'));
        }

        $data=[
            'login_form'=>$form->createView(),
        ];

        return $data;
    }

}
