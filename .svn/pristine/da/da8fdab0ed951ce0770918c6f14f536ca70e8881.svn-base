<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/27
 * Time: 14:13
 */
class ReBcBespeakProduct{

    public function addProduct($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;
        $name = inject_check(isset($data['name']) ? $data['name'] : '');   //商品名称
        $info = isset($data['info']) ? $data['info'] : '';   //商品描述
        $categoryIds = isset($data['categoryIds']) ? $data['categoryIds'] : '';//分类IDs
        $brandId = isset($data['brandId']) ? intval($data['brandId']) : 0;  //品牌ID
        $shortageDisplay = inject_check(isset($data['shortageDisplay']) ? $data['shortageDisplay'] : '');   //商品缺货显示
        $isReduceStock = isset($data['isReduceStock']) ? intval($data['isReduceStock']) : 0;  //购买后是否减少库存  1：是 0：否
        $distributionFee = isset($data['distributionFee']) ? doubleval($data['distributionFee']) : 0;  //配送费
        $hotLevel = isset($data['hotLevel']) ? intval($data['hotLevel']) : 1; //商品热门级别
        $goodIcon = isset($data['goodIcon']) ? $data['goodIcon'] : '';  //商品主图
        $goodImg = isset($data['goodImg']) ? $data['goodImg'] : '';  //商品附加图
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if(empty($name) || empty($shortageDisplay) || empty($categoryIds)){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        if($brandId > 0){
            $brandModel = get_load_model('brand');
            $brand = $brandModel->BcModelInfo($brandId);
            if(empty($brand)){
                set_return_value(BRAND_NULL, '');
                return false;
            }
        }
        $list = array();
        if(sizeof($categoryIds) > 0){
            $model = get_load_model('bespeakCategory');
            foreach ($categoryIds as $val){
                $row = $model->BcModelInfo($val);
                if(empty($row)){
                    set_return_value(CATEGORY_NULL, '');
                    return false;
                }
                if(!in_array($val,$list)){
                    array_push($list,$val);
                }
                if($row['parentId'] > 0){
                    if(!in_array($row['parentId'],$list)){
                        array_push($list,$row['parentId']);
                    }
                }
            }
        }
        $list = json_encode($list);
        $list = str_replace("[",",",$list);
        $list = str_replace("]",",",$list);
        $goodImg = str_replace('\/', '/', json_encode($goodImg));
        $model = get_load_model('reBespeakProduct');
        $ret = $model->BcModelAdd($name,$info,$list,$brandId,$shortageDisplay,$isReduceStock,$distributionFee,$hotLevel,$goodIcon,$goodImg,$userid);
        if($ret){
            $dataval['id'] = intval($ret);
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'bespeakProduct',$ret,38);
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function addSku($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;
        $pid = isset($data['pid']) ? intval($data['pid']) : 0;
        $modelName = inject_check(isset($data['model']) ? $data['model'] : '');
        $list = isset($data['list']) ? $data['list'] : '';
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if($pid == 0 || empty($list)){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('reBespeakProduct');
        $product = $model->BcModelMiniInfo($pid);
        if(empty($product)){
            set_return_value(PRODUCT_NULL_ERROR, $dataval);
            return false;
        }
        $ret = 0;
        if(sizeof($list) > 0){
            $refArray = array();
            $skuModel = get_load_model('reBespeakProductSku');
            foreach ($list as $val){
                if(empty($val['ref'])){
                    set_return_value(REF_NULL_ERROR, '');
                    return false;
                }
                $skuInfo = $skuModel->BcModelInfoByRef($val['ref']);
                if(!empty($skuInfo)){
                    set_return_value_ref(REF_ERROR, '',$val['ref']);
                    return false;
                }
                array_push($refArray,$val['ref']);
            }
            $unique_arr = array_unique($refArray);
            $repeat_arr = array_diff_assoc($refArray,$unique_arr);
            if($repeat_arr){
                set_return_value_ref(REF_REPEAT_ERROR, '',implode(',',$repeat_arr));
                return false;
            }
            $logModel = get_load_model('operationLog');
            foreach ($list as $val){
                $attr = isset($val['attr']) ? $val['attr'] : '';
                $img = isset($val['img']) ? $val['img'] : 'http://upload.hljr.com.cn/p_img_n@3x.png';
                $detailImg = isset($val['detailImg']) ? $val['detailImg'] : '';
                $ref = isset($val['ref']) ? $val['ref'] : '';
                $stock = isset($val['stock']) ? intval($val['stock']) : 0;
                $price = isset($val['price']) ? doubleval($val['price']) : 0;
                $dutyFreePrice = isset($val['dutyFreePrice']) ? doubleval($val['dutyFreePrice']) : 0;
                $costPrice = isset($val['costPrice']) ? doubleval($val['costPrice']) : 0;
                $limitTime = isset($val['limitTime']) ? intval($val['limitTime']) : 0;
                $minPurchaseNum = isset($val['minPurchaseNum']) ? intval($val['minPurchaseNum']) : 1;
                $limitNum = isset($val['limitNum']) ? intval($val['limitNum']) : 0;
                $status = isset($val['status']) ? intval($val['status']) : 1;
                if(empty($model)|| $price == 0 || $dutyFreePrice == 0 || $costPrice == 0){
                    set_return_value(WILL_FIELD_NULL, '');
                    return false;
                }

                                //后加的字段
                //会员价
                $vip_price = isset($val['vip_price']) ? doubleval($val['vip_price']) : 0;
                //税
                $tax = isset($val['tax']) ? doubleval($val['tax']) : 0;
                //重量
                $weight = isset($val['weight']) ? doubleval($val['weight']) : 0;
                //快递费
                $express_free = isset($val['express_free']) ? doubleval($val['express_free']) : 0;
                //产品级别
                $level = isset($val['level']) ? intval($val['level']) : 0;
                //是否新品推荐0默认不推荐
                $commend = isset($val['commend']) ? intval($val['commend']) : 0;
                //新品推荐排序
                $sort = isset($val['sort']) ? intval($val['sort']) : 0;
                //销量
                $sales = isset($val['sales']) ? intval($val['sales']) : 100;

                $ret = $skuModel->BcModelAdd($pid,$modelName,$attr,$img,$ref,$stock,$price,$dutyFreePrice,$costPrice,$minPurchaseNum,$limitNum,$limitTime,$status,$userid,$detailImg,$vip_price, $tax, $weight, $express_free, $level, $commend, $sort, $sales);
                $logModel->ModelAdd($userid,'bespeakProductSku',$ret,39);
            }
        }
        if($ret){
            set_return_value(RESULT_SUCCESS, $dataval);
        }else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function updateProduct($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;
        $id = isset($data['id']) ? intval($data['id']) : 0; //商品id
        $name = inject_check(isset($data['name']) ? $data['name'] : '');   //商品名称
        $info = isset($data['info']) ? $data['info'] : '';   //商品描述
        $categoryIds = isset($data['categoryIds']) ? $data['categoryIds'] : '';//分类IDs
        $brandId = isset($data['brandId']) ? intval($data['brandId']) : 0;  //品牌ID
        $shortageDisplay = inject_check(isset($data['shortageDisplay']) ? $data['shortageDisplay'] : '');   //商品缺货显示
        $isReduceStock = isset($data['isReduceStock']) ? intval($data['isReduceStock']) : 0;  //购买后是否减少库存  1：是 0：否
        $distributionFee = isset($data['distributionFee']) ? doubleval($data['distributionFee']) : 0;  //配送费
        $hotLevel = isset($data['hotLevel']) ? intval($data['hotLevel']) : 1; //商品热门级别
        $goodIcon = isset($data['goodIcon']) ? $data['goodIcon'] : '';  //商品主图
        $goodImg = isset($data['goodImg']) ? $data['goodImg'] : '';  //商品附加图
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if($id == 0 || empty($name) || empty($shortageDisplay) || empty($categoryIds)){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        if($brandId > 0){
            $brandModel = get_load_model('brand');
            $brand = $brandModel->BcModelInfo($brandId);
            if(empty($brand)){
                set_return_value(BRAND_NULL, '');
                return false;
            }
        }
        $list = array();
        if(sizeof($categoryIds) > 0){
            $model = get_load_model('bespeakCategory');
            foreach ($categoryIds as $val){
                $row = $model->BcModelInfo($val);
                if(empty($row)){
                    set_return_value(CATEGORY_NULL, '');
                    return false;
                }
                if(!in_array($val,$list)){
                    array_push($list,$val);
                }
                if($row['parentId'] > 0){
                    if(!in_array($row['parentId'],$list)){
                        array_push($list,$row['parentId']);
                    }
                }
            }
        }
        $list = json_encode($list);
        $list = str_replace("[",",",$list);
        $list = str_replace("]",",",$list);
        $goodImg = str_replace('\/', '/', json_encode($goodImg));
        $model = get_load_model('reBespeakProduct');
        $ret = $model->BcModelUpdateInfo($id,$name,$info,$list,$brandId,$shortageDisplay,$isReduceStock,$distributionFee,$hotLevel,$goodIcon,$goodImg,$userid);
        if($ret){
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'bespeakProduct',$id,40);
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function updateSku($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;
        $pid = isset($data['pid']) ? intval($data['pid']) : 0;
        $modelName = inject_check(isset($data['model']) ? $data['model'] : '');
        $list = isset($data['list']) ? $data['list'] : '';
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if(empty($list)){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $ret = 0;
        if(sizeof($list) > 0){
            $refArray = array();
            $skuModel = get_load_model('reBespeakProductSku');
            foreach ($list as $val){
                if(empty($val['ref'])){
                    set_return_value(REF_NULL_ERROR, '');
                    return false;
                }
                $skuInfo = array();
                if($val['id'] > 0){
                    $skuInfo = $skuModel->BcModelInfoByIdAndRef($val['id'],$val['ref']);
                }else{
                    $skuInfo = $skuModel->BcModelInfoByRef($val['ref']);
                }
                if(!empty($skuInfo)){
                    set_return_value_ref(REF_ERROR, '',$val['ref']);
                    return false;
                }
                array_push($refArray,$val['ref']);
            }
            $unique_arr = array_unique($refArray);
            $repeat_arr = array_diff_assoc($refArray,$unique_arr);
            if($repeat_arr){
                set_return_value_ref(REF_REPEAT_ERROR, '',implode(',',$repeat_arr));
                return false;
            }
            foreach ($list as $val){
                $id = isset($val['id']) ? intval($val['id']) : 0;
                $attr = isset($val['attr']) ? $val['attr'] : '';
                $img = isset($val['img']) ? $val['img'] : '';
                $detailImg = isset($val['detailImg']) ? $val['detailImg'] : '';
                $ref = isset($val['ref']) ? $val['ref'] : '';
                $stock = isset($val['stock']) ? intval($val['stock']) : 0;
                $price = isset($val['price']) ? doubleval($val['price']) : 0;
                $dutyFreePrice = isset($val['dutyFreePrice']) ? doubleval($val['dutyFreePrice']) : 0;
                $costPrice = isset($val['costPrice']) ? doubleval($val['costPrice']) : 0;
                $limitTime = isset($val['limitTime']) ? intval($val['limitTime']) : 0;
                $minPurchaseNum = isset($val['minPurchaseNum']) ? intval($val['minPurchaseNum']) : 1;
                $limitNum = isset($val['limitNum']) ? intval($val['limitNum']) : 0;
                $status = isset($val['status']) ? intval($val['status']) : 1;

                //后加的字段
                //会员价
                $vip_price = isset($val['vip_price']) ? doubleval($val['vip_price']) : 0;
                //税
                $tax = isset($val['tax']) ? doubleval($val['tax']) : 0;
                //重量
                $weight = isset($val['weight']) ? doubleval($val['weight']) : 0;
                //快递费
                $express_free = isset($val['express_free']) ? doubleval($val['express_free']) : 0;
                //产品级别
                $level = isset($val['level']) ? intval($val['level']) : 0;
                //是否新品推荐0默认不推荐
                $commend = isset($val['commend']) ? intval($val['commend']) : 0;
                //新品推荐排序
                $sort = isset($val['sort']) ? intval($val['sort']) : 0;
                //销量
                $sales = isset($val['sales']) ? intval($val['sales']) : 100;
                
                if(empty($attr)|| $price == 0 || $dutyFreePrice == 0){
                    set_return_value(WILL_FIELD_NULL, '');
                    return false;
                }
                if($id > 0){
                    $ret = $skuModel->BcModelUpdateSku($id,$modelName,$attr,$img,$ref,$stock,$price,$dutyFreePrice,$costPrice,$minPurchaseNum,$limitNum,$limitTime,$status,$userid,$detailImg,$vip_price, $tax, $weight, $express_free, $level, $commend, $sort, $sales);
                }else{
                    $ret = $skuModel->BcModelAdd($pid,$modelName,$attr,$img,$ref,$stock,$price,$dutyFreePrice,$costPrice,$minPurchaseNum,$limitNum,$limitTime,$status,$userid,$detailImg,$vip_price, $tax, $weight, $express_free, $level, $commend, $sort, $sales);
                }
            }
        }
        if($ret){
            set_return_value(RESULT_SUCCESS, $dataval);
        }else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function updateStatus($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? $data['id'] : 0;  //商品的ID
        $status = isset($data['status']) ? intval($data['status']) : 0; //商品状态  0：禁用，1：启用
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('reBespeakProduct');
        $set = array();
        $set[] = " status = {$status}";
        $set[] = " update_by = {$userid}";
        $ret = $model->BcModelUpdate($set,$id);
        if($ret){
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'bespeakProduct',$id,41);
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getInfo($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;
        $id = isset($data['id']) ? intval($data['id']) : 0;
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if($id == 0){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('reBespeakProduct');
        $product = $model->BcModelInfo($id);
        if(empty($product)){
            set_return_value(PRODUCT_NULL_ERROR, $dataval);
            return false;
        }
        $dataval['info'] = $product;
        $skuModel = get_load_model('reBespeakProductSku');
        $skuList = $skuModel->BcModelListByPid($id);
        $dataval['skuList'] = $skuList;
        $dataval['model']['name'] = '';
        if(!empty($skuList) && sizeof($skuList) > 0){
            $dataval['model']['name'] = $skuList[0]['model'];
        }
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function getList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $page = isset($_POST['page']) ? $_POST['page'] : 0;
        $limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
        $name = inject_check(isset($data['name']) ? $data['name'] : '');
        $brandId = isset($data['brandId']) ? intval($data['brandId']) : 0;  //品牌ID
        $categoryId = isset($data['categoryId']) ? intval($data['categoryId']) : 0;  //分类ID
        $status = isset($data['status']) ? intval($data['status']) : -1;
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $offset = 0;
        if($page > 0){
            $offset = $limit * ($page - 1);
        }
        $model = get_load_model('reBespeakProduct');
        $dataval = $model->BcModelList($name,$brandId,$categoryId,$status,$offset,$limit);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval['list'],$dataval['total']);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}