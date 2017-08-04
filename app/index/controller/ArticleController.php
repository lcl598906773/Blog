<?php
namespace index\controller;
use index\controller\BaseController;
use index\model\Article;
use lcl\framework\Page;

class ArticleController extends BaseController
{
	protected $blog;
	protected $page;
	public function _init()
	{

		$this->blog = new Article();
	}
	public function blog()
	{
		$total = $this->blog->select();
		$total = count($total);
		$count = 2;
		$this->page = new Page($total, $count);
		$offset = $this->page->limit();
		$allPage = $this->page->allPage();
		$data = $this->blog->limit("$offset")->order('addtime desc')->blogDetails();
		$this->assign('allPage', $allPage);
		$this->assign('data', $data);
		$this->display();
	}
}