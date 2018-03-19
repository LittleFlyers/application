<?php
namespace app\oauth\controller;

class Basic
{
    public function access_token()
    {
        $appid = input('post.appid');
        $appsecret = input('post.appsecret');

        $User = db('user');
        $umap['user_appid'] =  $appid;
        $umap['user_appsecret'] = $appsecret;
        $theUser = $User->where($umap)->find();
        if($theUser)
        {
            $AccessToken = db('access_token');
            $amap['user_id'] = $theUser['user_id'];
            $theToken = $AccessToken->where($amap)->find();

            $result['err_code'] = 0;
            $result['err_msg'] = $theToken['access_token'];
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = "用户不存在";
        }

        return json($result);
    }

    public function code()
    {

    }

    public function openid()
    {

    }

    private function is_appid_right()
    {

    }

    private function is_exprein()
    {
        //是否accesstoken超时
    }
}

/**
 * 统一账户登录
 * 人脸识别
 * 二维码
 * 座位地图
 * 平台
 * 人才
 * 简历
 * 文件处理
 * 对接教务系统
 * 轨迹
 * 地理位置
 * 推送
 * 长连接
 * 匹配
 */
