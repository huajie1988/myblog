<?php

namespace Tarsier\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tarsier\HomeBundle\Entity\rss;
use Tarsier\HomeBundle\Entity\user;
use Tarsier\HomeBundle\Entity\article;
use Tarsier\HomeBundle\Entity\userprofile;
use Tarsier\HomeBundle\Service\Common;
use Tarsier\HomeBundle\Service\RssReader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class DefaultController extends Controller
{

    private $nav_arr=['home','technology','food','acg'];

    /**
     * @Route("/",name="indexPage")
     * @Template()
     */
    public function indexAction()
    {

        $INDEX_NUMS=3;

        $sem=$this->getDoctrine()->getManager('sqlite');
        $em=$this->getDoctrine()->getManager();

        $c=new Common();
        $current_month=$c->getCurrentMonth();

        $rssArticle=$this->getRss($em);
        $tags=$this->getTags($em);
        $friendlink=$this->getFriendLink();

        /**
         * @var $topArticle \Tarsier\HomeBundle\Entity\article
         */
        $topArticle = $em->getRepository('TarsierHomeBundle:article')->findTopArticle();

        if(isset($topArticle[0])){
            $topArticle=$topArticle[0];
            $topArticle=$this->setImg($topArticle,$sem);
        }else{
            $topArticle=null;
        }



        $indexArticle=null;
        if($topArticle!==null){
            $indexArticle = $em->getRepository('TarsierHomeBundle:article')->findIndexArticle($topArticle->getId(),$INDEX_NUMS);

            foreach ($indexArticle as $ia) {
                $ia=$this->setImg($ia,$sem);
            }

        }


        $data['topArticle']=$topArticle;
        $data['indexArticle']=$indexArticle;
        $data['current_month']=$current_month;
        $data['rssArticle']=$rssArticle;
        $data['tags']=$tags;
        $data['nav_tags']='home';
        $data['friendlink']=$friendlink;


        return $data;
    }

    // 要使用默认值需去掉最后一个/

    /**
     * @Route("/detail/id/{id}",defaults={"id":1},requirements={"id"="\d+"})
     * @ParamConverter("article",class="TarsierHomeBundle:article")
     * @Method("GET")
     * @Template()
     */

    public function detailAction(article $article){

        $data['article']=$article;

        if($this->getRequest()->cookies->get('userName')==null && $article->getStatus()!=1)
            return $this->redirect($this->generateUrl('indexPage'));

        $tagsArr=[];

        foreach ($article->getTag() as $tag) {
            $tagsArr[]=$tag->getName();
        }

        if(empty($tagsArr)){
            $tagsArr[]='detail';
        }

        $c=new Common();
        $current_month=$c->getCurrentMonth();

        $em=$this->getDoctrine()->getManager();
        $tags=$this->getTags($em);

        $hot_article = $em->getRepository("TarsierHomeBundle:article")->getHotArticle();

        $nav_tags = array_intersect($tagsArr, $this->nav_arr);
        $data['current_month']=$current_month;
        $data['tagsArr']=$tagsArr;  //面包屑导航
        $data['tags']=$tags;        //标签云
        $data['sid']=md5($article->getTitle().'http://huajie1988.net/detail/id/'.$article->getId());
        $data['nav_tags']=current($nav_tags);//导航栏
        $data['hot_article']=$hot_article;//导航栏
        $data['friendlink']=$this->getFriendLink();
        $data['is_mobile']=$this->isMobile();
        $em=$this->getDoctrine()->getManager();
        $article->setClick($article->getClick()+1);
        $em->persist($article);
        $em->flush();

        return $data;
    }

    /**
     * @Route("/list/tags/{tags}",defaults={"tags":"technology"})
     * @Method("GET")
     * @Template()
     */

    public function listAction($tags){

        $nav_tags=$this->getRequest()->get('tags');
        $nav_tags=strip_tags($nav_tags);
        $em=$this->getDoctrine()->getManager();

        $c=new Common();
        $current_month=$c->getCurrentMonth();

        $em=$this->getDoctrine()->getManager();
        $tags=$this->getTags($em);

        $page=$this->getRequest()->get("page",1);

        if(is_numeric($nav_tags)){
            $ret_tags=$em->getRepository("TarsierHomeBundle:tags")->find(intval($nav_tags));
            $nav_tags=$ret_tags->getName();
        }
        $list_query = $em->getRepository("TarsierHomeBundle:tags")->getArticleByName($nav_tags);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $list_query,
            $page/*page number*/,
            10/*limit per page*/
        );
//        使用原生SQL

//        $list=$this->get("database_connection")->fetchAll("SELECT	a.* FROM	`tags` t LEFT JOIN article_tags at ON t.id=`at`.tags_id LEFT JOIN article a ON a.id=`at`.article_id WHERE t.`name`='$nav_tags';");

        $hot_article = $em->getRepository("TarsierHomeBundle:article")->getHotArticle();


        $data=[
            'nav_tags'=>$nav_tags,
            'pagination' => $pagination,
            'current_month' => $current_month,
            'tags' => $tags,
            'hot_article'=>$hot_article,
            'friendlink'=>$this->getFriendLink(),
        ];
        return $data;
    }

    /**
     * @Route("/search/mode/{mode}",defaults={"mode":"Do you want search empty?"})
     * @Method("GET")
     * @Template()
     */

    public function searchAction($mode){

        $mode=$this->getRequest()->get('mode');
        $mode=strip_tags($mode);

        $search_mode_list=$this->dealmodeArgs($mode);

        if(empty($search_mode_list))
            return $this->redirect('/search/mode');

        if(!empty($search_mode_list['mosquito']))
            return $this->redirect('/');

        $em=$this->getDoctrine()->getManager();

        $c=new Common();
        $current_month=$c->getCurrentMonth();

        $em=$this->getDoctrine()->getManager();
        $tags=$this->getTags($em);

        $page=$this->getRequest()->get("page",1);

        $list_query = $em->getRepository("TarsierHomeBundle:article")->getArticleBySearch($search_mode_list);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $list_query,
            $page/*page number*/,
            10/*limit per page*/
        );
//        使用原生SQL

//        $list=$this->get("database_connection")->fetchAll("SELECT	a.* FROM	`tags` t LEFT JOIN article_tags at ON t.id=`at`.tags_id LEFT JOIN article a ON a.id=`at`.article_id WHERE t.`name`='$nav_tags';");

        $hot_article = $em->getRepository("TarsierHomeBundle:article")->getHotArticle();


        $data=[
            'nav_tags'=>'',
            'pagination' => $pagination,
            'current_month' => $current_month,
            'tags' => $tags,
            'hot_article'=>$hot_article,
            'friendlink'=>$this->getFriendLink(),
        ];
        return $data;
    }

    private function setImg($article,$sem){

        $articleImg=$sem->getRepository('TarsierHomeBundle:articleimg')->getArticleImgByArticleId($article->getId());
        if(isset($articleImg[0])){
            $articleImg=$articleImg[0];
            $article->setFrontCover(gzcompress($articleImg->getFrontCover()));
            $article->setThumb(gzcompress($articleImg->getThumb()));
        }

        return $article;
    }

    private function getRss($em){

        $MAX_UPDATE_TIME=24*60*60;


        /**
         * @var $rss \Tarsier\HomeBundle\Entity\rss
         */

        $rss=$em->getRepository('TarsierHomeBundle:rss')->findBy(array('status'=>1));

        $rss_article=[];
        $rand=mt_rand(0,count($rss)-1);
        if(isset($rss[$rand]) && time()-$rss[$rand]->getLastTime()<=$MAX_UPDATE_TIME){
            $rss_article=unserialize($rss[$rand]->getContent());
        }else{
            $url=isset($rss[$rand])?$rss[$rand]->getUrl():'http://news.ifeng.com/rss/index.xml';
            $rssReader=new RssReader($url);
            $rss_article=$rssReader->run();

            $rss[$rand]->setLastTime(time());
            $rss[$rand]->setContent(serialize($rss_article));
            $em->persist($rss[$rand]);
            $em->flush();
        }

        return $rss_article;

    }

    private function getTags($em){
        $tags = $em->createQuery(
           'SELECT t FROM TarsierHomeBundle:tags t ORDER BY t.click DESC'
        )->setMaxResults(20)
        ->getResult();

        $tags_arr=[];

        foreach ($tags as $tag) {
            $t['id']=$tag->getId();
            $t['name']=$tag->getName();
            $t['click']=$tag->getClick();
            $tags_arr[]=$t;
        }

        $spread = 1;
        $minValue=0;
        if(!empty($tags_arr)){
            $spread=($tags_arr[0]['click']-$tags_arr[count($tags_arr)-1]['click'])>0?($tags_arr[0]['click']-$tags_arr[count($tags_arr)-1]['click']):1;
            $minValue=$tags_arr[count($tags_arr)-1]['click'];
        }
        shuffle($tags_arr);

        return [
            'tags'=>$tags_arr,
            'spread'=>$spread,
            'minValue'=>$minValue,
        ];
    }
    /**
     * @Route("/list/getArticlePhoto")
     * @Method("GET")
     * @Template()
     */
    public function getArticlePhotoAction(){

        $ids=explode(',',$this->getRequest()->get("ids"));
        $type=$this->getRequest()->get("type");

        if(empty($ids)){
            echo json_encode(['is_err'=>true,'msg'=>"Don't Find id"]);
            exit;
        }

        if(trim($type)==''){
            $type='thumb';
        }

        $sem=$this->getDoctrine()->getManager('sqlite');

        $articleImgs=$sem->getRepository('TarsierHomeBundle:articleimg')->getArticleImgByArticleIds($ids);

        if(empty($articleImgs)){
            echo json_encode(['is_err'=>true,'msg'=>"Don't Find Image"]);
            exit;
        }

        $photos=[];
        foreach ($articleImgs as $articleImg) {
            if(strtolower($type)=='frontcover')
                $photo=$articleImg->getFrontCover();
            else
                $photo=$articleImg->getThumb();
            $photos[]=[
                'id'=>$articleImg->getArticleId(),
                'photo'=>$photo
            ];
        }

        echo json_encode(['is_err'=>false,'msg'=>'','data'=>$photos]);
        exit();
    }

    /**
     * @Route("/feed")
     * @Method("GET")
     * @Template()
     */
    public function rssAction(){
        $r=new rssReader('myrss');

        $em=$this->getDoctrine()->getManager();
        $article=$em->getRepository('TarsierHomeBundle:article')->findRssArticle(10);


        $items=[];
        foreach ($article as $a) {
            $items[]=[
                'title'=>$a['title'],
                'link'=>"http://".$_SERVER['SERVER_NAME']."/detail/id/".$a['id'],
                'description'=>htmlspecialchars($a['content']),
                'pubDate'=>gmdate('D, d M Y H:i:s T',$a['create_time']),
            ];
        }

        $data=[
            'title'=>'KumaCore',
            'link'=>"http://".$_SERVER['SERVER_NAME'],
            'description'=>'KumaCore|熊心 一个默默无闻的程序员闲的无聊于是搞了这么一个东西',
            'image'=>[],
            'item'=>$items
        ];
        print_r($r->create($data));
        exit();
    }


    private function getFriendLink(){
        return $this->getDoctrine()
            ->getManager()
            ->getRepository('TarsierHomeBundle:friendlink')
            ->findBy(['status'=>1],['sort'=>'DESC']);
    }

    private function isMobile(){
        $ua=$_SERVER['HTTP_USER_AGENT'];
        $agents = ["Android", "iPhone","SymbianOS", "Windows Phone", "iPad", "iPod"];

        $flag = false;

        foreach ($agents as $a) {
            if (strstr($ua,$a)!==false) {
                $flag = true;
                break;
            }
        }


        return $flag;

    }

    /*
     * 日期：2016年4月18日 10:23:42
     * 作者：Huajie
     * 备注理由：估摸着效率有些差，待优化
     *
     * 日期：2016年4月20日 11:43:16
     * 作者：Huajie
     * 备注理由：第一次优化
     *
     */
    private function dealmodeArgs($mode){

        if(!strstr(current(explode(' ',$mode)),':'))
            $mode='title:'.$mode;

        $mode=preg_replace('!((?:^|\s)(\w+:)|$)!','|$1',$mode);
        $mode = explode('|',$mode);
        $mode=array_filter($mode);

        $search_mode_list=['title'=>[],'tags'=>[],'user'=>[],'mosquito'=>[]];

        foreach ($mode as $v) {
            $tm=explode(':',$v);
            $tm[0]=trim($tm[0]);
            $ta=[];
            if(isset($search_mode_list[$tm[0]])){
                $vv=str_replace(['<','>','～','｀','＄','＾','＋','｜','＝','＜','＞','￥','×',],['<','>','~','`','$','^','+','|','=','<','>','￥','*',],trim($tm[1]));
                $vv=preg_replace("![\\pP+~$`^=]!" , "",$vv);
                $ta=preg_replace('!(?:^|\s)([^\s]+)!'," $tm[0] LIKE '%$1%'",explode(' ',$vv));
                $search_mode_list[$tm[0]]=array_filter(array_merge($search_mode_list[$tm[0]],$ta));
            }
        }

        return array_filter($search_mode_list);

    }

}
