<?php
namespace app\human\controller;

class Education
{
    public function all()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $EducationBackground = db('user_education_background');
        $emap['user_id'] = $user_id;
        $emap['status'] = 1;
        $elist = $EducationBackground->where($emap)->select();

        if($elist)
        {
            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['data'] = $elist;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '暂无';
        }

        return $result;
    }

    public function one()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $education_id = intval(input('post.education_id'));

        $EducationBackground = db('user_education_background');
        $emap['education_id'] = $education_id;
        $emap['status'] = 1;
        $theData = $EducationBackground->where($emap)->find();

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        $result['data'] = $theData;

        return $result;
    }

    public function add()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $start_date = input('post.start_date');
        $end_date = input('post.end_date');
        $university = input('post.university');
        $school = input('post.school');
        $major = input('post.major');
        $degree = input('post.degree');
        $chengji_rank = input('post.chengji_rank');

        $EducationBackground = db('user_education_background');
        $emap['user_id'] = $user_id;
        $emap['start_date'] = $start_date;
        $emap['end_date'] = $end_date;
        $emap['university'] = $university;
        $emap['school'] = $school;
        $emap['major'] = $major;
        $emap['degree'] = $degree;
        $emap['chengji_rank'] = $chengji_rank;
        $emap['status'] = 1;
        $EducationBackground->insert($emap);

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }

    public function update()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $status = intval(input('post.status'));
        $start_date = input('post.start_date');
        $end_date = input('post.end_date');
        $university = input('post.university');
        $school = input('post.school');
        $major = input('post.major');
        $degree = input('post.degree');
        $chengji_rank = input('post.chengji_rank');
        $education_id = intval(input('post.education_id'));

        $EducationBackground = db('user_education_background');
        $emap['education_id'] = $education_id;
        $emap['user_id'] = $user_id;
        $emap['start_date'] = $start_date;
        $emap['end_date'] = $end_date;
        $emap['university'] = $university;
        $emap['school'] = $school;
        $emap['major'] = $major;
        $emap['degree'] = $degree;
        $emap['chengji_rank'] = $chengji_rank;
        $emap['status'] = $status;
        $EducationBackground->update($emap);

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }

    public function delete()
    {
        $access_token = input('post.access_token');
        $user_id = get_user_id_by_access_token($access_token);
        $education_id = intval(input('post.education_id'));

        $EducationBackground = db('user_education_background');
        $emap['education_id'] = $education_id;
        $emap['status'] = 0;
        $EducationBackground->update($emap);

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }
}