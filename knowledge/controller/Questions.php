<?php
namespace app\knowledge\controller;

class Questions{
	//获取题库中的全部信息
	public function all()
	{
		$Dq = db('question');
		$question_list = $Dq->select();
		if($question_list){
			 $result['err_code'] = 0;
			 $result['err_msg'] = 'success';
			 $result['data'] = $question_list;
		}else{
			$result['err_code'] = 0;
			$result['err_msg'] = 'faile';
		}
		return json_encode($result);
	}
	//获取题库中单个题目
	public function one()
	{
		$question_id = input('post.question_id');
		$Dq = db('question');
		$Dqi = db('question_img');
		$Dqa = db('question_answer');
		$check['question_id'] = $question_id;
		$question = $Dq ->where($check)->select();
		$question_detail = $Dqi ->where($check)->select();
		$answer = $Dqa  ->where($check)->select();
		$result['data'] = $question;
		$result['detail'] = $question_detail;
		$result['answer'] = $answer;
		$result['err_code'] = 0;
		$result['err_msg'] = 'success';
	
		return json_encode($result);
	}
	//向题库中添加题目
	public function add()
	{
		$Dq = db('question');
        $question = input('post.question');
        $type = input('post.type');
 
        
        $emap['question'] = $question;
        $emap['type'] = $type;
		$emap['create_time'] = date('Y-m-d G:i:s');
        $Dq->insert($emap);
		/*$exx['create_data']=$emap['create_data'];
		$list = $Dq->field('word_id')->limit(1)->where($exx)->find();*/
		$list = $Dq->getLastInsID();
          $result['err_code'] = 0;
          $result['err_msg'] = $list;
        
        return json_encode($result);
	}
	//加载题目图片
	public function load(){
				
        $file = request()->file('img');
		$question_id = input('post.question_id');
		$question = input('post.question');
		$i = input('post.i');
     
        // 移动到框架应用根目录/public/questions/ 目录下
        $info = $file->move(ROOT_PATH . 'questions');
        if($info){
            
			$img_url = $info->getSaveName();
			$img_url = 'https://mogujie.kuanxy.com/InterviewAdd/questions'.'/'.$img_url;
			$img_url = str_replace(DS,'/',$img_url);
			$WI = db('question_img');
			$emap['img_url'] = $img_url;
			$emap['question'] = $question;
			$emap['question_id'] = $question_id;
			$emap['i'] = $i;
            $WI->insert($emap);
			$result['err_code'] = 0;
			$result['err_msg'] = 'ok';
        }else{
            $result['err_msg'] = 'failt';
        }
        return json_encode($result);
	}
	//添加试题答案
	public function addanswer(){
		$Dq = db('question_answer');
        $question_id = input('post.question_id');
		$answer = input('post.answer');
		$emap['question_id'] = $question_id;
		$emap['answer'] = $answer;
		$Dq->insert($emap);
		$result['err_code'] = 0;
		$result['err_msg'] = 'ok';
	}
	public function updata()
	{
		
	}
    //删除题目
	public function delete()
	{
		
	}
}