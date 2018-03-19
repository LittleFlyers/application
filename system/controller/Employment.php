<?php
namespace app\info\controller;

class Version
{
    public function check_update()
    {
        //系统版本
        $os_type = input('post.os_type');
        $vmap['os_type'] = $os_type;
        $vmap['status'] = 1;

        $SystemVersion = db('system_version');
        $theVersion = $SystemVersion->where($vmap)->order('create_date desc')->find();
        if($theVersion)
        {
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $theVersion;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '无版本信息';
        }

        return $result;
    }
}