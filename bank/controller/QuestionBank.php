<?php
namespace app\bank\controller;

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

}
?> 