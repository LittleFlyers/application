<?php
namespace app\info\controller;

class Employment
{
    public function meeting()
    {
        //所有招聘会信息
        $Meeting = db('meeting');
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

        return $result;
    }

    public function post_all()
    {
        //所有岗位招聘信息
        $EmploymentPost = db('employment_post');
        $post_list = $EmploymentPost->select();

        $Post = db('post');

        if($post_list)
        {
            for($i=0;$i<count($post_list);$i++)
            {
                //获得公司名称
                $pmap['post_id'] = $post_list[$i]['post_id'];
                $thePost = $Post->where($pmap)->find();
                $post_list[$i]['post_name'] = $thePost['post_name'];
                $thePlatform = get_platform_by_id($thePost['platform_id']);
                $post_list[$i]['platform_id'] = $thePlatform['platform_id'];
                $post_list[$i]['platform_name'] = $thePlatform['platform_name'];
                $post_list[$i]['platform_level'] = $thePlatform['platform_level'];
            }
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $post_list;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }
        return $result;
    }

    public function post_one()
    {
        //所有岗位招聘信息
//        $access_token = input('post.access_token');
//        $user_id = get_user_id_by_access_token($access_token);

        $info_id = intval(input('post.info_id'));

        $EmploymentPost = db('employment_post');
        $emap['info_id'] = $info_id;
        $theInfo = $EmploymentPost->where($emap)->find();

        if($theInfo)
        {
            $Post = db('post');
            $pmap['post_id'] = $theInfo['post_id'];
            $thePost = $Post->where($pmap)->find();
            $thePlatform = get_platform_by_id($thePost['platform_id']);

            $theInfo['post_name'] = $thePost['post_name'];
            $theInfo['platform_id'] = $thePlatform['platform_id'];
            $theInfo['platform_name'] = $thePlatform['platform_name'];
            $theInfo['platform_level'] = $thePlatform['platform_level'];

            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $theInfo;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }

        return $result;
    }

    public function post_collect()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);

        $info_id = intval(input('post.info_id'));

        $EmploymentPost = db('employment_post');
        $emap['info_id'] = $info_id;
        $theInfo = $EmploymentPost->where($emap)->find();

        $EPC = db('employment_post_collect');
        $emap['info_id'] = $info_id;
        $emap['user_id'] = $user_id;
        $emap['post_id'] = $theInfo['post_id'];
        $theE = $EPC->where($emap)->find();
        if($theE)
        {
            if(!$theE['status'])
            {
                $theE['create_date'] = date('Y-m-d G:i:s');
                $theE['status'] = 1;
                $EPC->update($theE);
            }
            $result['err_code'] = 1;
            $result['err_msg'] = '已收藏';
        }
        else
        {
            $emap['create_date'] = date('Y-m-d G:i:s');
            $emap['status'] = 1;
            $EPC->insert($emap);
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
        }

        return $result;
    }

    public function post_collected()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);

        $EPC = db('employment_post_collect');
        $emap['user_id'] = $user_id;
        $emap['status'] = 1;
        $c_list = $EPC->where($emap)->select();

        $Post = db('post');
        $EmploymentPost = db('employment_post');

        if($c_list)
        {
            for($i=0;$i<count($c_list);$i++)
            {
                $pmap['post_id'] = $c_list[$i]['post_id'];
                $thePost = $Post->where($pmap)->find();
                $thePlatform = get_platform_by_id($thePost['platform_id']);
                $c_list[$i]['post_name'] = $thePost['post_name'];
                $c_list[$i]['platform_id'] = $thePlatform['platform_id'];
                $c_list[$i]['platform_name'] = $thePlatform['platform_name'];
                $c_list[$i]['platform_level'] = $thePlatform['platform_level'];
            }
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $c_list;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }

        return $result;
    }

    public function post_deliver()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);

        $info_id = intval(input('post.info_id'));

        $EmploymentPost = db('employment_post');
        $emap['info_id'] = $info_id;
        $theInfo = $EmploymentPost->where($emap)->find();

        $EPD = db('employment_post_deliver');
        $emap['info_id'] = $info_id;
        $emap['user_id'] = $user_id;
        $emap['post_id'] = $theInfo['post_id'];
        $theE = $EPD->where($emap)->find();
        if($theE)
        {
            if(!$theE['status'])
            {
                $theE['create_date'] = date('Y-m-d G:i:s');
                $theE['status'] = 1;
                $EPD->update($theE);
            }
            $result['err_code'] = 1;
            $result['err_msg'] = '已投';
        }
        else
        {
            $emap['create_date'] = date('Y-m-d G:i:s');
            $emap['status'] = 1;
            $EPD->insert($emap);
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
        }

        return $result;
    }

    public function post_delivered()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);

        $EPD = db('employment_post_deliver');
        $emap['user_id'] = $user_id;
        $emap['status'] = 1;
        $d_list = $EPD->where($emap)->select();

        $Post = db('post');
        $EmploymentPost = db('employment_post');

        if($d_list)
        {
            for($i=0;$i<count($d_list);$i++)
            {
                $pmap['post_id'] = $d_list[$i]['post_id'];
                $thePost = $Post->where($pmap)->find();
                $thePlatform = get_platform_by_id($thePost['platform_id']);
                $d_list[$i]['post_name'] = $thePost['post_name'];
                $d_list[$i]['platform_id'] = $thePlatform['platform_id'];
                $d_list[$i]['platform_name'] = $thePlatform['platform_name'];
                $d_list[$i]['platform_level'] = $thePlatform['platform_level'];
            }
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $d_list;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }

        return $result;
    }

    public function post_feedback()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);

        $PostFeedback = db('employment_post_feedback');

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }

    public function post_feedbacked()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);

        $Post = db('post');

        $PostFeedback = db('employment_post_feedback');
        $fmap['user_id'] = $user_id;
        $fmap['feedback_status'] = 1;
        $f_list = $PostFeedback->where($fmap)->select();

        if($f_list)
        {
            for($i=0;$i<count($f_list);$i++)
            {
                $pmap['post_id'] = $f_list[$i]['post_id'];
                $thePost = $Post->where($pmap)->find();
                $thePlatform = get_platform_by_id($thePost['platform_id']);
                $f_list[$i]['post_name'] = $thePost['post_name'];
                $f_list[$i]['platform_id'] = $thePlatform['platform_id'];
                $f_list[$i]['platform_name'] = $thePlatform['platform_name'];
                $f_list[$i]['platform_level'] = $thePlatform['platform_level'];
            }
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $f_list;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }

        return $result;
    }
}