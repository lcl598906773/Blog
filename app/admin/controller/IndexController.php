<?php
namespace admin\controller;
use admin\controller\BaseController;
use lcl\framework\Upload;
use admin\model\Article;
use admin\model\User;
use admin\model\Reply;
use admin\model\Comment;

class IndexController extends BaseController
{

	protected $article;
	protected $user;
	protected $rep;
	protected $guest;
	public function _init()
	{
		parent::_init();
		$this->article = new Article();
		$this->user = new User();
		$this->rep = new Reply();
		$this->guest = new Comment();
	}
	public function index()
	{
		$this->display();
	}
	public function pass()
	{
		if(!empty($_POST)){
			$pwd = trim($_POST['mpass']);
			$newpwd = md5(trim($_POST['newpass']));
			$renewpass = md5(trim($_POST['renewpass']));
			$res = $this->user->where("name='李成龙'")->select();
			if(!strcmp($res[0]['pwd'], $pwd)){
				exit("<script>alert('旧密码不正确');window.location.href='index.php?m=admin&c=index&a=pass'</script>");
			}
			$data = [
				'pwd' => $newpwd,
			];
			$result = $this->user->where("name='李成龙'")->update($data);
			if($result){
					header('location:index.php?m=admin&c=index&a=pass');
			}
		}
		$this->display();
	}
	public function book()
	{
		if(empty($_POST)){
		$sql = "select a.name,b.content,b.replytime,b.id,c.title,b.tid from blog_reply as b left join blog_user as a on a.id=b.authorid left join blog_article as c on b.tid=c.id";
			$res = $this->rep->query($sql,MYSQLI_ASSOC);
			$this->assign('res', $res);
			$this->display();
		}else{
			$arr = $_POST['id'];
			$tid = array_keys($arr);
			$id = array_values($arr);
			$id = join(',', $id);
			$result = $this->rep->where("id in ($id)")->delete();
			if($result){
				foreach ($tid as $key => $value) {
					$counarticle = $this->rep->where("tid=$value")->select();
					$numreply = count($counarticle);
					$res = $this->article->where("id=$value")->setdec('replycount', $numreply);
					if ($res) {
						header('location:index.php?m=admin&c=index&a=book');
					}
				}
			}
		}
	}
	public function guestbook()
	{
		if(empty($_POST)){
		$res = $this->guest->select();
				$this->assign('res', $res);
				$this->display();
		}else{
			$id = $_POST['id'];
			$id = join(',', $id);
			$result = $this->guest->where("id in ($id)")->delete();
			var_dump($result);
			if($result){
				header('location:index.php?m=admin&c=index&a=guestbook');
			}
		}

	}
	public function list()
	{
		if(empty($_POST)){
			$article = $this->article->field('id,title,addtime,replycount')->select();
			$this->assign('article',$article);
			$this->display();
		}else{
			$id = $_POST['id'];
			$id = join(',', $id);
			$result = $this->article->where("id in ($id)")->delete();
			if($result){
					header('location:index.php?m=admin&c=index&a=list');
			}
		}	
	}
	public function riji()
	{
		$this->display();
	}
	public function webinfo()
	{
		if(empty($_POST)){
			$result = $this->user->where('level=1')->select();
		$this->assign('result', $result);
		$this->display();
		}else{
			$phone = $_POST['phone'];
			$email = $_POST['email'];
			$weibo = $_POST['weibo'];
			$intro = $_POST['intro'];
			$data = [
				'phone' =>$phone,
				'email' =>$email,
				'weibo' =>$weibo,
				'intro' =>$intro,
			] ;
			$res = $this->user->where('level=1')->update($data);
			if($res){
					header('location:index.php?m=admin&c=index&a=webinfo');
			}
		}
	}
	public function post()
	{
		$con = htmlspecialchars($_POST["test-editormd-markdown-doc"]);
		$title = $_POST['title'];
		$path = Upload::uploadFile('file',['savePath'=>'public/upload']);
		$data['title'] = $title;
		$data['content'] = addslashes($con);
		$data['addtime'] = time();
		$data['icon'] = $path;
		$res = $this->article->insert($data);
		if($res){

			$this->notice('发表成功！');
		} else{
			exit("<script>alert('发表失败');window.location.href='index.php?m=admin&c=index&a=riji'</script>");
		}
	}
	public function edit()
	{
		$id = $_GET['id'];
		$res = $this->article->where("id=$id")->select();
		$this->assign('res', $res);
		$this->display();
	}
	public function editarticle()
	{
		$id = $_GET['id'];
		$content = $_POST['test-editormd-markdown-doc'];
		$data['content'] = addslashes($content);
		$result = $this->article->where("id=$id")->update($data);
		if ($result) {
			$this->notice('修改成功！','index.php?m=admin&c=index&a=list');
		}
	}
}
