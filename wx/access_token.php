<?php
include 'curl.php';
//个人
// define('APPID','wx20e2d977799aeaf9');
// define('SECRET','389c9a307889018ef493530868a89f69');
//测试账号
define('APPID','wx136f0737044d3b3d');
define('SECRET','9a7812f1965a789baaf4f792e7804d07');
define('TOKENFILE','token.txt');


getToken();


function getToken()
{
	$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . APPID . "&secret=" . SECRET;
	//1、检测是否有本地缓存文件
	if (file_exists(TOKENFILE)) {
		//判断是否过期
		if (filemtime(TOKENFILE)+7200>time()) {
			$content = file_get_contents(TOKENFILE);
			$obj = json_decode($content);
			return $obj->access_token;
		}else{
			//重新发起请求
			return requireToken($url);
		}
	}else{
		//2.如果没有，直接发起请求，获取access_token
		return requireToken($url);
	}
}
function requireToken($url)
{
	$content = MyCurl::get($url);
	file_put_contents(TOKENFILE, $content);
	$obj = json_decode($content);
	return $obj->access_token;
}
