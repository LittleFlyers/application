<?php
namespace app\info\controller;

class Exchange
{
    public function share_list()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $ES = db('exchange_share');
        $emap['status'] = 1;
        $share_list = $ES->where($emap)->order('create_date desc')->select();

        $UserInfo = db('user_info');

        if($share_list)
        {
            for($i=0;$i<count($share_list);$i++){
                $uimap['user_id'] = $share_list[$i]['user_id'];
                $theUserInfo = $UserInfo->where($uimap)->find();
                $share_list[$i]['user_headpic'] = $theUserInfo['user_headpic'];
                $share_list[$i]['user_truename'] = $theUserInfo['user_truename'];
            }
        }

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        $result['data'] = $share_list;
        return json_encode($result);
    }

    public function share_add()
    {
        //用户订阅企业消息
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $keyword = input('post.keyword');
        $content = input('post.content');

        $ES = db('exchange_share');
        $emap['user_id'] = $user_id;
        $emap['keyword'] = $keyword;
        $emap['content'] = $content;
        $emap['status'] = 1;
        $emap['create_date'] = date('Y-m-d G:i:s');
        $ES->insert($emap);

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return json_encode($result);
    }

    public function question_list()
    {
        //用户订阅企业消息
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $EQ = db('exchange_question');
        $emap['status'] = 1;
        $question_list = $EQ->where($emap)->order('create_date desc')->select();

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        $result['data'] = $question_list;
        return json_encode($result);
    }

    public function question_one()
    {
        $question_id = intval(input('post.question_id'));
        $Q = db('exchange_question');
        $qmap['question_id'] = $question_id;
        $theQ = $Q->where($qmap)->find();
        if($theQ)
        {
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $theQ;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }
        return $result;
    }

    public function question_add()
    {
        //用户订阅企业消息
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $title = input('post.title');
        $EQ = db('exchange_question');
        $emap['user_id'] = $user_id;
        $emap['title'] = $title;
        $emap['status'] = 1;
        $emap['create_date'] = date('Y-m-d G:i:s');
        $EQ->insert($emap);

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return json_encode($result);
    }

    public function answer_list()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $question_id = intval(input('post.question_id'));

        $EQA = db('exchange_question_answer');
        $emap['question_id'] = $question_id;
        $emap['status'] = 1;
        $answer_list = $EQA->where($emap)->order('create_date desc')->select();

        $UserInfo = db('user_info');

        if($answer_list)
        {
            for($i=0;$i<count($answer_list);$i++)
            {
                $uimap['user_id'] = $answer_list[$i]['user_id'];
                $theUserInfo = $UserInfo->where($uimap)->find();
                $answer_list[$i]['user_headpic'] = $theUserInfo['user_headpic'];
                $answer_list[$i]['user_truename'] = $theUserInfo['user_truename'];
            }
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $answer_list;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }

        return $result;
    }

    public function answer_add()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $question_id = intval(input('post.question_id'));
        $content = input('post.content');

        $EQA = db('exchange_question_answer');
        $emap['question_id'] = $question_id;
        $emap['status'] = 1;
        $emap['user_id'] = $user_id;
        $emap['content'] = $content;
        $emap['create_date'] = date('Y-m-d G:i:s');
        $EQA->insert($emap);

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }
}
