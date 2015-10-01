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


}