<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/27
 * Time: 10:54
 */
class ReBcProductSku{
    public function updateStatus($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? $data['id'] : 0;  //商品sku的id
        $status = isset($data['status']) ? intval($data['status']) : 0; //商品sku状态  0：禁用，1：启用
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('reProductSku');
        $set = array();
        $set[] = " status = {$status}";
        $set[] = " update_by = {$userid}";
        $ret = $model->BcModelUpdate($set,$id);
        if($ret){
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'productSku',$id,47);
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }
}