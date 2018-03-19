<?php
namespace app\message\controller;

class Short
{
    public function verify_code()
    {
        //短信验证码
        $mobile = input('post.user_mobile');

        $verify_code = rand(1000,9999);

        $msg = "您好，记住这串数字：".$verify_code."，打死也不告诉别人！瓜大科技";

        //TODO:蓝创接口包
        $this->sendSMS($mobile, $msg);

        $result['err_code'] = 0;
        $result['err_msg'] = 'ok';
        $result['verify_code'] = $verify_code;

        return json_encode($result);
    }

    private function sendSMS( $mobile, $msg, $needstatus = 1) {

        //创蓝接口参数
        $postArr = array (
            'un' => 'N1837777',
            'pw' => '39FlmCjBtVe292',
            'msg' => $msg,
            'phone' => $mobile,
            'rd' => $needstatus
        );

        $result = $this->curlPost( 'http://sms.253.com/msg/send' , $postArr);
        return $result;
    }

    private function execResult($result){
        $result=preg_split("/[,\r\n]/",$result);
        return $result;
    }

    private function curlPost($url,$postFields){
        $postFields = http_build_query($postFields);
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postFields );
        $result = curl_exec ( $ch );
        curl_close ( $ch );
        return $result;
    }

    public function __get($name){
        return $this->$name;
    }

    public function __set($name,$value){
        $this->$name=$value;
    }
}