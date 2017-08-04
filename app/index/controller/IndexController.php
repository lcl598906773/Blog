<?php
namespace index\controller;
use index\controller\BaseController;
use index\model\Article;
use index\model\User;
use index\model\Comment;
use index\model\Reply;
use lcl\framework\SaeTOAuthV2;
use lcl\framework\SaeTClientV2;

class IndexController extends BaseController
{
	protected $blog;
	protected $user;
	protected $comment;
	protected $reply;
	public function _init()
	{

		$this->blog = new Article();
		$this->user = new User();
		$this->comment = new Comment();
		$this->reply = new Reply();
	}
	public function index()
	{
		include 'config/config.php';
		if (!empty($_COOKIE['accesstoken'])){
			$o = new SaeTClientV2(WB_KEY,WB_SEC,$_COOKIE['accesstoken']);
			$res = $o->show_user_by_id($_SESSION['uid']);
			$_SESSION['username'] = $res['name'];
		}
		
		$data = $this->blog->blogList();
		$this->assign('data', $data);
		
		$this->display();
	}
	public function about()
	{
		$this->display();
	}
	public function gallery()
	{
		$this->display();
	}
	public function contacts()
	{
		$data = $this->user->getById('1');
		$this->assign('data', $data);
		$this->display();
	}
	public function docontacts()
	{
		if (empty($_POST['name'])) {
			exit("<script>alert('请输入姓名');window.location.href='index.php?m=index&c=index&a=contacts'</script>");
		}
		$data['name'] = trim($_POST['name']);
		$data['email'] = trim($_POST['email']);
		$data['comments']= $_POST['comments'];
		if($this->comment->insertComment($data)){
			exit("<script>alert('发送成功');window.location.href='index.php?m=index&c=index&a=contacts'</script>");
		}
	}
	public function blog_details()
	{
		if (empty($_GET['id'])) {
			exit("<script>alert('非法操作！');window.location.href='index.php'</script>");
		}
		$id = (int)$_GET['id'];
		$data = $this->blog->where("id=$id")->select();
		$sql = "select a.name,b.content,b.replytime,b.authorid from blog_reply as b left join blog_user as a on a.id=b.authorid where tid=$id";
		$result = $this->reply->query($sql,MYSQLI_ASSOC);
		$this->assign('data', $data);
		$this->assign('result', $result);
		$this->display();
	}
	public function leaveComment()
	{
		if (empty($_SESSION['uid'])) {
			exit("<script>alert('请先登录');window.location.href='index.php?m=index&c=index&a=blog_details'</script>");
		}
		$data['tid'] = $_GET['id'];
		$data['authorid'] = $_SESSION['uid'];
		$data['content']  = $_POST['message'];
		$data['replytime'] = time();
		if($this->reply->insert($data)){
			$tid = $_GET['id'];
			$this->blog->where("id=$tid")->setInc('replycount',1);
			$this->notice('回复成功！');
		}else{
			exit("<script>alert('回复失败！');window.location.href='index.php?m=index&c=index&a=blog_details'</script>");
		}
	}
	public function wblogin()
	{
		include 'config/config.php';
		include 'vendor/lcl/framework/src/saetv2.ex.class.php';

		$o = new SaeTOAuthV2(WB_KEY, WB_SEC);

		$oauth = $o->getAuthorizeURL(CALLBACK);
		header('Location: ' . $oauth);
	}
	public function callback()
	{
		include 'config/config.php';
		include 'vendor/lcl/framework/src/saetv2.ex.class.php';

		$code = $_GET['code'];
		$keys['code'] = $code;
		$keys['redirect_uri'] = CALLBACK;

		$o = new SaeTOAuthV2(WB_KEY, WB_SEC);

		$auth = $o->getAccessToken($keys);
		setcookie('accesstoken', $auth['access_token'],time()+86400);
		$_SESSION['uid'] = $auth['uid'];
		header('Location: index.php');
	}
}