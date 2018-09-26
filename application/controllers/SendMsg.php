<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/13
 * Time: 9:02
 */
class SendMsg{
    public function send($data){
        $mail = new Smtps(
            [
                'debug_mode' => false,
                'connection' => [
                    'host' => 'smtp.exmail.qq.com',
                    'port' => '465',
                    'secure' => 'ssl', // null, 'ssl', or 'tls'
                    'auth' => true, // true if authorization required
                    'user' => 'post@dig-data.com',
                    'pass' => '12345Asd',
                ],
                'localhost' => 'smtp.exmail.qq.com', // rename to the URL you want as origin of email
            ]
        );
        $mail->from('post@dig-data.com', ''); // email is required, name is optional
        $mail->clearto();
        $mail->to("info@dig-data.com");
        $title = $data['title'];
        $content = $data['content'];
        $mail->subject($title);
        $mail->body($content);
        $result = $mail->send();
        if($result){
            set_return_value(RESULT_SUCCESS, '');
        }else{
            set_return_value(DEFEATED_ERROR, '');
        }
    }
}