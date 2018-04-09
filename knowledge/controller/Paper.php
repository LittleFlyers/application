<?php
namespace app\knowledge\controller;

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
        $paper_id = $_GET['paper_id'];
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
        $paper_id = $_POST['paper_id'];;
        $P = db('paper');
        $PQ = db('paper_question');
        $Q = db('bank_question');
        $A = db('bank_question_answer');

        $check['paper_id'] = $paper_id;

        $paper_list = $P -> where($check) -> select();
        $paper_question_id = $PQ -> field('question_id') -> where($check) -> select();
        $paper_qs_id = [];
        foreach($paper_question_id as $value){
            array_push($paper_qs_id,$value['question_id']);
        }
        $question_list = $Q -> where('question_id', 'IN', $paper_qs_id) -> select();
     //   $question_img_list = $Q -> where('question_id', 'IN', $paper_question_id) -> select();
       // $question_answer = $A -> where($check) -> select();
 
        $result['err_code'] = 0;
  //      $result['paper'] = $paper_list;
        $result['paper_data'] = $paper_list;
        $result['paper_question'] = $question_list;
       // $result['img'] = $question_img_list;
       // $result['answer'] = $question_answer;

        return json_encode($result);
    }

    public function oneAnswerH()
    {
        $paper_id = input('post.paper_id');
        $P = db('paper');
        $PQ = db('paper_question');
        $Q = db('bank_question');
        $A = db('bank_question_answer');

        $check['paper_id'] = $paper_id;

        $paper_list = $P -> where($check) -> select();
        $paper_question_id = $PQ -> field('question_id') -> where($check) -> select();
        $paper_qs_id = [];
        foreach($paper_question_id as $value){
            array_push($paper_qs_id,$value['question_id']);
        }
        $question_list = $Q -> where('question_id', 'IN', $paper_qs_id) -> select();
     //   $question_img_list = $Q -> where('question_id', 'IN', $paper_question_id) -> select();
       // $question_answer = $A -> where($check) -> select();
 
        $result['err_code'] = 0;
  //      $result['paper'] = $paper_list;
        $result['paper_data'] = $paper_list;
        $result['paper_question'] = $question_list;
       // $result['img'] = $question_img_list;
       // $result['answer'] = $question_answer;

        return json_encode($result);
    }

    //添加试卷
    public function add()
    {
        $paper_title = input('post.title');
        $paper_data = json_decode(input('post.paper_data'), true);
        $emap['paper_title'] = $paper_title;
        $emap['paper_author'] = "张鹏飞";
        $emap['create_time'] = date('Y-m-d G:i:s');
        $emap['paper_type'] = "计算机组成原理";
        $P = db('paper');
        $id = $P->insertGetId($emap);
        $PQ = db('paper_question');
        foreach($paper_data as $question_id => $score){
            $emapQ['paper_id'] = $id;
            $emapQ['question_id'] = $question_id;
            $emapQ['score'] = $score;
            $PQ -> insert($emapQ);
        }

        $result['err_code'] = 0;
        $result['data'] = $id;

        return json_encode($result);
    }
     /***学生回答问题 */
    public function answer()
    {
         $user_id = input('post.user_id');
         $paper_id = input('post.paper_id');
         $question_answer = json_decode(input('post.question_answer'), true);
         $SA = db('student_answer');
         $SP = db('student_paper');
         $date = date('Y-m-d G:i:s');
         $i=0;
        /* for($i =0;$i<count($question_answer);$i++)
         {
            $emap['user_id'] = $user_id;
            $emap['paper_id'] = $paper_id;
            $emap['create_time'] = $date;
            $emap['question_id'] = $answer['question_id'];
            $SA -> insert($emap);

         }*/
         $check['paper_id'] = $paper_id;
         $check['user_id'] = $user_id;
         $answer_count = $SP -> group('paper_id', 'user_id') -> where($check) -> count();
         
         $check['answer_count'] = $answer_count + 1;
         $SP -> insert($check);

         foreach($question_answer as $answer)
         {
             $emap['user_id'] = $user_id;
             $emap['paper_id'] = $paper_id;
             $emap['question_id'] = $answer['question_id'];
             $emap['answer'] = $answer['question_answer'];
             $emap['create_time'] = $date;
             $SA -> insert($emap);
        }
         $result['err_code'] = 0;
         $result['err_msg'] = $question_answer;
         return json_encode($result);
     }
}
?>