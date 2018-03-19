<?php
namespace app\origin\controller;

// +----------------------------------------------------------------------
// | 超级管理员的后台页面控制器
// +----------------------------------------------------------------------

class Admin
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

    public function human()
    {
        //人才
        if(!is_login())
        {
            return view('login');
            exit;
        }
        return view();
    }

    public function human_detail()
    {
        //人才详情
        if(!is_login())
        {
            return view('login');
            exit;
        }
        return view();
    }

    public function platform()
    {
        //企业、高校、众创空间
        if(!is_login())
        {
            return view('login');
            exit;
        }
        return view();
    }

    public function platform_add()
    {
        //企业、高校、众创空间
        if(!is_login())
        {
            return view('login');
            exit;
        }
        return view();
    }

    public function platform_detail()
    {
        //人才平台详情
        if(!is_login())
        {
            return view('login');
            exit;
        }
        return view();
    }

    public function meeting()
    {
        //招聘会
        if(!is_login())
        {
            return view('login');
            exit;
        }
        return view();
    }

    public function meeting_add()
    {
        //招聘会
        if(!is_login())
        {
            return view('login');
            exit;
        }
        return view();
    }

    public function meeting_map()
    {
        //招聘会
        if(!is_login())
        {
            return view('login');
            exit;
        }
        session('meeting_id',input('meeting_id'));
        return view();
    }

    public function interview()
    {
        //招聘会
        if(!is_login())
        {
            return view('login');
            exit;
        }
        return view();
    }

    public function call_in()
    {
        //招聘信息
        if(!is_login())
        {
            return view('login');
            exit;
        }
        return view();
    }

    public function call_in_edit()
    {
        //添加招聘信息
        if(!is_login())
        {
            return view('login');
            exit;
        }
        return view();
    }

    public function map_list()
    {
        //地图信息
        if(!is_login())
        {
            return view('login');
            exit;
        }
        return view();
    }

    public function map_setting()
    {
        //地图设置
        if(!is_login())
        {
            return view('login');
            exit;
        }
        return view();
    }
}
