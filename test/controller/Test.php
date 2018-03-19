<?php
namespace app\test\controller;

class Test
{
    public function test()
    {
        $data = input('get.data');

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        $result['data'] = $data."hehhehehe";

        return $result;
    }
}