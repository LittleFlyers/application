<?php
namespace app\human\controller;

class Capacity
{
    public function all()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $Capacity = db('user_capacity');
        $cmap['user_id'] = $user_id;
        $cmap['status'] = 1;
        $c_list = $Capacity->where($cmap)->select();
        if($c_list)
        {
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
    public function one()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $data_id = intval(input('post.data_id'));

        $Capacity = db('user_capacity');
        $cmap['data_id'] = $data_id;
        $cmap['status'] = 1;
        $theData = $Capacity->where($cmap)->find();
        if($theData)
        {
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $theData;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }

        return $result;
    }
    public function add()
    {
        //添加个人能力
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $capacity_name = input('post.capacity_name');
        $capacity_intro = input('post.capacity_intro');
        $Capacity = db('user_capacity');
        $cmap['user_id'] = $user_id;
        $cmap['capacity_name'] = $capacity_name;
        $cmap['capacity_intro'] = $capacity_intro;
        $cmap['status'] = 1;
        $cmap['create_date'] = date('Y-m-d G:i:s');
        $Capacity->insert($cmap);

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }
    public function update()
    {
        //更新某个个人能力
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $data_id = intval(input('post.data_id'));
        $capacity_name = input('post.capacity_name');
        $capacity_intro = input('post.capacity_intro');

        $Capacity = db('user_capacity');
        $cmap['data_id'] = $data_id;
        $cmap['user_id'] = $user_id;
        $cmap['capacity_name'] = $capacity_name;
        $cmap['capacity_intro'] = $capacity_intro;
        $cmap['create_date'] = date('Y-m-d G:i:s');
        $Capacity->update($cmap);

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }
    public function delete()
    {
        //删除某个自己的能力
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $data_id = intval(input('post.data_id'));

        $Capacity = db('user_capacity');
        $cmap['data_id'] = $data_id;
        $cmap['status'] = 0;
        $Capacity->update($cmap);

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }
    public function collect()
    {
        //收藏某个能力
        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }
}