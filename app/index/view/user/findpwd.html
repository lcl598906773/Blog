<!doctype html>
<html>
<head>
<title>Find</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- font files  -->
<!-- <link href='http://fonts.useso.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'> -->
<!-- <link href='http://fonts.useso.com/css?family=Nunito:400,300,700' rel='stylesheet' type='text/css'> -->
<!-- /font files  -->
<!-- css files -->
<link href="public/index/css/style1.css" rel='stylesheet' type='text/css' media="all" />
<!-- /css files -->
    <style type="text/css">
    *{margin: 0px;padding: 0px;}
    .content{width: 360px;background: pink;margin: 20px auto;}
    .title{text-align: center;font-size: 18px;width:100%;height: 30px;line-height: 30px;}
    .register{height: 30px;line-height: 30px;width: 60px;text-align: center;float: right;}
    a{text-decoration: none;}
    .middle{width: 360px;}
    .headimage{width: 100%;}
    .headimage p{text-align: center;}
    .middle input{display: block;width: 98%;margin: 0px auto;height: 30px;}
    .middle input[type='submit']{margin-top: 20px;}
    .middle form .code{display: inline-block;width: 75%;height: 30px;}
    .middle form button{width: 22%;height: 32px;}
    </style>
    <script type="text/javascript" src='public/index/js1/jquery-1.11.3.min.js'></script>
</head>
<body>
<h1><a href="index.php" style="color: #fff">My Blog</a></h1>
<div class="log">
	<div class="content1">
		<h2>Find</h2>
		<form action="index.php?c=user&a=dofindpwd" method="post">
			<input type="text" name="username" placeholder = "USERNAME" >
			<input type="text" name="phone" placeholder="PHONE" id='mobile'>
			<input type="text" name="code" class='code' placeholder="验证码"><br />
            <button id='sendmsg' style="width: 200px;height: 40px;margin-top: 10px;">获取验证码</button>
			<div class="button-row">
				<input type="submit" class="sign-in" value="提交" style="text-align: center;margin-left: 150px;">
				<div class="clear"></div>
			</div>
		</form>
	</div>
	<div class="clear"></div>
</div>

</body>
<script type="text/javascript">
    //验证手机号
    $("#mobile").blur(function(){
        var value = $(this).val();
        //console.log(value,typeof value);
        if ( 0 == value.lenght || "" == value) {
            //alert("手机号不能为空！")
            $(this).focus();
        } else {
            // $.post('index.php?c=user&a=sendSMS',{phone:value},function(data){
            //     if (data) {
            //         alert("手机号重复！");
            //     }
            // });
        }
         
    });
 
    var InterValObj; //timer变量，控制时间
    var count = 60; //间隔函数，1秒执行
    var curCount;//当前剩余秒数
    var code = ""; //验证码
    var codeLength = 6;//验证码长度
 
    $('#sendmsg').click(function () {
        var phone = $("#mobile").val();
        console.log(phone);
        $.post('index.php?c=user&a=sendSMS',{mobile:phone},function(data){
            if(data){
                        console.log(data);
                        curCount = count;
                       //设置button效果，开始计时
                       $("#sendmsg").css("background-color", "LightSkyBlue");
                       $("#sendmsg").attr("disabled", "true");
                       $("#sendmsg").html("获取" + curCount + "秒");
                       InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
                      // alert("验证码发送成功，请查收!");
                  }
        });
       
        return false;
    })
 
    function SetRemainTime() {
        if (curCount == 0) {
            window.clearInterval(InterValObj);//停止计时器
            $("#sendmsg").removeAttr("disabled");//启用按钮
            $("#sendmsg").css("background-color", "");
            $("#sendmsg").html("重发验证码");
            code = ""; //清除验证码。如果不清除，过时间后，输入收到的验证码依然有效
        }
        else {
            curCount--;
            $("#sendmsg").html("获取" + curCount + "秒");
        }
    }
</script>
</html>