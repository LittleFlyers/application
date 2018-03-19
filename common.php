<?php

// 应用公共文件

function guada_log($user_id,$action,$page_id,$data_id)
{
    $create_date = date('Y-m-d G:i:s');
    $ActionLog = db('action_log');
    $amap['user_id'] = $user_id;
    $amap['action'] = $action;
    $amap['page_id'] = $page_id;
    $amap['data_id'] = $data_id;
    $amap['create_date'] = $create_date;
    $ActionLog->insert($amap);
    $result['err_code'] = 0;
    $result['err_msg'] = 'ok';
    return $result;
}

/**
 * @return bool
 * 是否登录
 */
function is_login()
{
    if(session('login_flag')==1)
    {
        return true;
    }
    return false;
}

function get_user_id_by_access_token($token)
{
    $AccessToken = db('access_token');
    $amap['access_token'] = $token;
    $theA = $AccessToken->where($amap)->find();
    $user_id = $theA['user_id'];
    return $user_id;
}

function get_resume_key_by_user_id($user_id)
{
    $AccessToken = db('access_token');
    $amap['user_id'] = $user_id;
    $theA = $AccessToken->where($amap)->find();
    $resume_key = $theA['access_token'];
    return $resume_key;
}

function get_platform_by_id($platform_id)
{
    $Platform = db('platform');
    $pmap['platform_id'] = $platform_id;
    $thePlatform = $Platform->where($pmap)->find();
    return $thePlatform;
}

function get_platform_by_name($platform_name)
{
    $Platform = db('platform');
    $pmap['platform_name'] = $platform_name;
    $thePlatform = $Platform->where($pmap)->find();
    if(!$thePlatform)
    {
        $pmap['create_date'] = date('Y-m-d G:i:s');
        $pmap['status'] = 1;
        $platform_id = $Platform->insertGetId($pmap);
        $thePlatform = get_platform_by_id($platform_id);
    }
    return $thePlatform;
}

function get_platform_by_post_id($post_id)
{
    $pmap['post_id'] = $post_id;
    $Post = db('post');
    $thePost = $Post->where($pmap)->find();
    $thePlatform = get_platform_by_id($thePost['platform_id']);
    return $thePlatform;
}

function get_platform_by_interviewer($interviewer_id)
{
    $Platform = db('platform_interviewer');
    $pmap['interviewer_id'] = $interviewer_id;
    $thePlatform = $Platform->where($pmap)->find();
    return $thePlatform;
}