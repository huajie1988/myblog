<?php

namespace Tarsier\WxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tarsier\AdminBundle\Controller\BaseController;

class WxBaseController extends BaseController
{
    const TOKEN="Huajie1988Weixin";
    const ACTION_NONE=0;
    const ACTION_FIRST=1;
    protected $fromUsername;
    protected $toUsername;
    protected $keyword;
    protected $msgType;
    protected $event;
    protected $responseList;


    protected function valid()
    {
        $echoStr = $this->getRequest()->get('echostr');

        //valid signature , option
        if($this->checkSignature()){
            return $echoStr;
            exit;
        }
        
        return false;
    }

    protected function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = file_get_contents('php://input', 'r');

        //extract post data
        if (!empty($postStr)){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
            if(!empty( $keyword ))
            {
                $msgType = "text";
                $contentStr = "Welcome to wechat world!";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }else{
                echo "Input something...";
            }

        }else {
            echo "";
            exit;
        }
    }

    private function checkSignature()
    {
        // you must define TOKEN by yourself



        if (!defined("self::TOKEN")) {
            throw new \Exception('TOKEN is not defined!');
        }

        $signature = $this->getRequest()->get('signature');
        $timestamp = $this->getRequest()->get('timestamp');
        $nonce = $this->getRequest()->get('nonce');

        $token = self::TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        $logger = $this->get('logger');
        $logger->error($signature.'==='.$tmpStr);
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    protected function wxMain(){
        $postStr = file_get_contents('php://input', 'r');
        $postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );

        $this->fromUserName = $postObj->FromUserName;
        $this->toUserName = $postObj->ToUserName;
        $this->msgType = trim ( $postObj->MsgType );
        $this->keyword = trim($postObj->Content);

        $events=$this->dealEvent($this->msgType,$postObj);

        $this->event=$events['Event'];
        $this->keyword=$events['EventKey'];

        $logger = $this->get('logger');
        $logger->error( $this->event.'==='.$this->keyword);

        if($this->event=='event'){
            $this->weixin_addresponse("这是一个点击事件");
        }elseif($this->event=='text'){
            $this->weixin_addresponse($this->keyword);
        }else{
            $this->weixin_addresponse($this->keyword);
        }

        $this->weixin_response ();

    }

    private function dealEvent($msgType,$postObj){
        $Event='';
        $EventKey='';

        switch ($msgType){
            case "event":
                $Event = trim ( $postObj->Event );
                $EventKey = trim ( $postObj->EventKey );
                break;
            case "text":
                $Event = "text";
                $EventKey = $postObj->Content;
                break;
            case "voice":
                $Event = "text";
                $EventKey = $postObj->Recognition;
                break;
//            case "image":
//                break;
//            case "video":
//                break;
//            case "shortvideo":
//                break;
//            case "location":
//                break;
//            case "link":
//                break;
            default:
                $Event = "unknow";
                $EventKey = "我暂时还不能处理这些，也许以后会有的";
                break;
        }

        return ['Event'=>$Event,'EventKey'=>$EventKey,];

    }


    private function weixin_addresponse($title = "", $url = "", $description = "", $picurl = "") {
        if (count ( $this->responseList ) == 10)
            return;
        $this->responseList [] = array ("title" => $title, "description" => $description, "url_pic" => $picurl, "url" => $url, "issimple" => ($description == "" && $url == "" && $picurl == "") );
    }

    private function weixin_response() {
        $issimple = true;
        if (count ( $this->responseList ) == 0)
            $this->weixin_addresponse ( $this->keyword );
        else {

            foreach ($this->responseList as $v) {
                if(!$v['issimple']){
                    $issimple = false;
                    break;
                }
            }
        }

        if ($issimple)
            $resultStr = $this->weixin_response_text ();
        else
            $resultStr = $this->weixin_response_article ();
        echo ($resultStr);

        $this->responseList=[];

        return;
    }

    /**
     * @brief 文本响应信息模板
     */

    private function weixin_response_text() {
        $time = time ();
        $msgType = "text";
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";

        $contentStr = "";
        foreach ($this->responseList  as $v) {
            $contentStr .= $v["title"];
        }

        $resultStr = sprintf ( $textTpl, $this->fromUserName, $this->toUserName, $time, $msgType, $contentStr );
        return $resultStr;
    }

    /**
     * @brief 新闻响应信息模板
     */

    private function weixin_response_article() {
        $time = time ();
        $msgType = "news";
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <ArticleCount>%s</ArticleCount>
                    <Articles>%s</Articles>
                    </xml>";
        $itemTpl = "<item>
                    <Title><![CDATA[%s]]></Title>
                    <Description><![CDATA[%s]]></Description>
                    <PicUrl><![CDATA[%s]]></PicUrl>
                    <Url><![CDATA[%s]]></Url>
                    </item>";

        $itemxmls = "";
        $itemxml = "";
        $count = count ( $this->responseList );
        foreach ($this->responseList as $v){
            $Title = $v["title"];
            $Content = $v["description"];
            $picurl = $v["url_pic"];
            $url = $v["url"];
            $itemxml = sprintf ( $itemTpl, $Title, $Content, $picurl, $url );
            $itemxmls = $itemxmls . $itemxml;
        }

        $resultStr = sprintf ( $textTpl, $this->fromUserName, $this->toUserName, $time, $msgType, $count, $itemxmls );
        return $resultStr;
    }


}
