<?php
include 'access_token.php';
include 'class.juhe.weather.php';

//define your token
// define("TOKEN", "lclwechat");
$wechatObj = new wechatCallbackapiTest();

if (!empty($_GET['echostr'])) {
    $wechatObj->valid();//第一次握手,执行一次
}else{
     $wechatObj->reponseMsg();
     $wechatObj->Menu();
}
class wechatCallbackapiTest
{
    protected $xingZuo = [
        '白羊座','金牛座',
        '双子座','巨蟹座',
        '狮子座','处女座',
        '天秤座','天蝎座',
        '射手座','摩羯座',
        '水瓶座','双鱼座',
    ];
	public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }
    public function reponseMsg()
    {
        if (phpversion()<7) {
            //获取来自微信服务器的消息
            $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        } else {//>=7.0
            $postStr = file_get_contents('php://input');
        }
        file_put_contents('1.txt',$postStr);
        $obj = simplexml_load_string($postStr);
        $to = (string)$obj->FromUserName;//发给
        $from =(string) $obj->ToUserName;
        $time = time().'';
        $type = $obj->MsgType;
        $content = trim($obj->Content);
        //获取用户opeaid

        if ($obj->EventKey == 'text') {
            // $this->responseText($obj);
            $this->responsepictext($obj);
        }else if ($obj->EventKey == 'image') {
            $this->responseImage($obj);
        }else if ($obj->EventKey == 'voice') {
            $this->responseVoice($obj);
        } else if ($obj->EventKey == 'rselfmenu_0_0') {
            $this->responseSao($obj);
        } else if ($obj->Event == 'subscribe') {
            $this->responseText($obj);
        }

        if ($type == 'location') {
            $this->responseLoc($obj);
        } else if ($type == 'image') {
            $this->responseWxpic($obj);
        }   

        if (mb_stristr($content,'1') !== false) {
            $this->weather($obj);
        }else if(mb_stristr($content,'2') !== false){
            $this->responseXingcode($obj);
        }else if (in_array($content,$this->xingZuo)) {
            $this->responseXing($obj,$content);
        }else if (mb_stristr($content,'3') !== false) {
            $this->responsename($obj);
        }else if (preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/',$content)) {
            $this->responsenameYs($obj,$content);
        }else{
            $this->responseText($obj);
        }
    }
    public function responsepictext($obj)
    {
        $url = 'http://blog.lclclouds.com/wx/test.jpg';
        $url1 = 'http://www.lclclouds.com/';
        $msg = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[news]]></MsgType>
                <ArticleCount>1</ArticleCount>
                <Articles>
                <item>
                <Title>我不是一个坏人！</Title> 
                <Description>你猜！</Description>
                <PicUrl>%s</PicUrl>
                <Url>%s</Url>
                </item>
                </Articles>
                </xml>";
        $msg = sprintf($msg,$obj->FromUserName,$obj->ToUserName,time().'',$url,$url1);
        echo $msg;
    }
    public function responsenameYs($obj, $name)
    {

        $content = urlencode($name);
        $url = 'http://m.1518.com/xingming_view.php?word='.$content.'&submit1=%C8%B7%B6%A8&FrontType=1';
        $data = MyCurl::get($url);
        $data = iconv("GBK","UTF-8//IGNORE",$data);
        file_put_contents('nametest.txt',$data);
        $name = urldecode($content);
        $reg1="/.*?<dd>(.*?)<\/dd>.*?/"; 
        $reg2="/.*?<strong>(.*?)<\/strong>.*?/";
        preg_match_all($reg1, $data, $matches);
        preg_match_all($reg2, $data, $gold);
        $res = $name . strip_tags($gold[0][0]). "\n总论：".strip_tags($matches[0][0]);  
        $msg = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <MsgId>6442143111226037724</MsgId>
                            </xml>";
            $msg = sprintf($msg,$obj->FromUserName,$obj->ToUserName,time().'',$res);
            echo $msg;  
    }
    public function responsename($obj)
    {
        $res = '请输入您想要查询的姓名？';  
        $msg = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <MsgId>6442143111226037724</MsgId>
                        </xml>";
        $msg = sprintf($msg,$obj->FromUserName,$obj->ToUserName,time().'',$res);
        echo $msg; 
    }
    public function responseXingcode($obj)
    {
        $res = "请输入您想要查询的星座？\n白羊座、金牛座、双子座\n巨蟹座、狮子座、处女座\n天秤座、天蝎座、射手座\n摩羯座、水瓶座、双鱼座";  
        $msg = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <MsgId>6442143111226037724</MsgId>
                        </xml>";
        $msg = sprintf($msg,$obj->FromUserName,$obj->ToUserName,time().'',$res);
        echo $msg;  
    }
    public function responseXing($obj,$content)
    {
        $key = array_search($content, $this->xingZuo) + 1;
        $url = 'http://astro.sina.cn/fortune/starent?type=day&ast='.$key.'&vt=4';
        $data = MyCurl::get($url);
        file_put_contents('xing.txt',$data);
        $regex4="/<div class=\"xz_cont\".*?>.*?<\/p>/ism";
        preg_match_all($regex4, $data, $matches);
        $name = trim(strip_tags($matches[0][0]));  
        $reg = "/<div class=\"xz_cont\".*?>.*?<\/div>/ism";
        preg_match_all($reg, $data, $matches2);
        $info = trim(strip_tags($matches2[0][1])); 
        $res = $name. "\n".$info;
        $msg = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <MsgId>6442143111226037724</MsgId>
                            </xml>";
        $msg = sprintf($msg,$obj->FromUserName,$obj->ToUserName,time().'',$res);
        echo $msg;  
        
    }
    public function weather($obj)//
    {
        $to = (string)$obj->FromUserName;//发给
        $this->getUserInfo($to);
        $content = file_get_contents('userinfo.txt');
        $data = json_decode($content);
        $city = $data->city;
        $appkey = '22288c7ec5b815abc83f8da19e80e601';
        $weather = new weather($appkey);
        $cityWeatherResult = $weather->getWeather($city);
        $data = $cityWeatherResult['result'];
        $res =  "城市：". $city ."\n温度：". $data['sk']['temp'] ."\n风向：".$data['sk']['wind_direction']."(".$data['sk']['wind_strength'].")\n湿度：".$data['sk']['humidity']. "\n穿衣指数：".$data['today']['dressing_index']." , ".$data['today']['dressing_advice']."\n紫外线强度：".$data['today']['uv_index']."\n舒适指数：".$data['today']['comfort_index']."\n洗车指数：".$data['today']['wash_index'];

        $msg = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <MsgId>6442143111226037724</MsgId>
                        </xml>";
        $msg = sprintf($msg,$obj->FromUserName,$obj->ToUserName,time().'',$res);
        echo $msg;  
    }
    
    public function Menu()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".getToken();
        $menuData['button'][0] = [
          "name"=>"素材",
          "sub_button"=>[
            [
               'type'=>"click",
               "name"=>"图文",
               'key'=>'text'
            ],
            [
               'type'=>"click",
               "name"=>"图片",
               'key'=>'image'
            ],
            [
               'type'=>"click",
               "name"=>"音频",
               'key'=>'voice'
            ]
          ]
        ];
        $menuData['button'][1] = [
          "name"=>"资源",
          "sub_button"=>[
            [
               'type'=>"view",
               "name"=>"搜索",
               'url'=>'http://www.soso.com/'
            ],
            [
               'type'=>"pic_weixin",
               "name"=>"微信相册发图",
               'key'=>'wxpic'
            ],
            [
               'type'=>"location_select",
               "name"=>"发送位置",
               'key'=>'loc'
            ]
          ]
        ];
        $menuData['button'][2] = [
          "type"=> "scancode_waitmsg", 
           "name"=>"扫码带提示", 
           "key"=>"rselfmenu_0_0", 
        ];
        //一级菜单
        $str = json_encode($menuData,JSON_UNESCAPED_UNICODE);
        echo MyCurl::post($url,$str);
    }
    public function responseWxpic($obj)
    {
         $msg = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            </xml>";
        $msg = sprintf($msg,$obj->FromUserName,$obj->ToUserName,time().'',"照片不错！");
        echo $msg;
    }
    public function responseLoc($obj)
    {
         $msg = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <MsgId>6442143111226037724</MsgId>
            </xml>";
        $msg = sprintf($msg,$obj->FromUserName,$obj->ToUserName,time().'',"你在这！");
        echo $msg;
    }

    public function responseSao($obj)
    {
        $msg = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <MsgId>6442143111226037724</MsgId>
            </xml>";
        $msg = sprintf($msg,$obj->FromUserName,$obj->ToUserName,time().'',"别扫了，没东西！");
            echo $msg;
    }
    public function responseText($obj)
    {
        $reply = "欢迎关注！\n回复数字1：查看天气状况！\n回复数字2：查看星座运势！\n回复数字3：姓名测试有惊喜！";
        $msg = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                <MsgId>6442143111226037724</MsgId>
                </xml>";  
        $resultStr = sprintf($msg, $obj->FromUserName,$obj->ToUserName, time().'', $reply);
        echo $resultStr;
    }

    public function responseImage($obj)
    {
        $id = 'CTvUKu4MK9p-_ULiwjFytFBMbu8B4NDVK0LLWWFf2VxLAyWGVDQnMUjZeEf3uIa7';
        $msg = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[image]]></MsgType>
                <Image>
                <MediaId>%s</MediaId>
                </Image>
                </xml>";
        $resultStr = sprintf($msg,$obj->FromUserName,$obj->ToUserName, time().'',$id);
        echo $resultStr;
    }

    public function getmeidaID()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".getToken()."&type=image";
        $imageName = 'test.jpg';
        $image = new CURLFile($imageName);
        $data = ['media'=>$image];
        echo  MyCurl::post($url,$data);
    }
    public function responseVoice($obj)
    {
        $reply = "aTNqCrjqJ1wPPIuMyl2R1Req8S-afCgd2YMyIhbE1PMtYsxIBQSIG1U7MCWlPD-w";
        $msg = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[voice]]></MsgType>
                <Voice>
                <MediaId>%s</MediaId>
                </Voice>
                </xml>";
        $resultStr = sprintf($msg,$obj->FromUserName,$obj->ToUserName, time().'',$reply);
        echo $resultStr;
    }
    public function getvoiceID()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".getToken()."&type=voice";
        $imageName = '8932.amr';
        $image = new CURLFile($imageName);
        $data = ['media'=>$image];
        echo  MyCurl::post($url,$data);
    }
    //获取用户基本信息
    public function getUserInfo($openid)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".getToken()."&openid=".$openid."&lang=zh_CN";
        file_put_contents('userinfo.txt', MyCurl::get($url));
    }
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}
?>