<?php
namespace admin\controller;
use lcl\framework\Template;

class BaseController extends Template
{
	public function __construct()
	{
		parent::__construct('cache/admin','app/admin/view');
		$this->_init();
	}

	//子类的初始化方法
	public function _init()
	{
		if (empty($_SESSION['uid'])) {
			header('index.php?m=admin&c=user&a=login');
		}
	}

	public function display($viewFile=null,$isExtract=true)
	{
		if (empty($viewFile)) {
			$viewFile = $_GET['c'] . '/'. $_GET['a'].'.html';
		}
		parent::display($viewFile,$isExtract);
	}
	public function notice($con, $url = null, $sec = 3)
	{
		if (empty($url)) {
			$url = $_SERVER['HTTP_REFERER'];
		}
		$this->assign('con', $con);
		$this->assign('url', $url);
		$this->assign('sec', $sec);
		$this->display('notice.html');
	}
}