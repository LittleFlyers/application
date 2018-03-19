<?php
namespace app\knowledge\controller;

class Library{
	//获取文库中全部的信息
	public function all()
	{
		$L = db('document');
		$list = $L->select();
		$result['err_code'] = 0;
		$result['data'] = $list;
		
		return json_encode($result);
	}
	//获取文库中的单个文件
	public function one()
	{
		$document_id = input('post.id');
		$L = db('document');
		$enmp['document_id'] = $document_id;
		$list = $L -> where($enmp) -> limit(1) -> select();
		$result['err_code'] = 0;
		$result['data'] = $list;

		return json_encode($result);
	}
	//向文库中上传文件
	public function gettype()
	{
		$type = input('post.type');
		$L = db('document');
		$enmp['type'] = $type;
		$list = $L -> where($enmp) -> select();
		$result['err_code'] = 0;
		$result['data'] = $list;

		return json_encode($result);
	}
    //删除文件
	public function delete()
	{
		
	}
}