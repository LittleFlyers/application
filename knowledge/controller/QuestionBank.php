<?php
namespace app\knowledge\controller;

class QuestionBank
{
    //获取所有的题库信息
    public function all()
    {
        $Q = db('bank_question');
        $question_list = $Q->select();
        if( $question_list)
        {
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] =  $question_list;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }
 
        return json_encode($result);
    } 

    //获取题目详细信息
    public function one()
    {
        $question_id = input('post.question_id');
        $Q = db('bank_question');
        $A = db('bank_question_answer');
        $Qi = db('bank_question_img');
        $check['question_id'] = $question_id;
        $question_list = $Q -> where($check) -> select();
        $answer_list = $A -> where($check) -> select();
        $question_img_list = $Qi -> where($check) -> select();

        $result['err_code'] = 0;
        $result['qcontent'] = $question_list;
        $result['acontent'] = $answer_list;
        $result['mcontent'] = $question_img_list;

        return json_encode($result);
    }

    //类型搜索
    public function search()
	{
		$search = input('post.search');
		$D = db('question');
		$search = '%'.$search.'%';
        $question_list = $D -> whereOr('question_type', 'LIKE', $search) -> select();
        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        $result['data'] = $question_list;
        

        return json_encode($result);
    }
    
    //添加题目
    public function add()
    {
     /*   $question_content = input('post.question');
        $question_answer = input('post.answer');
        $question_type = input('post.type');
        $question_course = input('post.course');
        
        $Q = db('bank_question');
        
        $emap['question_content'] = $question_content;
        $emap['question_type'] = $question_type;
        $emap['question_course'] = $question_course;
        $emap['create_time'] = date('Y-m-d G:i:s');
        $emap['question_answer'] = $question_answer;
        $Q->insert($emap);*/

        $result['err_code'] = 0;
          $result['err_msg'] = "成功";
        
        return json_encode($result);
    }

}
?> 