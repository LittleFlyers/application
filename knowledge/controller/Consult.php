<?php
namespace app\knowledge\controller;

class Consult{
	//获取咨询中的全部信息
	public function all()
	{
		$access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);

        $Consult = db('consult');
       // $emap['user_id'] = $user_id;
        $consult_list = $Consult->where(['user_id'=>$user_id])->select();
		if($consult_list)
        {
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $consult_list;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }

        return json_encode($result);

	}
	//获取咨询中详细信息
	public function one()
	{
		
	}
    //添加咨询
	public function add()
	{
		$access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
		$type = input('post.type');
		$consult_title = input('post.consult');

		$Consult = db('consult');
		$emap['user_id'] = $user_id;
		$emap['consult_type'] = $type;
		$emap['consult_title'] = $consult_title;
		$emap['create_date'] = date('Y-m-d G:i:s');
		$Consult->insert($emap);

		$result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return json_encode($result);


	}
	//回答咨询
	public function answer()
	{
		
	}
    //删除咨询
	public function delete()
	{
		
	}
}