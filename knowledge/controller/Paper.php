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
        $access_token = input('post.access_token');
        $author_id = get_user_id_by_access_token($access_token);
        $paper_title = input('post.title');
        $paper_data = json_decode(input('post.paper_data'), true);
        $paper_time = json_decode(input('post.paper_time'), true);
        $teache_list = json_decode(input('post.teacher_list'), true);
        $emap['paper_title'] = $paper_title;
        $emap['paper_author'] = $author_id;
        $emap['create_time'] = date('Y-m-d G:i:s');
        $emap['paper_type'] = "计算机组成原理";
        $emap['paper_start_time'] = $paper_time[0]['startTime'];
        $emap['paper_end_time'] = $paper_time[0]['endTime'];
        $emap['paper_duration'] = $paper_time[0]['durTime'];
        $P = db('paper');
        $id = $P->insertGetId($emap);
        $PQ = db('paper_question');
        foreach($paper_data as $question_id => $score){
            $emapQ['paper_id'] = $id;
            $emapQ['question_id'] = $question_id;
            $emapQ['score'] = $score;
            $PQ -> insert($emapQ);
        }
        $CP = db('can_paper');
        $U = db('user');
        foreach($teache_list as $value){
            $temp['user_mobile'] = $value;
            $user_id = $U -> field('user_id') -> where($temp) -> select();
            $insert['paper_id'] = $id;
            $insert['user_id'] = $user_id[0]['user_id'];
            $CP -> insert($insert);
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
         $check['check'] = 0;
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

    /****教师查看可以批改的试卷 */
    public function getPaper()
    {
        $SP = db('student_paper');
        $paper_id = $SP -> field('paper_id') -> select();
        $pa_id = [];
        foreach($paper_id as $value){
            array_push($pa_id,$value['paper_id']);
        }
        $P = db('paper');
        $paper_endtime_list = $P -> field('paper_end_time,paper_id') -> where('paper_id', 'IN', $pa_id) -> select();
        $pa_id_can = [];
        $now = date('Y-m-d G:i:s');
        foreach($paper_endtime_list as $value){
            if($value['paper_end_time'] <= $now){
                array_push($pa_id_can,$value['paper_id']);
            }
        }

        $paper_list = $SP -> where('paper_id', 'IN' , $pa_id_can) -> select();

       


        $result['err_code'] = 0;
        $result['err_msg'] = $paper_list;

        return json_encode($result);
    }

    public function paperListToCheck()
    {
        $paper_id = input('post.paper_id');
        $SP = db('student_paper');
        $P = db('paper');

        $check['paper_id'] = $paper_id;
        $check['check'] = 0;
        $check1['paper_id'] = $paper_id;

        $student_paper_list = $SP -> where($check) ->select();
        $paper_data = $P -> where($check1) -> select();

        $result['err_code'] = 0;
        $result['student_paper'] = $student_paper_list;
        $result['paper_data'] = $paper_data;

        return json_encode($result);
    }

    /*** 获取可批改的试卷*/
    public function canMarkPaper()
    {
       // $teacher_id = input('post.teacher_id');
       $access_token = input('post.access_token');
       $teacher_id = get_user_id_by_access_token($access_token);

        $P = db('paper');
        $CP = db('can_paper');
        
        $check['user_id'] = $teacher_id;
        $can_paper_id = $CP -> field('paper_id') -> where($check) -> select();
        $cp_id = [];
        foreach($can_paper_id as $value)
        {
            array_push($cp_id, $value['paper_id']);
        }

        $student_paper_list = $P -> alias('p') -> where('p.paper_id', 'IN', $cp_id) -> select();

        $result['err_code'] = 0;
        $result['data'] = $student_paper_list;

        return json_encode($result);


    }
    /***获取试卷中所有写生答题试卷 */
    public function getStuPaper()
    {
        $paper_id = input('post.paper_id');
        //$paper_id = '39';
        $P = db('paper');
        $SP = db('student_paper');
        $check['sp.paper_id'] = $paper_id;
        $check['sp.check'] = 0;
        $check1['paper_id'] = $paper_id;
        $paper_data = $P -> where($check1) -> select();
        $student_paper_list = $SP -> alias('sp') -> join('paper p', 'sp.paper_id=p.paper_id') -> where($check) -> select();

        $result['err_code'] = 0;
        $result['data'] = $student_paper_list;
        $result['paper_data'] = $paper_data;

        return json_encode($result);

    }
    /***获取学生答题试卷的详细信息 */

    public function stuPaperDetail()
    {
        $paper_id = input('post.paper_id');
        $user_id = input('post.user_id');
       /* $count = input('post.count');*/

        $SA = db('student_answer');
        $SP = db('student_paper');
        $P = db('paper');
        $PQ = db('paper_question');

        $check['sa.paper_id'] = $paper_id;
        $check['sa.user_id'] = $user_id;
        $check1['paper_id'] = $paper_id;
        $question_answer = $SA -> alias('sa') -> join('student_paper sp', 'sa.paper_id=sp.paper_id') -> join('bank_question q', 'sa.question_id=q.question_id') -> where($check) -> select();
        $question_score = $PQ -> where($check1) -> select();
        $paper_data = $P -> where($check1) -> select();
          
        $result_data = [];
        foreach($question_answer as $key1)
        {
            foreach($question_score as $key2)
            {
                if($key1['question_id'] == $key2['question_id'])
                {
                    $temp['paper_id'] = $key1['paper_id'];
                    $temp['question_id'] = $key1['question_id'];
                    $temp['question_content'] = $key1['question_content'];
                    $temp['question_answer'] = $key1['question_answer'];
                    $temp['student_answer'] = $key1['answer'];
                    $temp['question_score'] = $key2['score'];
                    $temp['user_id'] = $key1['user_id'];

                    array_push($result_data, $temp);
                }
            }
        }

        $result['err_code'] = 0;
       // $result['student_paper'] = $question_answer;
        $result['data'] = $result_data;
        $result['paper_data'] = $paper_data;

        return json_encode($result);
    }

    public function mark()
    {
        $access_token = input('post.access_token');
        $teacher_id = get_user_id_by_access_token($access_token);
        $mark_data = json_decode(input('post.mark_data'), true);

        $SA = db('student_answer');
        $SP = db('student_paper');

        $check1['user_id'] = $mark_data[0]['user_id'];
        $check1['paper_id'] = $mark_data[0]['paper_id'];
        $score = 0;
        foreach($mark_data as $value)
        {
            $check['user_id'] = $value['user_id'];
            $check['paper_id'] = $value['paper_id'];
            $check['question_id'] = $value['question_id'];
            $updata['score'] = $value['getScore'];
            $score += $value['getScore'];
            $updata['reviewer'] = $teacher_id;
            $SA -> where($check) -> update($updata);
            
        }
        $SP -> where($check1) -> update(['check' => 1, 'score' => $score]);
        

        $result['err_code'] = 0;
        // $result['student_paper'] = $question_answer;
        $result['data'] = $mark_data;
 
         return json_encode($result);
    }

    /**学生获取自己答过的试卷 */
    public function getPapers()
    {
        $user_id = input('post.user_id');
        //$user_id = '18829570573';

        $SP = db('student_paper');
        
        $check['user_id'] = $user_id;
        
        $data = $SP -> alias('sp') -> join('paper p', 'sp.paper_id=p.paper_id') -> where($check) -> select();

        $result['err_code'] = 0;
        $result['data'] = $data;

        return json_encode($result);
    }

    /***学生获取答过试卷的详细信息 */
    public function getPaperMarked()
    {
        /*$user_id = input('post.user_id');
        $paper_id = input('post.paper_id');*/
        $user_id = '18829570573';
        $paper_id = '39';

        $check['paper_id'] = $paper_id;
        $check['user_id'] = $user_id;

        $check2['paper_id'] = $paper_id;

        $P = db('paper');
        $SA = db('student_answer');

        $paper_data = $P -> where($check2) -> select();
        $stu_question = $SA -> where($check) -> select();

        $result['err_code'] = 0;
        $result['paper_data'] = $paper_data;
        $result['question_data'] = $stu_question;

        return json_encode($result);
    }

}
?>