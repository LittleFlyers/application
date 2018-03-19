<?php
namespace app\platform\controller;

// +----------------------------------------------------------------------
// | 目标制定接口集
// +----------------------------------------------------------------------

class Basic
{
    public function create()
    {
        $Platform = db('platform');
        $pmap['platform_name'] = input('post.platform_name');
        $pmap['platform_code'] = input('post.platform_code');
        $pmap['create_date'] = date('Y-m-d G:i:s');
        $Platform->insert($pmap);

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';

        return $result;
    }

    public function all()
    {
        $Platform = db('platform');
        $pmap['status'] = 1;
        $platform_list = $Platform->where($pmap)->select();

        if($platform_list)
        {
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $platform_list;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '0';
        }

        return $result;
    }

    public function search()
    {
        $access_token = input('post.access_token');
        $search_key = input('post.search_key');

        $user_id = get_user_id_by_access_token($access_token);

        $Platform = db('platform');
        $pmap['status'] = 1;
        $pmap['platform_name'] = array('like','%'.$search_key.'%');
        $platform_list = $Platform->where($pmap)->select();

        if($platform_list)
        {
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $platform_list;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '无';
        }

        return $result;
    }
}