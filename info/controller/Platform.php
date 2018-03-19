<?php
namespace app\info\controller;

class Platform
{
    public function one()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $platform_id = intval(input('post.platform_id'));

        $Platform = db('platform');
        $pmap['platform_id'] = $platform_id;
        $theP = $Platform->where($pmap)->find();

        if($theP)
        {

            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $theP;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }

        return $result;
    }

    public function subscribe()
    {
        //用户订阅企业消息
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $platform_id = intval(input('post.platform_id'));
        $status = intval(input('post.status'));

        $PlatformCollect = db('platform_subscribe');
        $pmap['platform_id'] = $platform_id;
        $pmap['user_id'] = $user_id;
        $theC = $PlatformCollect->where($pmap)->find();
        if($theC)
        {
            $theC['status'] = $status;
            $theC['create_date'] = date('Y-m-d G:i:s');
            $PlatformCollect->update($theC);
        }
        else
        {
            $pmap['status'] = $status;
            $pmap['create_date'] = date('Y-m-d G:i:s');
            $PlatformCollect->insert($pmap);
        }

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }

    public function employment_post()
    {
        //用户订阅企业消息
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $platform_id = intval(input('post.platform_id'));

        //搜集企业的招聘信息。
        //找到post_id组，再从employment_post表里找。

        $omap['platform_id'] = $platform_id;
        $Post = db('employment_post');
        $post_list = $Post->join('post','gd_employment_post.post_id=gd_post.post_id')->where($omap)->select();

        if($post_list)
        {
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
}
