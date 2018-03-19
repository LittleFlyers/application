<?php
namespace app\origin\controller;

// +----------------------------------------------------------------------
// | 人才的后台页面控制器
// +----------------------------------------------------------------------

class Human
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
