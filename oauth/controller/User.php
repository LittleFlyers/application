<?php
namespace app\oauth\controller;

class User
{
    public function register()
    {
        $user_mobile = input('post.user_mobile');
        $user_password = input('post.user_password');
        $appid = input('post.appid');
        $appsecret = input('post.appsecret');

        $User = db('user');
        $umap['user_mobile'] = $user_mobile;

        $theUser = $User->where($umap)->find();
        if($theUser)
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '该手机号已注册，请登录';
            return $result;
            exit;
        }

        $umap['user_password'] = $user_password;
        $umap['create_date'] = date('Y-m-d G:i:s');
        $umap['user_appid'] = $appid;
        $umap['user_appsecret'] = $appsecret;
        $user_id = $User->insertGetId($umap);

        $access_token = base64_encode($appid.$umap['create_date']);

        $AccessToken = db('access_token');
        $amap['user_id'] = $user_id;
        $theA = $AccessToken->where($amap)->find();
        if($theA)
        {
            $theA['create_date'] = date('Y-m-d G:i:s');
            $theA['access_token'] = $access_token;
            $AccessToken->update($amap);
        }
        else
        {
            $amap['create_date'] = date('Y-m-d G:i:s');
            $amap['access_token'] = $access_token;
            $AccessToken->insert($amap);
        }

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        $result['appid'] = $appid;
        $result['appsecret'] = $appsecret;
        $result['access_token'] = $access_token;

        return json_encode($result);
    }

    public function change_password()
    {
        $user_mobile = input('post.user_mobile');
        $user_password = input('post.user_password');
        $appid = input('post.appid');
        $appsecret = input('post.appsecret');

        $User = db('user');
        $umap['user_mobile'] = $user_mobile;

        $theUser = $User->where($umap)->find();
        if($theUser)
        {
            $theUser['user_password'] = $user_password;
            $theUser['user_appid'] = $appid;
            $theUser['user_appsecret'] = $appsecret;
            $User->update($theUser);

            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '未找到该用户';
        }

        return json_encode($result);
    }

    public function login()
    {
        $user_mobile = input('post.user_mobile');
        $user_password = input('post.user_password');

        $User = db('user');
        $umap['user_appid'] = base64_encode($user_mobile);
        $umap['user_appsecret'] = md5($user_password);

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