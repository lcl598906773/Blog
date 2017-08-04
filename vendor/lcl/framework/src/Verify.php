<?php
namespace lcl\framework;

class Verify
{
	protected $width; //宽
	protected $height; //高
	protected $length;//验证码长度
	protected $img; //画布资源
	protected $code;//验证码
	protected $type;//格式
	public function __construct($width = 200, $height = 50, $length = 4, $type = 4)
	{
		$this->width  = $width;
		$this->height = $height;
		$this->length = $length;
		$this->type   = $type;
		$this->outPut();
	}
	public static function ver($width = 100, $height = 40, $length = 4, $type = 4)
	{
		$ver = new Verify($width, $height, $length, $type);
		return $ver->code;
	} 
	protected function outPut()
	{
		//创建画布
		$this->createImg();
		//画字符串
		$this->verCode();
		//干扰元素
		$this->setDisturb();
		//发送图片
		$this->sendImg();
	}

	//创建画布 填充背景颜色
	protected function createImg()
	{
		$this->img = imagecreatetruecolor($this->width, $this->height);
		$lightColor = $this->getColor(true);
		imagefill($this->img, 0, 0, $lightColor);
	}
	protected function getColor($isLight = false)
	{  
		$start = (int)$isLight * 128; //0 * 128  1*128
		$end = $start + 127;
		$red = mt_rand($start, $end);
		$green = mt_rand($start, $end);
		$blue = mt_rand($start, $end);
		return imagecolorallocate($this->img, $red, $green, $blue);
	}
	//画随机字符串
	protected function verCode()
	{
		$str = $this->randString();//1 字符串  数组
		$fontSize = $this->height / 2;
		is_array($str) ? $count = strlen($str['str']) : $count = strlen($str);
		$perWidth = $this->width / $count;
		$delta = $perWidth - $fontSize; 
		$offsetY = ($this->height + $fontSize) / 2;
		for ($i=0; $i < $count ; $i++) {
			$angle = mt_rand(-30, 30); 
			$color = $this->getColor();
			$offsetX = $i * $perWidth + $delta; 
			if ($this->type == 4) {
				imagettftext($this->img, $fontSize, $angle, $offsetX, $offsetY, $color, 'public/index/fonts/lxkmht.ttf', $str['str'][$i]);
			} else{
				imagettftext($this->img, $fontSize, $angle, $offsetX, $offsetY, $color, 'public/index/fonts/lxkmht.ttf', $str[$i]);
			}
			
		}
		

	}
	//干扰元素
	protected function setDisturb()
	{
		$total = $this->width * $this->height / 50;
		for ($i = 0; $i < $total; $i++) {
			$x = mt_rand(0, $this->width);
			$y = mt_rand(0, $this->height);
			$color = $this->getColor();
			imagesetpixel($this->img, $x, $y, $color);
		}
		for ($i=0; $i < 10; $i++) { 
			$color = $this->getColor();
			imageline($this->img, mt_rand(0, $this->width), mt_rand(0,$this->height) , mt_rand(0, $this->width), mt_rand(0,$this->height), $color);
		}
		
	}
	protected function randString()
	{
		switch ($this->type) {
			case 1://纯数字
				$str = $this->randNum();
				break;
			case 2://纯字母
				$str = $this->randAlpha();
				break;
			case 3://字母数字混合
				$str = $this->randMixed();
				break;
			case 4://加减乘
				$str = $this->randCom();
				break;
			default:
				$str = $this->randNum();
				break;
		}
		$this->type == 4 ? $this->code = $str['code'] : $this->code = $str;
		return $str;
	}
	protected function randCom()
	{
		// 3 + 2 = ? 4 * 3 = ?
		$str1 = mt_rand(0, 9);
		$str2 = mt_rand(0, 9);
		$arr = ['+', '-', '*'];
		$com = $arr[mt_rand(0,2)];
		$str = $str1 . $com . $str2 . '=?';
		switch ($com) {
			case '+':
				$re = $str1 + $str2;
				break;
			case '-':
				$re = $str1 - $str2;
				break;
			case '*':
				$re = $str1 * $str2;
				break;
		}
		return ['str' => $str, 'code' => $re];

	}
	protected function randNum()
	{
		$str = '1234567890';
		return substr(str_shuffle($str), 0, $this->length);
	}
	protected function randAlpha()
	{
		$arr = range('a', 'Z');
		$str = join('',$arr);
		return substr(str_shuffle($str), 0, $this->length);
	}
	protected function randMixed()
	{
		return substr(md5(mt_rand(9,99)), 0, $this->length);
	}
	protected function sendImg()
	{
		header('content-type:image/png');
		imagepng($this->img);
	}
	public function  __get($code)
	{
		return $this->code;
	}
	
}


