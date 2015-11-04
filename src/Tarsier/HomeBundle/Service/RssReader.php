<?php
/**
 * Created by PhpStorm.
 * User: Huajie
 * Date: 2015/9/28
 * Time: 17:38
 */

namespace Tarsier\HomeBundle\Service;


class rssReader {

    private $feed_url;
    private $article;
    private $rssTpl;


    public function __construct($feeds){
            if(is_array($feeds)){
                foreach ($feeds as $feed) {
                    $this->feed_url[]=$feed;
                }
            }else{
                $this->feed_url[]=$feeds;
            }
    }


    public function run(){

        foreach ($this->feed_url as $fu) {
            $doc = new \DOMDocument();
            $doc->load( $fu );
            foreach ($doc->getElementsByTagName( "item" ) as $xc) {

                $art['title']= strval($xc->getElementsByTagName( "title" )->item(0)->nodeValue) ;
                $art['description']= strval($xc->getElementsByTagName( "description" )->item(0)->nodeValue) ;
                $art['link']= strval($xc->getElementsByTagName( "link" )->item(0)->nodeValue) ;
                $this->article[]=$art;
            }


        }

        return $this->article;

    }

    /**
     * create rss feed
     *
     * @param array $data
     * @return string
     */

    public function create($data){

        $search=['title','link','description','image','item'];
        foreach($data as $k=>$v){
            if (!in_array($k, $search)) {
                echo "The '$k' is illegal";
                exit;
            }
            $$k=$v;
        }

        $this->initTpl($title,$link,$description,$image);

        $itemTpl=[];

        $search=['title','link','description','pubDate'];

        foreach ($item as $i) {

            foreach ($search as $v) {
                if (!isset($i[$v])) {
                    echo "The '$v' isn't isset";
                    exit;
                }
            }


            $itemTpl[]=sprintf("<item>
                          <title>%s</title>
                          <link>%s</link>
                          <description>%s</description>
                          <pubDate>%s</pubDate>
                        </item>",$i['title'],$i['link'],$i['description'],$i['pubDate']);
        }
        $itemTpl=implode('',$itemTpl);

        $this->rssTpl=str_replace('{%item%}',$itemTpl,$this->rssTpl);
        return $this->rssTpl;
    }

    private function initTpl($title,$link,$description,$image=array()){
        $this->rssTpl='<?xml version="1.0" encoding="utf-8"?>
            <rss version="2.0">
              <channel>
                <title>'.$title.'</title>
                <link>'.$link.'</link>
                <description>'.$description.'</description>
                {%image%}
                {%item%}
              </channel>
            </rss>';

        $imageTpl='';

        if(!empty($image)){
            $imageTpl='<image>
                  <url>'.$image['url'].'</url>
                  <title>'.$image['title'].'</title>
                  <link>'.$image['link'].'</link>
                </image>';
        }

        $this->rssTpl=str_replace('{%image%}',$imageTpl,$this->rssTpl);

    }


}