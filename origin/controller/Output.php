<?php
namespace app\origin\controller;

// +----------------------------------------------------------------------
// | 输出人才管理员的后台页面控制器
// +----------------------------------------------------------------------

class Output
{
    public function login()
    {
        //登录

        return view();
    }

    public function index()
    {
        //后台首页
        if(!is_login())
        {
            return view('login');
            exit;
        }
        return view();
    }

    public function meeting()
    {
        //后台首页
        if(!is_login())
        {
            return view('login');
            exit;
        }
        return view();
    }
}
