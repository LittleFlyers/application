<?php
namespace app\knowledge\controller;

class Experience{
	//获取经验的全部信息
	public function all()
	{
		$E = db('experience');
        $experience_list = $E->select();
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $experience_list;
        

        return json_encode($result);
	}
	//
	public function getone()
	{
		$experience_id = input('post.experience_id');
		$E = db('experience');
		$emap['experience_id'] = $experience_id;
		$user_id = $E -> field('user_id') -> where($emap) -> select();
		$emap1['user_id'] = $user_id[0]['user_id'];
		$U = db('user');
		$user_name = $U -> field('user_name') ->where($emap1) -> select();
        $e_list = $E->select();
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $e_list;
            $result['user_name'] = $user_name[0]['user_name'];

        return json_encode($result);
	}
	//获取单个经验回答
	public function one()
	{
        $experience_id = input('post.experience_id');
		$EA = db('experience_answer');
        $emap['experience_id'] = $experience_id;		
        $user_id = $EA -> field('user_id') -> where($emap) -> select();
        $emap1['user_id'] = $user_id[0]['user_id'];
		$U = db('user');
		$user_name = $U -> field('user_name') ->where($emap1) -> select();
        $theData = $EA -> where($emap)->select();

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        $result['data'] = $theData;
		$result['user_name'] = $user_name[0]['user_name'];

        return json_encode($result);
	}
	
	//回答经验
	public function answer()
	{
		$access_token = input('post.access_token');
        $exp_user_id = get_user_id_by_access_token($access_token);
		$experience_answer = input('post.experience_answer');
		$experience_id = input('post.experience_id');

		$EA = db('experience_answer');
		$emap['experience_id'] = $experience_id;
		$emap['answer_content'] = $experience_answer;
		$emap['user_id'] = $exp_user_id;
		$emap['create_date'] = date('Y-m-d G:i:s');
		$EA->insert($emap);

		$result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return json_encode($result);
	}
	//添加经验
	public function add()
	{
		$access_token = input('post.access_token');
        $exp_user_id = get_user_id_by_access_token($access_token);

		$title = input('post.title');
        $answer = input('post.answer');
        $type = input('post.type');

        $Experience = db('experience');
        $emap['experience_title'] = $title;
		$emap['user_id'] = $exp_user_id;
		$emap['type'] = $type;
        $emap['create_date'] = date('Y-m-d G:i:s');
		$emap['answer'] = $answer;
        $Experience->insert($emap);
		
        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return json_encode($result);
	}
    //收藏经验
    public function collect()
    {
         $access_token = input('post.access_token');
         $user_id = get_user_id_by_access_token($access_token);
         $experience_id = input('post.experience_id');
         $emap['user_id'] = $user_id;
         $emap['experience_id'] = $experience_id;
         $Wc = db('experience_collect');
         $info = $Wc->insert($emap);
         if(false !== $info)
         {
             $result['err_code'] = 0;
         }else
         {
             $result['err_code'] = 1;
         }
         return json_encode($result);
    }
    //获取收藏的经验
    public function getCollect(){
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $emap['user_id'] = $user_id;
        $Wc = db('experience_collect');
        $wordlist = $Wc-> field('experience_id') -> where($emap) ->select();
        $check = array();
        
        for($i = 0; $i < count($wordlist);$i++){
			$check[] = $wordlist[$i]['experience_id'];
		}
        $Dc = db('experience');
        $list = $Dc -> where('experience_id', 'IN', $check) -> select();
        $result['data'] = $list;
        $result['err_code'] = 0;
        return json_encode($result);
    }
	//搜索
	public function search()
	{
		$search = input('post.search');
		$E = db('experience');
		$search = '%'.$search.'%';
        $experience_list = $E -> whereOr('type', 'LIKE', $search)-> whereOr('experience_title', 'LIKE', $search) -> select();
        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        $result['data'] = $experience_list;
        

        return json_encode($result);
	}
    //删除经验
	public function delete()
	{
	
	}
}