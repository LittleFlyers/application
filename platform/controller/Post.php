<?php
namespace app\platform\controller;

// +----------------------------------------------------------------------
// | 岗位接口集
// +----------------------------------------------------------------------

class Post
{
    public function create()
    {
        $platform_name = input('post.platform_name');
        $post_name = input('post_name');
        $duty_intro = input('duty_intro');
        $capacity_intro = input('capacity_intro');

        $Post = db('post');
        $pmap['post_name'] = $post_name;
        $pmap['duty_intro'] = $duty_intro;
        $pmap['capacity_intro'] = $capacity_intro;
        $pmap['platform_id'] = $platform_id;
        $Post->insert($pmap);

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';

        return $result;
    }

    public function all()
    {
        //获得全部岗位。
        $Post = db('post');
        $pmap['platform_id'] = intval(input('post.platform_id'));
        $post_list = $Post->where($pmap)->select();

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

    public function one()
    {
        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }

    public function employment()
    {
        //岗位招聘信息
    }

    public function capacity()
    {
        //岗位能力列表
    }

    public function duty()
    {
        //岗位职责列表
    }
}