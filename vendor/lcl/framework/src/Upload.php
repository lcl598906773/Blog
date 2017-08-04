<?php
// Upload::uploadFile('icon',['savePath'=>'./upload']);
// echo Upload::$errorNumber;
// echo Upload::$errorMessage;
namespace lcl\framework;
class Upload
{
	static protected $savePath; //保存的路径
	static protected $randName = true ; //随机名
	static protected $datePath = true; 
	static protected $mime = ['image/png', 'image/jpeg', 'image/gif'];//mime
	static protected $extension = 'png';
	static protected $suff = ['png','jpeg', 'jpg', 'gif'];//允许上传
	static protected $maxSize = 2000000;
	static public $errorNumber = 0;
	static public $errorMessage = '成功';
	static protected $uploadInfo;
	static protected $pathName;

	static protected function setOption($options)
	{
		if (is_Array($options)) {
			$keys = get_class_vars(__CLASS__);
			foreach ($options as $key => $value) {
				//判断一下成员属性
				if (in_array($key, $keys)) {
					self::$$key = $value;
				}
			}
		}
	}
	static public function uploadFile($field,$options = null)
	{
		self::setOption($options);
		//1.检查路径
		if (!self::checkSavePath()) {
			return false;
		}
		//2检查上传信息
		if (!self::checkUploadInfo($field)) {
			return false;
		}
		//3检查error错误信息
		if (!self::checkUploadError()) {
			return false;
		}
		//4检查自定义的错误
		if (!self::checkAllowOption()) {
			return false;
		}
		//5检查是不是上传文件
		if (!self::checkUploadFile()) {
			return false;
		} 
		//6.拼接路径
		self::joinPathName();
		//7.移动文件
		if (!self::moveUploadFile()) {
			return false;
		}
		return self::$pathName;

	}
	static protected function moveUploadFile()
	{
		
		if (!move_uploaded_file(self::$uploadInfo['tmp_name'], self::$pathName)) {
			self::$errorNumber = -8;
			self::$errorMessage = '移动失败' ;
			return false;
		}
		return true;
	}
	static protected function joinPathName()
	{
		//路径
		self::$pathName = self::$savePath;
		if (self::$datePath) {
			//upload/2017/04/17/hdkjdhd.png
			self::$pathName .= date('Y/m/d/');
			if (!file_exists(self::$pathName)) {
				mkdir(self::$pathName, 0777,true);
			}
		}
		//名字
		if (self::$randName) {
			self::$pathName .= uniqid();
		} else {
			$info = pathinfo(self::$uploadInfo['name']);
			self::$pathName .=  $info['filename'];
		}
		//后缀
		self::$pathName .=  '.' . self::$extension;
	}
	static protected function checkUploadFile()
	{
		if(!is_uploaded_file(self::$uploadInfo['tmp_name'])) {
			self::$errorNumber = -7;
			self::$errorMessage = '不是上传的文件' ;
			return false;
		}
		return true;
	}
	static protected function checkAllowOption()
	{
		if (!in_array(self::$uploadInfo['type'], self::$mime)) {
			self::$errorNumber = -4;
			self::$errorMessage = '不是允许的mime类型' ;
			return false;
		}
		if (!in_array(self::$extension, self::$suff)) {
			self::$errorNumber = -5;
			self::$errorMessage = '不是允许的后缀' ;
			return false;
		}
		if (self::$uploadInfo['size'] > self::$maxSize) {
			self::$errorNumber = -6;
			self::$errorMessage = '超出规定的大小' ;
			return false;
		}
		return true;
	}	
	static protected function checkUploadError() 
	{
		if (!self::$uploadInfo['error']) {
			return true;
		}
		switch(self::$uploadInfo['error']) {
			case UPLOAD_ERR_INI_SIZE:
				self::$errorMessage = '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值';
				break;
			case UPLOAD_ERR_FORM_SIZE:
				self::$errorMessage = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值';
				break;
			case UPLOAD_ERR_PARTIAL:
				self::$errorMessage = '文件只有部分被上传';
				break;
			case UPLOAD_ERR_NO_FILE:
				self::$errorMessage = '没有文件被上传' ;
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				self::$errorMessage = '找不到临时文件';
				break;
			case UPLOAD_ERR_CANT_WRITE:
				self::$errorMessage = '文件写入失败';
				break;
		}
		self::$errorNumber = self::$uploadInfo['error'];
		return false;
	}
	static protected function checkUploadInfo($field)
	{
		if (empty($_FILES[$field])) {
			self::$errorNumber = -3;
			self::$errorMessage = '没有' . $field . '上传信息' ;
			return false;
		}
		self::$uploadInfo = $_FILES[$field];
		return true;
	}
	static protected function checkSavePath()
	{
		if (!is_dir(self::$savePath)) {
			self::$errorNumber = -1;
			self::$errorMessage = '保存的路径不存在';
			return false;
		}
		if (!is_writable(self::$savePath)) {
			self::$errorNumber = -2;
			self::$errorMessage = '保存的路径不可写';
			return false;
		}
		self::$savePath = rtrim(self::$savePath, '/') . '/';
		return true;
	}
	public function __get($name)
	{
		if ($name == 'errorNumber' || $name == 'errorMessage') {
			return $this->$name;
		}
	}
}