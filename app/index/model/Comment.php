<?php
namespace index\model;
use lcl\framework\Model;

class Comment extends Model
{
	public function insertComment($data)
	{
		return $this->insert($data);
	}
}