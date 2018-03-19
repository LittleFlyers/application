<?php

namespace app\message\controller;

class Subscribe
{
    public function send()
    {
        //向所有订阅的用推送信息。

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        return $result;
    }
}