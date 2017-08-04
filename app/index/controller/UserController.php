<?php
namespace index\controller;
use lcl\framework\Verify;
use index\model\User;
use vendor\alidayu\TopClient;
use vendor\alidayu\AlibabaAliqinFcSmsNumSendRequest;

class UserController extends BaseController
{
	protected $user;
	public function _init()
	{

		$this->user = new User();
	}
	public function login()
	{
		if (!empty($_POST)) {
			if(empty($_POST['username'])){
				exit("<script>alert('请输入用户名');window.location.href='index.php?m=index&c=user&a=login	'</script>");
			}
			$username = trim($_POST['username']);
			$pwd = md5(trim($_POST['pwd']));
			$result = $this->user->checklogin($username, $pwd);
			if ($result && count($result[0])>0) {
				$this->notice('登录成功！','index.php');
				$_SESSION['uid'] = $result[0]['id'];
				$_SESSION['username'] = $result[0]['name'];
				$_SESSION['level'] = $result[0]['level'];
			} else{
				$this->notice('用户名或密码错误！');
			}
		}
		$this->display();
	}
	public function register()
	{
		if (!empty($_POST)) {
			//验证名字是否有重复
			$username = trim($_POST['username']);
			$name = $this->user->selectuser("name='$username'");
			if (mb_strlen($username) >= 12){
	 			exit("<script>alert('名字过长');window.location.href='index.php?m=index&c=user&a=register'</script>");
	 		}
			if($name){
				exit("<script>alert('名字已被注册');window.location.href='index.php?m=index&c=user&a=register'</script>");	
			}
			//验证密码
			$pwd = trim($_POST['pwd']);
			$repwd = trim($_POST['repwd']);
			if(strcmp($pwd,$repwd)){
				exit("<script>alert('密码不一致');window.location.href='index.php?m=index&c=user&a=register'</script>");	
			}
			if(strlen($pwd) < 6){
				exit("<script>alert('密码不够长');window.location.href='index.php?m=index&c=user&a=register'</script>");	
			}
			$pwd = md5($pwd);
			//验证邮箱
			$email = trim($_POST['email']);
			$pat = '/\w+@\w+\.(com|cn|net)$/';
			if (!preg_match($pat, $email)) {
			exit("<script>alert('邮箱格式不正确');window.location.href='index.php?m=index&c=user&a=register'</script>");
			}
			//验证手机号
			$phone = trim($_POST['phone']);
			$pat = '/^1[34578]\d{9}$/';
			if (!preg_match($pat, $phone)) {
			exit("<script>alert('手机格式不正确');window.location.href='index.php?m=index&c=user&a=register'</script>");
			}
			//ip
			$ip = $_SERVER['REMOTE_ADDR'];
			if (!strcmp($ip,'::1')){
				$ip = '127.0.0.1';
			}
			$ip = ip2long($ip);
			//验证码的验证
			$yanzheng = trim($_POST['yanzheng']);
			$ver = $_SESSION['yzm'];
			if(strcmp($yanzheng, $ver)){
				exit("<script>alert('验证码不正确');window.location.href='index.php?m=index&c=user&a=register'</script>");	
			}
			$data = [
				'name' => $username,
				'pwd' => $pwd,
				'email' =>$email,
				'phone'=>$phone,
				'regip'=>$ip,
				'regtime'=>time(),
				'lasttime'=>time()
			];
			$result = $this->user->insertuser($data);
			if(!$result)
			{
			exit("<script>alert('注册失败，请联系管理员');window.location.href='index.php?m=index&c=user&a=register'</script>");
			}else{
				$result = $this->user->selectuser("id='$result'");
				$_SESSION['uid'] = $result[0]['id'];
				$_SESSION['username'] = $result[0]['name'];
				$this->notice('注册成功','index.php');	
			}
		}else{
			$this->display();
		}
	}
	public function verify()
	{
		 $code = Verify::ver(100,40);
		 $_SESSION['yzm'] = $code;
	}
	public function logout()
	{
		unset($_SESSION);session_destroy();
		setcookie("accesstoken",'');
		setcookie("uid",'');
		exit("<script>alert('退出成功');window.location.href='index.php'</script>");
	}
	public function findpwd()
	{
		$this->display();
	}
	public function dofindpwd()
	{
		$username = trim($_POST['username']);
		$phone = trim($_POST['phone']);
		$code = trim($_POST['code']);
		$realcode = $_SESSION['smscode'];
		$result = $this->user->where("name='$username'")->select();
		if (!$result) {
			$this->notice('用户名不存在！','index.php?c=user&a=findpwd');
		}else{
			if ($phone != $result[0]['phone']) {
				$this->notice('手机号错误！','index.php?c=user&a=findpwd');
			}
		}
		if ($code != $realcode) {
			$this->notice('验证码错误！','index.php?c=user&a=findpwd');
		}
		$this->notice('验证成功','index.php?c=user&a=setpwd');
	
	}
	public function setpwd()
	{
		if (empty($_POST)) {
			$this->display();
		}else{
			$username = trim($_POST['username']);
			$result = $this->user->where("name='$username'")->select();
			if (!$result) {
				$this->notice('用户名不存在！','index.php?c=user&a=setpwd');
			}
			$pwd = trim($_POST['pwd']);
			$repwd = trim($_POST['repwd']);
			if(strcmp($pwd,$repwd)){
				exit("<script>alert('密码不一致');window.location.href='index.php?m=index&c=user&a=setpwd'</script>");	
			}
			if(strlen($pwd) < 6){
				exit("<script>alert('密码不够长');window.location.href='index.php?m=index&c=user&a=setpwd'</script>");	
			}
			$pwd = md5($pwd);
			$data['pwd'] = $pwd;
			$res = $this->user->where("name='$username'")->update($data);
			if ($res) {
				$this->notice('重置成功！请重新登录','index.php');
			}else{
				$this->notice('重置失败！','index.php?c=user&a=setpwd');
			}
		}
	}
	//短信验证
	public function sendSMS()
	{
		$tel = $_POST['mobile'];//手机号
		              
		$c = new TopClient;//大于客户端   
		$c->format = 'json';//设置返回值得类型

		$c->appkey = "24451847";//阿里大于注册应用的key

	    $c->secretKey = "80b86919d45f22db95c68539b8743b51";//注册的secretkey
	                                                       
	    //请求对象，需要配置请求的参数   
		$req = new AlibabaAliqinFcSmsNumSendRequest;
		$req->setExtend("123456");//公共回传参数，可以不传
		$req->setSmsType("normal");//短信类型，传入值请填写normal
		
		//签名，阿里大于-控制中心-验证码--配置签名 中配置的签名，必须填
		$req->setSmsFreeSignName("李成龙");
		
		//你在短信中显示的验证码，这个要保存下来用于验证
		$num = rand(100000,999999);
		$_SESSION['smscode'] = $num;

		//短信模板变量，传参规则{"key":"value"}，key的名字须和申请模板中的变量名一致，传参时需传入{"code":"1234","product":"alidayu"}
		$req->setSmsParam("{\"number\":\"$num\"}");//模板参数
		                                           
		//短信接收号码。
	     $req->setRecNum($tel);

		//短信模板。阿里大于-控制中心-验证码--配置短信模板 必须填
		$req->setSmsTemplateCode("SMS_71256110");
		$resp = $c->execute($req);//发送请求
		return $resp;
	}
}