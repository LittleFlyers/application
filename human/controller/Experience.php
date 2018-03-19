<?php
namespace app\human\controller;

class Experience
{
    public function all()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);

        $Experience = db('user_experience');
        $emap['exp_user_id'] = $user_id;
        $emap['status'] = 1;
        $e_list = $Experience->where($emap)->select();

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        $result['data'] = $e_list;
        return $result;
    }

    public function one()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $exp_id = intval(input('post.exp_id'));

        $Experience = db('user_experience');
        $emap['exp_id'] = $exp_id;
        $emap['exp_user_id'] = $user_id;
        $emap['status'] = 1;
        $theData = $Experience->where($emap)->find();

        if($theData)
        {
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $theData;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }

        return $result;
    }

    public function add()
    {
        //添加个人经历
        $access_token = input('post.access_token');
        $exp_user_id = get_user_id_by_access_token($access_token);

        $exp_name = input('post.exp_name');
        $exp_start_date = input('post.exp_start_date');
        $exp_end_date = input('post.exp_end_date');
        $exp_address = input('post.exp_address');
        $exp_keyword = input('post.exp_keyword');
        $exp_role = input('post.exp_role');
        $exp_description = input('post.exp_description');

        $Experience = db('user_experience');
        $emap['exp_user_id'] = $exp_user_id;
        $emap['exp_name'] = $exp_name;
        $emap['exp_start_date'] = $exp_start_date;
        $emap['exp_end_date'] = $exp_end_date;
        $emap['exp_address'] = $exp_address;
        $emap['exp_role'] = $exp_role;
        $emap['exp_keyword'] = $exp_keyword;
        $emap['exp_description'] = $exp_description;
        $emap['status'] = 1;
        $emap['create_date'] = date('Y-m-d G:i:s');
        $Experience->insert($emap);

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }

    public function update()
    {
        //更新某个个人经历
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $exp_id = intval(input('post.exp_id'));
        $exp_name = input('post.exp_name');
        $exp_start_date = input('post.exp_start_date');
        $exp_end_date = input('post.exp_end_date');
        $exp_address = input('post.exp_address');
        $exp_keyword = input('post.exp_keyword');
        $exp_role = input('post.exp_role');
        $exp_description = input('post.exp_description');

        $Experience = db('user_experience');
        $emap['exp_id'] = $exp_id;
        $emap['exp_user_id'] = $user_id;
        $emap['exp_name'] = $exp_name;
        $emap['exp_start_date'] = $exp_start_date;
        $emap['exp_end_date'] = $exp_end_date;
        $emap['exp_address'] = $exp_address;
        $emap['exp_keyword'] = $exp_keyword;
        $emap['exp_role'] = $exp_role;
        $emap['exp_description'] = $exp_description;
        $emap['status'] = 1;
        $emap['create_date'] = date('Y-m-d G:i:s');
        $Experience->update($emap);

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }

    public function delete()
    {
        //删除某个自己的经历
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $exp_id = intval(input('post.exp_id'));

        $Experience = db('user_experience');
        $emap['exp_id'] = $exp_id;
        $emap['status'] = 0;
        $Experience->update($emap);

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }

    public function collect()
    {
        //收藏某个经历
        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }

    public function capacity_bind()
    {
        //给经历绑定、解绑能力。
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $exp_id = intval(input('post.exp_id'));
        $cap_id = intval(input('post.cap_id'));
        $status = intval(input('post.status'));

        $UECB = db('user_exp_cap_bind');
        $bmap['exp_id'] = $exp_id;
        $bmap['cap_id'] = $cap_id;
        $bmap['user_id'] = $user_id;
        $theBind = $UECB->where($bmap)->find();
        if($theBind)
        {
            $theBind['status'] = $status;
            $UECB->update($theBind);
        }
        else
        {
            $bmap['status'] = $status;
            $UECB->insert($bmap);
        }

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }

    public function capacity_bound()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $exp_id = intval(input('post.exp_id'));

        //TODO:找出全部能力，以及绑定的能力。
        $UC = db('user_capacity');
        $ucmap['user_id'] = $user_id;
        $ucmap['status'] = 1;
        $c_list = $UC->where($ucmap)->select();

        $UECB = db('user_exp_cap_bind');

        if($c_list)
        {
            for($i=0;$i<count($c_list);$i++)
            {
                $bmap['user_id'] = $user_id;
                $bmap['exp_id'] = $exp_id;
                $bmap['cap_id'] = $c_list[$i]['data_id'];

                $bmap['status'] = 1;
                $theBind = $UECB->where($bmap)->find();
                if($theBind)
                {
                    $c_list[$i]['is_bound'] = 1;
                }
                else
                {
                    $c_list[$i]['is_bound'] = 0;
                }
            }
        }

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        $result['data'] = $c_list;

        return $result;
    }
}