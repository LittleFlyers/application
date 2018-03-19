<?php
namespace app\human\controller;

// +----------------------------------------------------------------------
// | 个人基本信息接口集
// +----------------------------------------------------------------------

class Basic
{
    public function info_set()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);

        $user_truename = input('post.user_truename');
        $user_sex = input('post.user_sex');
        $user_birth = input('post.user_birth');
        $user_minzu = input('post.user_minzu');

        $UserInfo = db('user_info');
        $umap['user_id'] = $user_id;
        $theUI = $UserInfo->where($umap)->find();

        if($theUI)
        {
            $theUI['user_truename'] = $user_truename;
            $theUI['user_sex'] = $user_sex;
            $theUI['user_minzu'] = $user_minzu;
            $theUI['user_birth'] = $user_birth;
            $UserInfo->update($theUI);
        }
        else
        {
            $umap['user_truename'] = $user_truename;
            $umap['user_sex'] = $user_sex;
            $umap['user_minzu'] = $user_minzu;
            $umap['user_birth'] = $user_birth;
            $UserInfo->insert($umap);
        }

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        $result['user_id'] = $user_id;
        return $result;
    }

    public function info_get()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);

        $UserInfo = db('user_info');
        $umap['user_id'] = $user_id;
        $theUI = $UserInfo->where($umap)->find();

        if($theUI)
        {
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data']['user_truename'] = $theUI['user_truename'];
            $result['data']['user_minzu'] = $theUI['user_minzu'];
            $result['data']['user_birth'] = $theUI['user_birth'];
            $result['data']['user_sex'] = $theUI['user_sex'];
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }

        return $result;
    }

    public function contact_get()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);

        $UserInfo = db('user_info');
        $umap['user_id'] = $user_id;
        $theUI = $UserInfo->where($umap)->find();

        if($theUI)
        {
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data']['user_mobile'] = $theUI['user_mobile'];
            $result['data']['user_email'] = $theUI['user_email'];
            $result['data']['user_address'] = $theUI['user_address'];
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }

        return $result;
    }

    public function contact_set()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);

        $user_mobile = input('post.user_mobile');
        $user_email = input('post.user_email');
        $user_address = input('post.user_address');

        $UserInfo = db('user_info');
        $umap['user_id'] = $user_id;
        $theUI = $UserInfo->where($umap)->find();

        if($theUI)
        {
            $theUI['user_mobile'] = $user_mobile;
            $theUI['user_email'] = $user_email;
            $theUI['user_address'] = $user_address;
            $UserInfo->update($theUI);
        }
        else
        {
            $umap['user_mobile'] = $user_mobile;
            $umap['user_email'] = $user_email;
            $umap['user_address'] = $user_address;
            $UserInfo->insert($umap);
        }

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }

    public function self_evalution_get()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);

        $UserInfo = db('user_info');
        $umap['user_id'] = $user_id;
        $theInfo = $UserInfo->where($umap)->find();
        if($theInfo)
        {
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['content'] = $theInfo['user_evaluation'];
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }
        return $result;
    }

    public function self_evalution_set()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);

        $content = input('post.content');

        $UserInfo = db('user_info');
        $umap['user_id'] = $user_id;
        $theInfo = $UserInfo->where($umap)->find();
        if($theInfo)
        {
            $theInfo['user_evaluation'] = $content;
            $UserInfo->update($theInfo);
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '请先填写基本信息';
        }
        return $result;
    }

    public function headpic()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $UserInfo = db('user_info');
        $umap['user_id'] = $user_id;
        $theInfo = $UserInfo->where($umap)->find();
        if($theInfo)
        {
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $theInfo['user_headpic'];
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }
        return $result;
    }

    public function headpic_set()
    {
        //TODO:上传违停照片
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $access_token = input('post.client');
            $user_id = get_user_id_by_access_token($access_token);

            $UserHeadpic = db('user_headpic');
            $UserInfo = db('user_info');

            foreach ( $_FILES as $name=>$file ) {

                $fn=$access_token;
                $ft=strrpos($fn,'.',0);
                $fm=substr($fn,0,$ft);
                $fe=substr($fn,$ft);
                $fp='C:/xampp/htdocs/Interview/pic/'.$fn;
                $fi=1;
                while( file_exists($fp) ) {
                    $fn=$fm.'['.$fi.']'.$fe;
                    $fp='C:/xampp/htdocs/Interview/pic/'.$fn;
                    $fi++;
                }

                move_uploaded_file($file['tmp_name'],$fp);

                $pmap['pic_url'] = 'http://weixin.kuanxy.com/Interview/pic/'.$fn;
                $pmap['user_id'] = $user_id;
                $pmap['create_date'] = date('Y-m-d G:i:s');
                $UserHeadpic->insert($pmap);

                $imap['user_id'] = $user_id;
                $imap['user_headpic'] = 'http://weixin.kuanxy.com/Interview/pic/'.$fn;
                $UserInfo->update($imap);
            }
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';

            return json_encode($result);
        }else{
            return json_encode(['error'=>'Unsupport GET request!']);
        }
    }

    public function city_get()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $UserInfo = db('user_info');
        $umap['user_id'] = $user_id;
        $theInfo = $UserInfo->where($umap)->find();
        if($theInfo)
        {
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data']['user_city1'] = $theInfo['user_city1'];
            $result['data']['user_city2'] = $theInfo['user_city2'];
            $result['data']['user_city3'] = $theInfo['user_city3'];
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }
        return $result;
    }

    public function city_set()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);

        $user_city1 = input('post.user_city1');
        $user_city2 = input('post.user_city2');
        $user_city3 = input('post.user_city3');

        $UserInfo = db('user_info');
        $umap['user_id'] = $user_id;
        $theInfo = $UserInfo->where($umap)->find();
        if($theInfo)
        {
            $theInfo['user_city1'] = $user_city1;
            $theInfo['user_city2'] = $user_city2;
            $theInfo['user_city3'] = $user_city3;
            $UserInfo->update($theInfo);
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '请先填写基本信息';
        }
        return $result;
    }

    public function is_full()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);

        $UserInfo = db('user_info');
        $UserEducation = db('user_education_background');
        $UserCapacity = db('user_capacity');
        $UserExperience = db('user_experience');

        $umap['user_id'] = $user_id;
        $theUser = $UserInfo->where($umap)->find();

        if($theUser)
        {
            if($theUser['user_truename'])
            {
                $result['data']['resume_name'] = 1;
            }
            if($theUser['user_mobile'])
            {
                $result['data']['resume_phone'] = 1;
            }
            if($theUser['user_headpic'])
            {
                $result['data']['resume_headpic'] = 1;
            }
            if($theUser['user_city1']||$theUser['user_city2']||$theUser['user_city3'])
            {
                $result['data']['resume_city'] = 1;
            }
            if($theUser['user_evaluation'])
            {
                $result['data']['resume_evaluation'] = 1;
            }

            $umap['status'] = 1;
            $theEducation = $UserEducation->where($umap)->find();
            if($theEducation)
            {
                $result['data']['resume_education'] = 1;
            }

            $cmap['user_id'] = $user_id;
            $cmap['status'] = 1;
            $theCapacity = $UserCapacity->where($cmap)->find();
            if($theCapacity)
            {
                $result['data']['resume_capacity'] = 1;
            }

            $emap['exp_user_id'] = $user_id;
            $emap['status'] = 1;
            $theExperience = $UserExperience->where($emap)->find();
            if($theExperience)
            {
                $result['data']['resume_experience'] = 1;
            }

            $result['err_code'] = 1;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }

        return $result;
    }
}