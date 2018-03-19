<?php
namespace app\origin\controller;

// +----------------------------------------------------------------------
// | 输入人才管理员的后台页面控制器
// +----------------------------------------------------------------------

class Input
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
        //正在进行的招聘会
        if(!is_login())
        {
            return view('login');
            exit;
        }
        return view();
    }

    public function resume_package()
    {
        //简历包
        if(!is_login())
        {
            return view('login');
            exit;
        }
        return view();
    }

    public function resume_detail()
    {
        //简历详情
        if(!is_login())
        {
            return view('login');
            exit;
        }
        return view();
    }
}