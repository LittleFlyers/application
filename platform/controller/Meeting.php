<?php
namespace app\platform\controller;

// +----------------------------------------------------------------------
// | 招聘会接口集
// +----------------------------------------------------------------------

class Meeting
{
    public function create()
    {
        //创建招聘会
        $meeting_name = input('post.meeting_name');
        $meeting_address = input('post.meeting_address');
        $meeting_start_date = input('post.meeting_start_date');
        $meeting_end_date = input('post.meeting_end_date');

        $Meeting = db('meeting');
        $mmap['meeting_name'] = $meeting_name;
        $mmap['meeting_address'] = $meeting_address;
        $mmap['meeting_start_date'] = date($meeting_start_date);//date('Y-m-d G:i:s',strtotime($meeting_start_date));
        $mmap['meeting_end_date'] = date($meeting_end_date);//date('Y-m-d G:i:s',strtotime($meeting_end_date));
        $mmap['create_date'] = date('Y-m-d G:i:s');

        $Meeting->insert($mmap);

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }

    public function all()
    {
        //全部招聘会

        $Meeting = db('meeting');
        $meeting_list = $Meeting->select();
        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        $result['data'] = $meeting_list;
        return $result;
    }

    public function update()
    {
        //更新招聘会
    }

    public function map()
    {
        //获取招聘会地图、招聘会企业信息

        $meeting_id = intval(input('post.meeting_id'));

        $MeetingMap = db('meeting_map');
        $mmap['meeting_id'] = $meeting_id;
        $theMap = $MeetingMap->where($mmap)->find();

        if($theMap)
        {
            $Meeting = db('meeting');
            $tmap['meeting_id'] = $meeting_id;
            $theMeeting = $Meeting->where($tmap)->find();

            $result['err_code'] = 0;
            $result['err_msg'] = 'ok';
            $result['meeting_name'] = $theMeeting['meeting_name'];
            $result['data'] = $theMap;
        }
        else
        {
            $result['err_code'] = 1;
            $result['err_msg'] = '未找到该ID的区格图';
        }

        return $result;
    }

    public function map_set()
    {
        $meeting_id = intval(input('meeting_id'));
        $row_num = intval(input('row_num'));
        $col_num = intval(input('col_num'));
        $map_json = input('post.map_json');

        $MeetingMap = db('meeting_map');
        $map['meeting_id'] = $meeting_id;
        $theMap = $MeetingMap->where($map)->find();
        if($theMap)
        {
            $theMap['map_row_num'] = $row_num;
            $theMap['map_col_num'] = $col_num;
            $theMap['map_json'] = $map_json;
            $MeetingMap->update($theMap);
        }
        else
        {
            $map['map_row_num'] = $row_num;
            $map['map_col_num'] = $col_num;
            $map['map_json'] = $map_json;
            $MeetingMap->insert($map);
        }

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';

        return $result;
    }

    public function chair()
    {
        //获取招聘会格间信息

        $meeting_id = intval(input('post.meeting_id'));
        $chair_no = input('post.chair_no');

        $Map = db('meeting_platform');
        $mmap['meeting_id'] = $meeting_id;
        $mmap['chair_no'] = $chair_no;
        $theChair = $Map->where($mmap)->find();
        if($theChair)
        {
            $platform_id = $theChair['platform_id'];
            if($platform_id)
            {
                $thePlatform = get_platform_by_id($platform_id);
                $result['err_code'] = 0;
                $result['err_msg'] = 'ok';
                $result['data'] = $thePlatform;
            }
            else
            {
                $result['err_code'] = 1;
                $result['err_msg'] = '没有确定公司';
            }
        }
        else
        {
            $result['err_code'] = 2;
            $result['err_msg'] = '没有确定公司';
        }

        return $result;
    }

    public function meeting_platform()
    {
        //列出招聘会所有企业
        $meeting_id = intval(input('post.meeting_id'));
        $Map = db('meeting_platform');
        $mmap['meeting_id'] = $meeting_id;
        $platform_list = $Map->where($mmap)->order('chair_no')->select();

        for($i=0;$i<count($platform_list);$i++)
        {
            $thePlatform = get_platform_by_id($platform_list[$i]['platform_id']);
            $platform_list[$i]['platform_name'] = $thePlatform['platform_name'];
        }

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        $result['data'] = $platform_list;

        return $result;
    }

    public function chair_set()
    {
        $meeting_id = intval(input('post.meeting_id'));
        $chair_no = input('post.chair_no');
        $platform_name = input('post.platform_name');

        $Platform = db('platform');
        $pmap['platform_name'] = $platform_name;
        $thePlatform = $Platform->where($pmap)->find();
        if($thePlatform)
        {
            $platform_id = $thePlatform['platform_id'];
        }
        else
        {
            $pmap['create_date'] = date('Y-m-d G:i:s');
            $platform_id = $Platform->insertGetId($pmap);
        }
        $MeetingPlatform = db('meeting_platform');
        $mmap['chair_no'] = $chair_no;
        $mmap['meeting_id'] = $meeting_id;
        $theMP = $MeetingPlatform->where($mmap)->find();
        if($theMP)
        {
            $theMP['platform_id'] = $platform_id;
            $MeetingPlatform->update($theMP);
        }
        else
        {
            $mmap['platform_id'] = $platform_id;
            $MeetingPlatform->insert($mmap);
        }

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';

        return $result;

    }

    public function chair_sign()
    {
        //人才标注自己感兴趣的企业
        $access_token = input('post.access_token');
        $meeting_id = intval(input('post.meeting_id'));
        $platform_id = intval(input('post.platform_id'));
        $chair_no = input('post.chair_no');
        $status = input('post.status');

        $user_id = get_user_id_by_access_token($access_token);

        $MCS = db('meeting_platform_sign');
        $mmap['user_id'] = $user_id;
        $mmap['platform_id'] = $platform_id;
        $mmap['meeting_id'] = $meeting_id;
        $mmap['chair_no'] = $chair_no;
        $theSign = $MCS->where($mmap)->find();
        if($theSign)
        {
            $theSign['status'] = $status;
            $MCS->update($theSign);
        }
        else
        {
            $mmap['status'] = $status;
            $MCS->insert($mmap);
        }

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';

        return $result;
    }

    public function chair_signed()
    {
        //根据user_id获得已经被标注的企业
        $access_token = input('post.access_token');
        $meeting_id = intval(input('post.meeting_id'));

        $user_id = get_user_id_by_access_token($access_token);

        $MCS = db('meeting_platform_sign');
        $mmap['user_id'] = $user_id;
        $mmap['meeting_id'] = $meeting_id;
        $mmap['status'] = 1;
        $m_list = $MCS->where($mmap)->select();

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        $result['data'] = $m_list;

        return $result;
    }

    public function platform_sign()
    {
        //标记企业
    }
    public function platform_signed()
    {
        //列出已标记企业
    }
    public function platform_meet()
    {
        //已面试
    }
    public function platform_resume()
    {
        //投简历
    }
    public function platform_resumed()
    {
        //已投简历
    }
}