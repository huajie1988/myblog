<?php

namespace Tarsier\AdminBundle\Controller;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Cookie;
use Tarsier\AdminBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Tarsier\HomeBundle\Entity\article;
use Tarsier\HomeBundle\Entity\articleimg;
use Tarsier\HomeBundle\Entity\qrrecord;
use Tarsier\HomeBundle\Entity\tags;
use Tarsier\HomeBundle\Entity\user;
use Doctrine\Common\Util\Debug;
use Tarsier\HomeBundle\Service\Common;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Tarsier\HomeBundle\Service\QRcode;
use Tarsier\HomeBundle\Service\QRencode;

class DefaultController extends BaseController
{

    private $qrcode_url="http://qr.liantu.com/api.php?text=";


    /**
     * @Route("/admin/index",name="adminindex")
     * @Template()
     */
    public function indexAction()
    {
        if(!$this->isLogin())
            return $this->redirect($this->generateUrl('adminlogin'));

        $ret = $this->get("database_connection")->fetchAll("SELECT COUNT(*) as nums,status FROM `Article` a GROUP BY a.status;");

        $article_status=[
            'draft'=>0,
            'release'=>0,
            'delete'=>0,
        ];
        foreach ($ret as $r) {

            if($r['status']==0)
                $article_status['draft']=$r['nums'];
            elseif($r['status']==1){
                $article_status['release']=$r['nums'];
            }elseif($r['status']==2){
                $article_status['delete']=$r['nums'];
            }else{
                $article_status['unknow']=$r['nums'];
            }

        }

        $hotArticle=$this->getArticleRepository()->getHotArticle();


        $data=[
            'userName'=>$this->getRequest()->cookies->get('userName'),
            'time'=>time(),
            'systemInfo'=>'System:'.php_uname().'<br />PHP Version:'.PHP_VERSION.'<br />PHP run mode:'.php_sapi_name(),
            'article_status'=>$article_status,
            'hotArticle'=>$hotArticle,
            'nav'=>'home',
        ];

        return $data;
    }

    /**
     * @Route("/admin/login",name="adminlogin")
     * @Template()
     */
    public function loginAction()
    {

        $this->qrcode_url="http://".$_SERVER['SERVER_NAME']."/admin/qrcodeService?text=";

        if($this->isLogin())
            return $this->redirect($this->generateUrl('adminindex'));

        $c=new Common();

        $user=new user();
        $form=$this->createFormBuilder($user,['attr'=>['id'=>'form-signin']])
            ->add('username','text',['label_attr'=>['class'=>'sr-only'],'attr'=>['class'=>'form-control input-signin','placeholder'=>"Email address"]])
            ->add('password','password',['label_attr'=>['class'=>'sr-only'],'attr'=>['class'=>'form-control input-signin','placeholder'=>"password"]])
            ->add('captcha','text',['label_attr'=>['class'=>'sr-only'],'attr'=>['class'=>'form-control input-signin','placeholder'=>"Captcha"]])
            ->add('Rememberme','checkbox',['attr'=>['class'=>'rememberme'],'required'=>0])
            ->add('Sign in','submit',['attr'=>['class'=>'btn btn-lg btn-primary btn-block']])
            ->getForm();

        $form->handleRequest($this->getRequest());

//        include $_SERVER["DOCUMENT_ROOT"]."/src/Tarsier/HomeBundle/Service/Phpqrcode.php";

        if($form->isValid()){

            $ret_form=$this->getRequest()->get('form');

            $code=$session=$this->getRequest()->getSession()->get('captcha');
            $uid = $this->getRequest()->getSession()->get('uid');

            if($ret_form['captcha']!=$code){
                $error=new FormError('The Captcha is not right!');
                $form->addError($error);
                $text=urlencode("http://".$_SERVER['SERVER_NAME']."/admin/scanningLoginAuto?uid=$uid&code=$code") ;
                $captcha_img='data:image/png;base64,'.base64_encode(file_get_contents($this->qrcode_url.$text));

                return [
                    'login_form'=>$form->createView(),
                    'captcha_img'=>$captcha_img,
                ];
            }

            $user_em = $this->getUserRepository();
            $user=$user_em->findOneBy(['username'=>$ret_form['username']]);
            if(!$user_em->checkUserValid($ret_form['username'],$ret_form['password'])){
                $error=new FormError('Username or password is error!');
                $form->addError($error);
                $text=urlencode("http://".$_SERVER['SERVER_NAME']."/admin/scanningLoginAuto?uid=$uid&code=$code") ;
                $captcha_img='data:image/png;base64,'.base64_encode(file_get_contents($this->qrcode_url.$text));

                return [
                    'login_form'=>$form->createView(),
                    'captcha_img'=>$captcha_img,
                ];
            }

            $token=md5($c->createRandStr());
//            $sequence=md5($c->createRandStr());

            $day=1;
            if(isset($ret_form['Rememberme']) && intval($ret_form['Rememberme'])>0){
                $day=14;
            }

            $expire=24*3600*$day;

            $response = new Response();
            $response->headers->setCookie(new Cookie('userName', $ret_form['username'], time() + $expire));
            $response->headers->setCookie(new Cookie('token', $token, time() + $expire));
            $response->headers->setCookie(new Cookie('sequence', $user->getSequence(), time() + $expire));
            $response->sendHeaders();

            $user->setToken($token);
//            $user->setSequence($sequence);
            $user->setIp($this->getRequest()->getClientIp());

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('adminindex'));
        }


        $code=$c->createRandStr();
        $uid=uniqid('lg_');
        $session=$this->getRequest()->getSession();

        $session->set('captcha',$code);
        $session->set('uid',$uid);

        $text=urlencode("http://".$_SERVER['SERVER_NAME']."/admin/scanningLoginAuto?uid=$uid&code=$code") ;
        $captcha_img='data:image/png;base64,'.base64_encode(file_get_contents($this->qrcode_url.$text));


        $data=[
            'login_form'=>$form->createView(),
            'captcha_img'=>$captcha_img,
        ];

        return $data;
    }

    /**
     * @Route("/admin/logout",name="adminlogout")
     * @Template()
     */

    public function logoutAction(){
        $response = new Response();
        $expire=3600;
        $response->headers->setCookie(new Cookie('userName', '', time() -$expire));
        $response->headers->setCookie(new Cookie('token', '', time() - $expire));
        $response->headers->setCookie(new Cookie('sequence', '', time() - $expire));
        $response->sendHeaders();
        return $this->redirect($this->generateUrl('adminlogin'));
    }

    /**
     * @Route("/article/list",name="articlelist")
     * @Template("TarsierAdminBundle:article:list.html.twig")
     */
    public function articleListAction()
    {
        if(!$this->isLogin())
            return $this->redirect($this->generateUrl('adminlogin'));

        $article_em=$this->getArticleRepository();
        $list_query=$article_em->findAllArticle();
        $page=$this->getRequest()->get("page",1);


        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $list_query,
            $page/*page number*/,
            10/*limit per page*/
        );

        $data=[
            'userName'=>$this->getRequest()->cookies->get('userName'),
            'nav'=>'article',
            'pagination' => $pagination,
        ];

        return $data;
    }

    /**
     * @Route("/article/add",name="articleadd")
     * @Template("TarsierAdminBundle:article:edit.html.twig")
     */
    public function articleAddAction()
    {
        if(!$this->isLogin())
            return $this->redirect($this->generateUrl('adminlogin'));

        $userName=$this->getRequest()->cookies->get('userName');

        $article=new article();
        $form=$this->createFormBuilder($article,['attr'=>['id'=>'form-save','class'=>'form-save']])
            ->add('title','text',['label_attr'=>['class'=>''],'attr'=>['class'=>'form-control input-signin','placeholder'=>"Article Title"]])
            ->add('status','choice',['choices' => ['0'=>'Draft','1'=>'Release','2'=>'Delete'],'label_attr'=>['class'=>''],'attr'=>['class'=>'form-control input-signin']])
            ->add('tag','text',['label_attr'=>['class'=>''],'attr'=>['class'=>'form-control input-signin']])
            ->add('top','choice',['choices' => ['0'=>'Not top article','1'=>'Is top article'],'label_attr'=>['class'=>''],'attr'=>['class'=>'form-control input-signin']])
            ->add('sort','integer',['label_attr'=>['class'=>''],'attr'=>['class'=>'form-control input-signin','placeholder'=>"Article Sort"]])
            ->add('front_cover','file',['label_attr'=>['class'=>''],'attr'=>['class'=>'form-control input-signin'],'required'=>0])
            ->add('thumb','file',['label_attr'=>['class'=>''],'attr'=>['class'=>'form-control input-signin'],'required'=>0])
            ->add('content','textarea',['label_attr'=>['class'=>''],'attr'=>['id'=>'editor','class'=>'form-control input-signin','placeholder'=>"Article Content"],'required'=>0])
            ->add('Save','submit',['attr'=>['class'=>'btn btn-lg btn-primary btn-block form-save-btn']])
            ->getForm();

        $form->handleRequest($this->getRequest());

        if($form->isValid()) {

            $front_cover='';
            if($form->get('front_cover')->getData()!=null){
                $front_cover_path=$form->get('front_cover')->getData()->getPathName();
                $front_cover_str=file_get_contents($front_cover_path);
                $front_cover=base64_encode($front_cover_str);
                $front_cover= Common::addBase64ImgHead($front_cover,'png');
            }



            $thumb='';
            if($form->get('front_cover')->getData()!=null && $form->get('thumb')->getData()== null){
                $src=imagecreatefromstring($front_cover_str);
                $size_src=getimagesize($front_cover_path);
                $w=$size_src['0'];
                $h=$size_src['1'];
                $max=300;
                if($w > $h){
                    $w=$max;
                    $h=$h*($max/$size_src['0']);
                }else{
                    $h=$max;
                    $w=$w*($max/$size_src['1']);
                }
                $image=imagecreatetruecolor($w, $h);
                imagecopyresampled($image, $src, 0, 0, 0, 0, $w, $h, $size_src['0'], $size_src['1']);
                ob_start();
                imagepng($image);
                // Capture the output
                $imagedata = ob_get_contents();
                // Clear the output buffer
                ob_end_clean();
                $thumb= Common::addBase64ImgHead(base64_encode($imagedata),'png');

            }elseif($form->get('thumb')->getData()!= null){
                $thumb=$form->get('thumb')->getData()->getPathName();
                $thumb=base64_encode(file_get_contents($thumb));
                $thumb= Common::addBase64ImgHead($thumb,'png');
            }


            $em=$this->getEm();
            $sem=$this->getEm('sqlite');
            $user=$this->getUserRepository()->findOneBy(['username'=>$userName]);

            $ret_form = $this->getRequest()->get('form');

            $tags=explode(',',str_replace('，',',',$ret_form['tag']));

            $tags_em = $this->getTagsRepository();
            $tags_list=[];
            foreach ($tags as $t) {
                $tag=$tags_em->findOneBy(['name'=>$t]);
                if(empty($tag)){
                    $tag =new tags();
                    $tag->setName($t);
                    $tag->setClick(1);
                }else{
                    $tag->setClick(count($tag->getArticle())+1);
                }

                $em->persist($tag);
                $em->flush();

                $tags_list[]=$tag;
            }







            $article = new article();
            $article->setTitle($ret_form['title']);
            $article->setCreateTime(time());
            $article->setSort($ret_form['sort']);
            $article->setStatus($ret_form['status']);
            $article->setTop($ret_form['top']);
            $article->setClick(0);
            foreach ($tags_list as $t) {
                $article->addTag($t);
            }
            $article->setContent($ret_form['content']);
            $article->setUser($user);
            $em->persist($article);
            $em->flush();


            $articleimg = new articleimg();
            $articleimg->setFrontCover($front_cover);
            $articleimg->setThumb($thumb);
            $articleimg->setArticleId($article->getId());
            $sem->persist($articleimg);
            $sem->flush();

           return $this->redirect($this->generateUrl('articlelist'));

        }

        $data=[
            'save_form'=>$form->createView(),
            'userName'=>$userName,
            'nav'=>'article',
            'id'=>0,
        ];

        return $data;
    }

    /**
     * @Route("/article/edit/id/{id}",name="articleedit")
     * @ParamConverter("article",class="TarsierHomeBundle:article")
     * @Template("TarsierAdminBundle:article:edit.html.twig")
     */
    public function articleEditAction(article $article)
    {

        if(!$this->isLogin())
            return $this->redirect($this->generateUrl('adminlogin'));

        $userName=$this->getRequest()->cookies->get('userName');

        $tags=$article->getTag();
        $tags_list_orgin=$tags;
        $tagStr=[];

        foreach ($tags as $t) {
            $tagStr[]=$t->getName();
        }

        $tagStr=join(',',$tagStr);


        $form=$this->createFormBuilder($article,['attr'=>['id'=>'form-save','class'=>'form-save']])
            ->add('title','text',['data'=>$article->getTitle(),'label_attr'=>['class'=>''],'attr'=>['class'=>'form-control input-signin','placeholder'=>"Article Title"]])
            ->add('status','choice',['data'=>$article->getStatus(),'choices' => ['0'=>'Draft','1'=>'Release','2'=>'Delete'],'label_attr'=>['class'=>''],'attr'=>['class'=>'form-control input-signin']])
            ->add('tag','text',['data'=>$tagStr,'label_attr'=>['class'=>''],'attr'=>['class'=>'form-control input-signin']])
            ->add('top','choice',['data'=>$article->getTop(),'choices' => ['0'=>'Not top article','1'=>'Is top article'],'label_attr'=>['class'=>''],'attr'=>['class'=>'form-control input-signin']])
            ->add('sort','integer',['data'=>$article->getSort(),'label_attr'=>['class'=>''],'attr'=>['class'=>'form-control input-signin','placeholder'=>"Article Sort"]])
            ->add('front_cover','file',['data'=>'','label_attr'=>['class'=>''],'attr'=>['class'=>'form-control input-signin'],'required'=>0])
            ->add('thumb','file',['data'=>'','label_attr'=>['class'=>''],'attr'=>['class'=>'form-control input-signin'],'required'=>0])
            ->add('content','textarea',['data'=>$article->getContent(),'label_attr'=>['class'=>''],'attr'=>['id'=>'editor','class'=>'form-control input-signin','placeholder'=>"Article Content"],'required'=>0])
            ->add('Save','submit',['attr'=>['class'=>'btn btn-lg btn-primary btn-block form-save-btn']])
            ->getForm();


        $form->handleRequest($this->getRequest());

        if($form->isValid()) {
            $article->setTag([]);
            $front_cover='';
            if($form->get('front_cover')->getData()!=null){
                $front_cover_path=$form->get('front_cover')->getData()->getPathName();
                $front_cover_str=file_get_contents($front_cover_path);
                $front_cover=base64_encode($front_cover_str);
                $front_cover= Common::addBase64ImgHead($front_cover,'png');
            }


            $thumb='';
            if($form->get('front_cover')->getData()!=null && $form->get('thumb')->getData()== null){

                $src=imagecreatefromstring($front_cover_str);
                $size_src=getimagesize($front_cover_path);
                $w=$size_src['0'];
                $h=$size_src['1'];
                $max=300;
                if($w > $h){
                    $w=$max;
                    $h=$h*($max/$size_src['0']);
                }else{
                    $h=$max;
                    $w=$w*($max/$size_src['1']);
                }
                $image=imagecreatetruecolor($w, $h);
                imagecopyresampled($image, $src, 0, 0, 0, 0, $w, $h, $size_src['0'], $size_src['1']);
                ob_start();
                imagepng($image);
                // Capture the output
                $imagedata = ob_get_contents();
                // Clear the output buffer
                ob_end_clean();
                $thumb= Common::addBase64ImgHead(base64_encode($imagedata),'png');

            }elseif($form->get('thumb')->getData()!= null){
                $thumb=$form->get('thumb')->getData()->getPathName();
                $thumb=base64_encode(file_get_contents($thumb));
                $thumb= Common::addBase64ImgHead($thumb,'png');
            }


            $em=$this->getEm();
            $sem=$this->getEm('sqlite');

            $ret_form = $this->getRequest()->get('form');
            $tags=explode(',',str_replace('，',',',$ret_form['tag']));
            $tags_em = $this->getTagsRepository();
            $tags_list=[];



            foreach ($tags as $t) {

                $tag=$tags_em->findOneBy(['name'=>$t]);
                if(empty($tag)){
                    $tag =new tags();
                    $tag->setName($t);
                    $tag->setClick(1);
                }else{
                    $tag->setClick(count($tag->getArticle())+1);
                }

                $em->persist($tag);
                $em->flush();

                $tags_list[]=$tag;
            }


            $article->setTag($tags_list);

            $em->persist($article);
            $em->flush();


            $articleimg_em=$this->getArticleImgRepository();

            $articleimg=$articleimg_em->findOneBy(['article_id'=>$article->getId()]);

            if ($articleimg == null)
                $articleimg = new articleimg();

            $frontcover_replace= $this->getRequest()->get('frontcover_replace');
            $thumb_replace= $this->getRequest()->get('thumb_replace');

            if($frontcover_replace==1)
                $articleimg->setFrontCover($front_cover);

            if($thumb_replace==1)
                $articleimg->setThumb($thumb);

            $articleimg->setArticleId($article->getId());
            $sem->persist($articleimg);
            $sem->flush();

            return $this->redirect($this->generateUrl('articlelist'));

        }



        $data=[
            'save_form'=>$form->createView(),
            'userName'=>$userName,
            'nav'=>'article',
            'id'=>$article->getId(),
        ];

        return $data;
    }

    /**
     * @Route("/admin/scanningLogin",name="adminScanningLogin")
     * @Template()
     */

    public function scanningLoginPollAction(){
        $uid=$this->getRequest()->getSession()->get('uid');
        $captcha=$this->getRequest()->getSession()->get('captcha');

        $record = $this->getQrRecordRepository()->findOneBy(['uid'=>$uid,'code'=>$captcha]);

        if(!empty($record)){
            $sem=$this->getEm('sqlite');
            $sem->remove($record);
            $sem->flush();
            echo json_encode(['status'=>200,'captcha'=>$captcha]);
        }else{
            echo json_encode(['status'=>404]);
        }
        exit();
    }

    /**
     * @Route("/admin/scanningLoginAuto",name="adminScanningLoginauto")
     * @Template()
     */

    public function scanningLoginAction(){
        $uid=$this->getRequest()->get('uid');
        $code=$this->getRequest()->get('code');
        $record = $this->getQrRecordRepository()->findOneBy(['uid'=>$uid,'code'=>$code]);

        if(empty($record)){

            $sem=$this->getEm('sqlite');
            $qr_record= new qrrecord();
            $qr_record->setUid($uid);
            $qr_record->setCode($code);
            $sem->persist($qr_record);
            $sem->flush();

            echo json_encode(['status'=>200]);
        }else{
            echo json_encode(['status'=>404]);
        }
        exit();
    }

    private function isLogin(){
        if($this->getRequest()->cookies->get('userName')==null)
            return false;
        else{
            $ret = $this->getUserRepository()->findOneBy(['username'=>$this->getRequest()->cookies->get('userName'),'token'=>$this->getRequest()->cookies->get('token')]);
            return empty($ret)?false:true;
        }
    }

    /**
     * @Route("/admin/qrcodeService",name="adminQrcodeService")
     * @Template()
     */

    public function QRCodeServiceAction(){
        $text=$this->getRequest()->get('text');
        QRcode::png($text,false,3,16,2);
        exit;
    }

}
