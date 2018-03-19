<?php

namespace app\message\controller;

class Feedback
{
    public function add()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $content = input('post.content');

        $UserFeedback = db('user_feedback');
        $ufmap['user_id'] = $user_id;
        $ufmap['content'] = $content;
        $ufmap['create_date'] = date('Y-m-d G:i:s');
        $UserFeedback->insert($ufmap);

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }
}