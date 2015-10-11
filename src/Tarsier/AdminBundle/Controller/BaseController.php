<?php

namespace Tarsier\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BaseController extends Controller
{
    public function getEm($db='')
    {
        if($db=='')
            return $this->getDoctrine()->getManager();
        else
            return $this->getDoctrine()->getManager($db);
    }

    public function getRepository($table='user',$db='',$bundle='TarsierHomeBundle'){
        $className=$bundle.':'.$table;
        return $this->getEm($db)->getRepository($className);
    }

    public function getUserRepository(){
       return $this->getRepository('user');
    }

    public function getArticleRepository(){
        return $this->getRepository('article');
    }

    public function getArticleImgRepository(){
        return $this->getRepository('articleimg','sqlite');
    }

    public function getTagsRepository(){
        return $this->getRepository('tags');
    }

    public function getRssRepository(){
        return $this->getRepository('rss');
    }

    public function getFriendlinkRepository(){
        return $this->getRepository('friendlink');
    }

}
