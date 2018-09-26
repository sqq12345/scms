<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/12
 * Time: 15:26
 */
class CartModel{

    private $filepath = 'cart';
    private $fields = 'id, user_id, pid, sku_id, product_type, num, create_time, update_time';

    public function ModelAdd($userId,$pid,$skuId,$pType,$num){
        $set = array();
        $set[] = "user_id = {$userId}";
        $set[] = "pid = {$pid}";
        $set[] = "sku_id = {$skuId}";
        $set[] = "product_type = {$pType}";
        $set[] = "num = {$num}";
        $set[] = "create_time = now()";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $set);
        return $ret;
    }

    public function ModelDelete($id){
        $where = " AND id = {$id}";
        $ret = $GLOBALS['DB']->Set_Delete(ECHO_AQL_SWITCH, 1, $this->filepath , $where);
        if($ret !== false){
            return true;
        }
        return false;
    }

    public function ModelUpdate($set, $id, $userId){
        $where = "AND id = {$id} AND user_id = {$userId}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function ModelInfoByUserIdAndSkuId($userId,$skuId,$pType){
        $dataval = array();
        $where = " AND user_id = {$userId} AND sku_id = {$skuId} AND product_type = {$pType}";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['userId'] = intval($row['user_id']);
        $dataval['skuId'] = intval($row['sku_id']);
        $dataval['pType'] = intval($row['product_type']);
        $dataval['num'] = intval($row['num']);
        return $dataval;
    }

    public function ModelGetList($userId){
        $dataval = array();
        $where = "AND user_id = {$userId} ";
        $orderby = " create_time asc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where,$orderby);
        if (empty($list)) {
            $dataval['list'] = array();
            $dataval['total'] = 0;
            $dataval['num'] = 0;
            return $dataval;
        }
        $productModel = get_load_model('reProduct');
        $skuModel = get_load_model('reProductSku');
        $specialModel = get_load_model('reSpecialProduct');
        $bespeakskuModel = get_load_model('reBespeakProductSku');
        $total = 0;
        $num = 0;
        foreach ($list as $key => $val) {
            if($val['product_type'] == 1){
                $sku = $skuModel->ModelInfoById($val['sku_id']);
                if(!empty($sku)){
                    $dataval[$key]['model'] = $sku['model'];
                    $dataval[$key]['attr'] = $sku['attr'];
                    $dataval[$key]['price'] = doubleval($sku['price']);
                    $product = $productModel->BcModelMiniInfo($sku['pid']);
                    if(!empty($product)){
                        $dataval[$key]['name'] = $product['name'];
                    }
                    $total = bcadd(bcmul($sku['price'],$val['num'],2),$total,2);
                }
            }elseif ($val['product_type'] == 2){
                $special = $specialModel->ModelFindBySkuIdAndTime($val['sku_id']);
                if(!empty($special)){
                    $dataval[$key]['name'] = $special['name'];
                    $dataval[$key]['price'] = doubleval($special['price']);
                    $sku = $skuModel->ModelInfoById($special['sku_id']);
                    if(!empty($sku)){
                        $dataval[$key]['model'] = $sku['model'];
                        $dataval[$key]['attr'] = $sku['attr'];
                    }
                    $total = bcadd(bcmul($special['price'],$val['num'],2),$total,2);
                }else{
                    $this->ModelDelete($val['id']);
                    continue;
                }
            }
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['pid'] = intval($val['pid']);
            $dataval[$key]['skuId'] = intval($val['sku_id']);
            $dataval[$key]['pType'] = intval($val['product_type']);
            $dataval[$key]['num'] = intval($val['num']);
            $num = $num + intval($val['num']);
            $data['data'.$val['product_type']]['list'][] = $dataval[$key];
            $data['data'.$val['product_type']]['type'] = $val['product_type'];
        }
        $data['total'] = doubleval($total);
        $data['num'] = intval($num);
        return $data;
    }

    public function ModelListByUserId($userId){
        $dataval = array();
        $where = " AND user_id = {$userId} ";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $key => $val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['pid'] = intval($val['pid']);
            $dataval[$key]['skuId'] = intval($val['sku_id']);
            $dataval[$key]['pType'] = intval($val['product_type']);
            $dataval[$key]['num'] = intval($val['num']);
        }
        return $dataval;
    }

    public function ModelGetListByUserId($userId){
        $dataval = array();
        $where = "AND user_id = {$userId} ";
        $orderby = " create_time asc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where,$orderby);
        if (empty($list)) {
            $dataval['list'] = array();
            $dataval['num'] = 0;
            return $dataval;
        }
        $productModel = get_load_model('reProduct');
        $skuModel = get_load_model('reProductSku');
        $specialModel = get_load_model('reSpecialProduct');
        $num = 0;
        foreach ($list as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['pid'] = intval($val['pid']);
            $dataval[$key]['skuId'] = intval($val['sku_id']);
            $dataval[$key]['pType'] = intval($val['product_type']);
            $dataval[$key]['model'] = '';
            $dataval[$key]['attr'] = '';
            $dataval[$key]['price'] = 0;
            $dataval[$key]['num'] = intval($val['num']);
            $num = $num + intval($val['num']);
            if($val['product_type'] == 1){
                $sku = $skuModel->ModelInfoById($val['sku_id']);
                if(!empty($sku)){
                    $dataval[$key]['model'] = $sku['model'];
                    $dataval[$key]['attr'] = $sku['attr'];
                    $dataval[$key]['price'] = doubleval($sku['price']);
                    $product = $productModel->BcModelMiniInfo($sku['pid']);
                    if(!empty($product)){
                        $dataval[$key]['name'] = $product['name'];
                    }
                }
            }elseif ($val['product_type'] == 2){
                $special = $specialModel->ModelFindBySkuIdAndTime($val['sku_id']);
                if(!empty($special)){
                    $dataval[$key]['name'] = $special['name'];
                    $dataval[$key]['price'] = doubleval($special['price']);
                    $sku = $skuModel->ModelInfoById($special['sku_id']);
                    if(!empty($sku)){
                        $dataval[$key]['model'] = $sku['model'];
                        $dataval[$key]['attr'] = $sku['attr'];
                    }
                }
            }elseif($pType == 3){
                $killproductModel = get_load_model('reSpecialProduct');
                $killproduct = $killproductModel->ModelSkillBySkuIdAndTime($skuId);
                if(empty($killproduct)){
                    set_return_value(SPECIAL_PRODUCT_NULL_ERROR, '');
                    return false;
                }
                $sku = $productModel->ModelInfoByIdAndPid($skuId,$pid);
                if(empty($sku)){
                    set_return_value(PRODUCT_SKU_ERROR, '');
                    return false;
                }
                $price = doubleval($killproduct['price_limit']);
                $total = bcadd(bcmul($killproduct['price_limit'],$val['num'],2),$total,2);
            }
        }
        $data = array();
        $data['list'] = $dataval;
        $data['num'] = intval($num);
        return $data;
    }

    public function ModelDeleteByPid($userId,$pid){
        $where = " AND pid = {$pid} AND user_id = {$userId}";
        $ret = $GLOBALS['DB']->Set_Delete(ECHO_AQL_SWITCH, 1, $this->filepath , $where);
        if($ret !== false){
            return true;
        }
        return false;
    }
}