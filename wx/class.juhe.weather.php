<?php
// +----------------------------------------------------------------------
// | JuhePHP [ NO ZUO NO DIE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010-2015 http://juhe.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Juhedata <info@juhe.cn-->
// +----------------------------------------------------------------------
 
//----------------------------------
// 聚合数据天气预报接口请求类
//----------------------------------
class weather{
    private $appkey = false; //申请的聚合天气预报APPKEY
 
    private $cityUrl = 'http://v.juhe.cn/weather/citys'; //城市列表API URL
 
    private $weatherUrl = 'http://v.juhe.cn/weather/index'; //根据城市请求天气API URL
 
    private $weatherIPUrl = 'http://v.juhe.cn/weather/ip'; //根据IP地址请求天气API URL
 
    private $weatherGeoUrl = 'http://v.juhe.cn/weather/geo'; //根据GPS坐标获取天气API URL
 
    private $forecast3hUrl = 'http://v.juhe.cn/weather/forecast3h'; //获取城市天气3小时预报API URL
 
    public function __construct($appkey){
        $this->appkey = $appkey;
    }
 
    /**
     * 获取天气预报支持城市列表
     * @return array
     */
    public function getCitys(){
        $params = 'key='.$this->appkey;
        $content = $this->juhecurl($this->cityUrl,$params);
        return $this->_returnArray($content);
    }
 
    /**
     * 根据城市名称/ID获取详细天气预报
     * @param string $city [城市名称/ID]
     * @return array
     */
    public function getWeather($city){
        $paramsArray = array(
            'key'   => $this->appkey,
            'cityname'  => $city,
            'format'    => 2
        );
        $params = http_build_query($paramsArray);
        $content = $this->juhecurl($this->weatherUrl,$params);
        return $this->_returnArray($content);
    }
 
    /**
     * 根据IP地址获取当地天气预报
     * @param string $ip [IP地址]
     * @return array
     */
    public function getWeatherByIP($ip){
         $paramsArray = array(
            'key'   => $this->appkey,
            'ip'  => $ip,
            'format'    => 2
        );
        $params = http_build_query($paramsArray);
        $content = $this->juhecurl($this->weatherIPUrl,$params);
        return $this->_returnArray($content);
    }
 
    /**
     * 根据GPS坐标获取当地的天气预报
     * @param  string $lon [经度]
     * @param  string $lat [纬度]
     * @return array
     */
    public function getWeatherByGeo($lon,$lat){
        $paramsArray = array(
            'key'   => $this->appkey,
            'lon'  => $lon,
            'lat'   => $lat,
            'format'    => 2
        );
        $params = http_build_query($paramsArray);
        $content = $this->juhecurl($this->weatherGeoUrl,$params);
        return $this->_returnArray($content);
    }
 
    /**
     * 获取城市三小时预报
     * @param  string $city [城市名称]
     * @return array
     */
    public function getForecast($city){
        $paramsArray = array(
            'key'   => $this->appkey,
            'cityname'  => $city,
            'format'    => 2
        );
        $params = http_build_query($paramsArray);
        $content = $this->juhecurl($this->forecast3hUrl,$params);
        return $this->_returnArray($content);
    }
 
    /**
     * 将JSON内容转为数据，并返回
     * @param string $content [内容]
     * @return array
     */
    public function _returnArray($content){
        return json_decode($content,true);
    }
 
    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    public function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();
 
        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }
 
}
