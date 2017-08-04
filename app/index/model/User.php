<?php
namespace index\model;
use lcl\framework\Model;

class User extends Model
{
	public function insertuser($data)
	{
		return $this->insert($data);
	}
	public function selectuser($where)
	{
		return $this->where($where)->select();
	}
	public function checklogin($username, $pwd)
	{
		$data = $this->where("name='$username' and pwd='$pwd'")
					->field('id,name,level')
					->limit('1')
					->select();
		return $data;
	}
}