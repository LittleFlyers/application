<?php
namespace app\platform\controller;

// +----------------------------------------------------------------------
// | 面试接口集
// +----------------------------------------------------------------------

class Interview
{
    public function log_scan_face()
    {
        //刷脸
    }

    public function log_scan_qrcode()
    {
        //扫码
        //保存扫描记录。///////////////////////////////////////////
        $access_token = input('post.access_token');
        $interviewer_id = get_user_id_by_access_token($access_token);

        $resume_key = input('post.resume_key');
        $resume_user_id = get_user_id_by_access_token($resume_key);

        $thePlatform = get_platform_by_interviewer($interviewer_id);
        $platform_id = $thePlatform['platform_id'];

        $URS = db('user_resume_scanned');
        $urmap['interviewer_id'] = $interviewer_id;
        $urmap['resume_user_id'] = $resume_user_id;
        $urmap['platform_id'] = $platform_id;
        $urmap['create_date'] = date('Y-m-d G:i:s');
        $URS->insert($urmap);
        ///////////////////////////////////////////////////////////

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }

    public function log_scan_finger()
    {
        //指纹
    }

    public function resume_all()
    {
        $access_token = input('post.access_token');
        $interviewer_id = get_user_id_by_access_token($access_token);
        $status = input('post.status');
        $thePlatform = get_platform_by_interviewer($interviewer_id);
        $platform_id = $thePlatform['platform_id'];

        $PlatformResume = db('platform_resume');
        $pmap['interviewer_id'] = $interviewer_id;
        $pmap['platform_id'] = $platform_id;
        $pmap['status'] = $status;
        $resume_list = $PlatformResume->where($pmap)->select();

        if($resume_list)
        {
            $UserInfo = db('user_info');
            for($i=0;$i<count($resume_list);$i++)
            {
                $umap['user_id'] = $resume_list[$i]['resume_user_id'];
                $theResumeUser = $UserInfo->where($umap)->find();
                $resume_list[$i]['resume_user_truename'] = $theResumeUser['user_truename'];
                //获得resume_key
                $resume_list[$i]['resume_key'] = get_resume_key_by_user_id($resume_list[$i]['resume_user_id']);
            }
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $resume_list;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '无';
        }
        return $result;
    }

    public function resume_get()
    {
        //获取个人简历
        $access_token = input('post.access_token');
        $interviewer_id = get_user_id_by_access_token($access_token);

        $resume_key = input('post.resume_key');
        $resume_user_id = get_user_id_by_access_token($resume_key);

        $UserInfo = db('user_info');
        $uimap['user_id'] = $resume_user_id;
        $theResumeUser = $UserInfo->where($uimap)->find();
        if($theResumeUser){
            $result['data']['user_truename'] = $theResumeUser['user_truename'];
            $result['data']['user_minzu'] = $theResumeUser['user_minzu'];
            $result['data']['user_sex'] = $theResumeUser['user_sex'];
            $result['data']['user_headpic'] = $theResumeUser['user_headpic'];
            $result['data']['user_mobile'] = $theResumeUser['user_mobile'];
            $result['data']['user_email'] = $theResumeUser['user_email'];
            $result['data']['user_birth'] = $theResumeUser['user_birth'];
            $result['data']['user_evaluation'] = $theResumeUser['user_evaluation'];

            $UEB = db('user_education_background');
            $uemap['user_id'] = $resume_user_id;
            $uemap['status'] = 1;
            $ue_list = $UEB->where($uemap)->select();
            if($ue_list){
                $result['data']['education'] = $ue_list;
            }

            $Experience = db('user_experience');
            $emap['exp_user_id'] = $resume_user_id;
            $emap['status'] = 1;
            $e_list = $Experience->where($emap)->select();
            if($e_list){
                $result['data']['experience_list'] = $e_list;
            }

            $Capacity = db('user_capacity');
            $cmap['user_id'] = $resume_user_id;
            $cmap['status'] = 1;
            $c_list = $Capacity->where($cmap)->select();
            if($c_list)
            {
                $result['data']['capacity_list'] = $c_list;
            }

            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '查无此人';
        }

        return $result;
    }

    public function resume_left()
    {
        //获取个人简历
        $access_token = input('post.access_token');
        $resume_key = input('post.resume_key');

        $interviewer_id = get_user_id_by_access_token($access_token);
        $resume_user_id = get_user_id_by_access_token($resume_key);
        $thePlatform = get_platform_by_interviewer($interviewer_id);
        $platform_id = $thePlatform['platform_id'];

        $PlatformResume = db('platform_resume');
        $pmap['resume_user_id'] = $resume_user_id;
        $pmap['interviewer_id'] = $interviewer_id;
        $pmap['platform_id'] = $platform_id;
        $thePR = $PlatformResume->where($pmap)->find();
        if($thePR)
        {
            $thePR['status'] = 'left';
            $thePR['create_date'] = date('Y-m-d G:i:s');
            $PlatformResume->update($thePR);
        }
        else
        {
            $pmap['status'] = 'left';
            $pmap['create_date'] = date('Y-m-d G:i:s');
            $PlatformResume->insert($pmap);
        }

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }

    public function resume_right()
    {
        //获取个人简历
        $access_token = input('post.access_token');
        $resume_key = input('post.resume_key');

        $interviewer_id = get_user_id_by_access_token($access_token);
        $resume_user_id = get_user_id_by_access_token($resume_key);
        $thePlatform = get_platform_by_interviewer($interviewer_id);
        $platform_id = $thePlatform['platform_id'];

        $PlatformResume = db('platform_resume');
        $pmap['resume_user_id'] = $resume_user_id;
        $pmap['interviewer_id'] = $interviewer_id;
        $pmap['platform_id'] = $platform_id;

        $thePR = $PlatformResume->where($pmap)->find();
        if($thePR)
        {
            $thePR['status'] = 'right';
            $thePR['create_date'] = date('Y-m-d G:i:s');
            $PlatformResume->update($thePR);
        }
        else
        {
            $pmap['status'] = 'right';
            $pmap['create_date'] = date('Y-m-d G:i:s');
            $PlatformResume->insert($pmap);
        }

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }

    public function resume_evalution_get()
    {
        $access_token = input('post.access_token');
        $resume_key = input('post.resume_key');
    }

    public function resume_evalution_set()
    {
        $access_token = input('post.access_token');
        $resume_key = input('post.resume_key');
    }

    public function interviewer_bind()
    {
        //面试官绑定公司
        $access_token = input('post.access_token');
        $platform_id = intval(input('post.platform_id'));
        $platform_name = input('post.platform_name');

        if(!$platform_id){
            $theP = get_platform_by_name($platform_name);
            $platform_id = $theP['platform_id'];
        }

        $user_id = get_user_id_by_access_token($access_token);

        $PI = db('platform_interviewer');
        $pmap['interviewer_id'] = $user_id;
        $pmap['platform_id'] = $platform_id;
        $thePI = $PI->where($pmap)->find();

        if($thePI)
        {
            $thePI['status'] = 1;
            $PI->update($thePI);
        }
        else
        {
            $pmap['status'] = 1;
            $PI->insert($pmap);
        }

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }

    public function interviewer_platform()
    {
        //面试官绑定公司
        $access_token = input('post.access_token');

        $user_id = get_user_id_by_access_token($access_token);

        $PI = db('platform_interviewer');
        $pmap['interviewer_id'] = $user_id;
        $pmap['status'] = 1;
        $thePI = $PI->where($pmap)->find();

        if($thePI)
        {
            $platform_id = $thePI['platform_id'];
            $thePlatform = get_platform_by_id($platform_id);
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $thePlatform;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }

        return $result;
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
