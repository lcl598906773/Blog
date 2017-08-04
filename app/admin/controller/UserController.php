<?php
namespace admin\controller;
use admin\controller\BaseController;
use admin\model\User;

class UserController extends BaseController
{
	protected $user;
	public function _init()
	{
		parent::_init();
		$this->user = new User();
	}
	public function login()
	{
		$this->display();
	}
	public function dologin()
	{
		$username = $_POST['name'];
		$pwd = md5($_POST['password']);
		$data = $this->user->checkUser($username, $pwd);
		if ($data) {
			$_SESSION['uid'] = $data[0]['id'];
			$this->notice('登录成功！','index.php?m=admin&c=index&a=index');
		}else{
			exit("<script>alert('账号或密码错误');window.location.href='index.php?m=admin&c=user&a=login'</script>");
		}
	}
	public function logout()
	{
		unset($_SESSION);
		session_destroy();
		$this->notice('退出成功！', 'index.php');
	}
	public function user()
	{
		if(empty($_POST)){
				$result = $this->user->select();
				$this->assign('result', $result);
				$this->display();
		}else{
			$id = $_POST['id'];
			$id = join(',', $id);
			$result = $this->user->where("id in ($id)")->delete();
			if($result){
				header('location:index.php?m=admin&c=user&a=user');
			}
		}
	}
}