<?php
namespace app\knowledge\controller;
 
class Dictionary{
    //获取词典中全部的信息
    public function all()
    {
       $Meeting = db('dictionary');
        $meeting_list = $Meeting->select();
        if($meeting_list)
        {
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $meeting_list;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }
 
        return json_encode($result);
    }
    //获取词典中的单个词条
    public function one()
    {
        $word_id = input('post.word_id');
         //$word_id = 61;
		 $D = db('dictionary');
		 $Wi = db('word_img');
		 $check['word_id'] = $word_id;
		 $m_list = $D->where($check)->select();
		 $s_list = $Wi->where($check)->select();
		 $result['err_code'] = 0;
		 $result['mcontent'] = $m_list;
		 $result['scontent'] = $s_list;
		 
		 return json_encode($result);
    }
    //向词典中添加词条
    public function add()
    {
		$Dw = db('dictionary');
        $word = input('post.word');
        $type = input('post.type');
        $explain = input('post.explain');
        $emap['word'] = $word;
        $emap['word_explain'] = $explain;
        $emap['type'] = $type;
		$emap['create_data'] = date('Y-m-d G:i:s');
        $Dw->insert($emap);
		$exx['create_data']=$emap['create_data'];
	    $list = $Dw->field('word_id')->limit(1)->where($exx)->find();
          $result['err_code'] = 0;
          $result['err_msg'] = $list;
        
        return json_encode($result);
         
    }
    //接收词条图片
    public function load()
    {
		
        $file = request()->file('img');
		$word_id = input('post.word_id');
		$explain = input('post.explain');
		$i = input('post.i');
     
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'uploads');
        if($info){
            
			$img_url = $info->getSaveName();
			$img_url = 'https://mogujie.kuanxy.com/InterviewAdd/uploads'.'/'.$img_url;
			$img_url = str_replace(DS,'/',$img_url);
			$WI = db('word_img');
			$emap['img_url'] = $img_url;
			$emap['word_explain'] = $explain;
			$emap['word_id'] = $word_id;
			$emap['i'] = $i;
            $WI->insert($emap);
			$result['err_code'] = 0;
			$result['err_msg'] = 'ok';
        }else{
            $result['err_msg'] = 'failt';
        }
        return json_encode($result);
    }
    //收藏词条
    public function collect()
    {
         $access_token = input('post.access_token');
         $user_id = get_user_id_by_access_token($access_token);
         $word_id = input('post.word_id');
         /*$user_id = 0;
         $word_id = 0;*/
         $emap['user_id'] = $user_id;
         $emap['word_id'] = $word_id;
         $Wc = db('word_collect');
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
    //获取收藏的词条
    public function getCollect(){
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $emap['user_id'] = $user_id;
        $Wc = db('word_collect');
        $wordlist = $Wc-> field('word_id') -> where($emap) ->select();
        $check = array();
        
        for($i = 0; $i < count($wordlist);$i++){
			$check[] = $wordlist[$i]['word_id'];
		}
        $Dc = db('dictionary');
        $list = $Dc -> where('word_id', 'IN', $check) -> select();
        $result['data'] = $list;
        $result['err_code'] = 0;
        return json_encode($result);
    }
	//搜索
    public function search()
	{
		$search = input('post.search');
		$D = db('dictionary');
		$search = '%'.$search.'%';
        $word_list = $D -> whereOr('type', 'LIKE', $search)-> whereOr('word', 'LIKE', $search) -> select();
        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        $result['data'] = $word_list;
        

        return json_encode($result);
	}
    //删除词条
    public function delete()
    {
         
    }
}