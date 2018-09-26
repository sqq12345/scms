<?php
/**
 * Created by PhpStorm.
 * 上传图片
 * User: huiyong.yu
 * Date: 2018/4/18
 * Time: 16:44
 */
class Upload{

    public function upload(){
        $dataval = array();
        $auth = new \Qiniu\Auth(ACCESS_KEY,SECRET_KEY);
        $bucket = 'hljr';
        $token = $auth->uploadToken($bucket);
//        $uploadMgr = new \Qiniu\Storage\UploadManager();
//        $return = $uploadMgr->putFile($token,"ceshi222","C:/Users/zhouence/Pictures/Camera Roll/11.jpg");
        $dataval['uploadToken'] = $token;
        set_return_value(RESULT_SUCCESS, $dataval);
    }
}