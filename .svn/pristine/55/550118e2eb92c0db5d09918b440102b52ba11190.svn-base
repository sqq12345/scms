<?php
/**
 * function:新品推荐
 * User: zx
 * Date: 2018/8/13
 * Time: 14:20
 */
class NewGoods
{
	//搜索全网品牌和商品
	public function newGoodsList(){
		$newgoods = get_load_model('NewGoods');
		$dataval = $newgoods->ModelNewGoodsList();
		if (!empty($dataval)) {
			set_return_value(RESULT_SUCCESS, $dataval);
		} else {
			set_return_value(RESULT_ERROR_NULL, $dataval);
		}
	}


	//更新排序
	public function updateSort($data){
		$id = isset($data['id']) ? $data['id'] : 0;  //商品的ID
		$sort = isset($data['sort']) ? intval($data['sort']) : 0;
		$goodstype = isset($data['goodstype']) ? intval($data['goodstype']) : 0;
		$newgoods = get_load_model('NewGoods');
		$res = $newgoods->ModelUpdateSort($id, $sort, $goodstype);
		if($res){
			set_return_value(RESULT_SUCCESS, $res);
		}else{
			set_return_value(RESULT_ERROR_NULL, $res);
		}
	}

	//删除推荐
	public function delCommend($data){
		$id = isset($data['id']) ? $data['id'] : 0;  //商品的ID
		$goodstype = isset($data['goodstype']) ? intval($data['goodstype']) : 0;
		$newgoods = get_load_model('NewGoods');
		$res = $newgoods->ModelDelCommend($id, $goodstype);
		if($res){
			set_return_value(RESULT_SUCCESS, $res);
		}else{
			set_return_value(RESULT_ERROR_NULL, $res);
		}
	}

	//销量排行
	public function salesSortList($data){
		$allmax = isset($data['allmax']) ? intval($data['allmax']) : 20;
		$weekmax = isset($data['weekmax']) ? intval($data['weekmax']) : 20;
		$monthmax = isset($data['monthmax']) ? intval($data['monthmax']) : 20;
		$newgoods = get_load_model('NewGoods');
		$dataval = $newgoods->ModelSalesSortList($allmax, $weekmax, $monthmax);
		if (!empty($dataval)) {
			set_return_value(RESULT_SUCCESS, $dataval);
		} else {
			set_return_value(RESULT_ERROR_NULL, $dataval);
		}
	}
}