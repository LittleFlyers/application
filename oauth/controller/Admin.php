<?php
namespace app\oauth\controller;

class Admin
{
    public function login()
    {
        $user_mobile = input('post.user_mobile');
        $user_password = input('post.user_password');

        $User = db('user');
        $umap['user_mobile'] = $user_mobile;
        $umap['user_password'] = $user_password;

        $theUser = $User->where($umap)->find();
        if($theUser)
        {
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            session('login_flag',1);
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '用户名或密码错误';
        }
        return $result;
    }
}