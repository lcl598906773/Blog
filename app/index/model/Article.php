<?php
namespace index\model;
use lcl\framework\Model;

class Article extends Model
{
	public function blogList()
	{
		return $this->field('id,title,content,addtime,replycount,icon')->order('addtime desc')->limit('5')->select();
	}
	public function blogDetails()
	{
		return $this->field('id,title,content,addtime,replycount,icon')->select();
	}
	
}