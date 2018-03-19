<?php
namespace app\knowledge\controller;

class Test
{
    public function all()
    {
		$arr = array('code' => '1');

        return json_encode($arr);
    }

}