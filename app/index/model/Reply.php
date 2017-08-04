<?php
namespace index\model;
use lcl\framework\Model;

class Reply extends Model
{
	public function reply($id)
	{	
		// return $this->field('blog_user.name,blog_reply.content,blog_reply.replytime')->table('user,reply')->where("tid=$id and blog_user.id=blog_reply.authorid")->select();
		
		// $sql = "select a.name,b.content,b.replytime,b.authorid from blog_reply as b left join blog_user as a on a.id=b.authorid";
		return $this->field('authorid,content,replytime')->where("tid=$id")->select();
		return $this->query($sql,MYSQLI_ASSOC);
	}
}