<?php
namespace app\bank\coontroller;

class Paper
{
    //获取全部试卷信息
    public function all()
    {
        $P = db('paper');
        $paper_list = $P->select();
        if( $paper_list)
        {
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] =  $paper_list;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }
 
        return json_encode($result);
    } 
    //获取单个试卷(无答案)
    public function one()
    {
        $paper_id = intput('post.paper_id');
        $P = db('paper');
        $PQ = db('paper_question');
        $Q = db('bank_question');
        $check['paper_id'] = $paper_id;
        $paper_list = $P -> where($check) -> select();
        $paper_question_id = $PQ -> field('question_id') -> where($check) -> select();
        $question_list = $Q -> where('question_id', 'IN', $paper_question_id) -> select();
        $question_img_list = $Q -> where('question_id', 'IN', $paper_question_id) -> select();
        $result['err_code'] = 0;
        $result['paper'] = $paper_list;
        $result['paper_question'] = $question_list;
        $result['img'] = $question_img_list;

        return json_encode($result);
    }
    //获取单个试卷含答案
    public function oneAnswer()
    {
        $paper_id = intput('post.paper_id');
        $P = db('paper');
        $PQ = db('paper_question');
        $Q = db('bank_question');
        $A = db('bank_question_answer');

        $check['paper_id'] = $paper_id;

        $paper_list = $P -> where($check) -> select();
        $paper_question_id = $PQ -> field('question_id') -> where($check) -> select();
        $question_list = $Q -> where('question_id', 'IN', $paper_question_id) -> select();
        $question_img_list = $Q -> where('question_id', 'IN', $paper_question_id) -> select();
        $question_answer = $A -> where($check) -> select();

        $result['err_code'] = 0;
        $result['paper'] = $paper_list;
        $result['paper_question'] = $question_list;
        $result['img'] = $question_img_list;
        $result['answer'] = $question_answer;

        return json_encode($result);
    }

    //添加试卷
    public function add()
    {
        $user_id = input('post.user_id');
        $paper_title = input('post.paper_title');
        $paper_type = input('post.paper_type');
        $paper_question = input('post.paper_question');
    }
}
?>