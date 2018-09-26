<?php
/**
 * function:搜索
 * User: zx
 * Date: 2018/8/13
 * Time: 14:20
 */
class Search
{
	//搜索全网品牌和商品
	public function searchAll($data){
		$keyword = isset($data['keyword']) ? trim($data['keyword']) : '';
		$page = isset($data['page']) ? intval($data['page']) : 1;
		$max = isset($data['max']) ? intval($data['max']) : 10;
		$search = get_load_model('Search');
		$dataval = $search->ModelSearchAll($keyword, $page, $max);
		if (!empty($dataval)) {
			set_return_value(RESULT_SUCCESS, $dataval);
		} else {
			set_return_value(RESULT_ERROR_NULL, $dataval);
		}
	}

	//现货秒发搜索
	public function searchNowGoods($data){
		$keyword = isset($data['keyword']) ? trim($data['keyword']) : '';
		$page = isset($data['page']) ? intval($data['page']) : 1;
		$max = isset($data['max']) ? intval($data['max']) : 10;
		$search = get_load_model('Search');
		$dataval = $search->ModelSearchNowGoods($keyword, $page, $max);
		if (!empty($dataval)) {
			set_return_value(RESULT_SUCCESS, $dataval);
		} else {
			set_return_value(RESULT_ERROR_NULL, $dataval);
		}
	}

	//海外直邮搜索
	public function searchOutGoods($data){
		$keyword = isset($data['keyword']) ? trim($data['keyword']) : '';
		$page = isset($data['page']) ? intval($data['page']) : 1;
		$max = isset($data['max']) ? intval($data['max']) : 10;
		$search = get_load_model('Search');
		$dataval = $search->ModelSearchOutGoods($keyword, $page, $max);
		if (!empty($dataval)) {
			set_return_value(RESULT_SUCCESS, $dataval);
		} else {
			set_return_value(RESULT_ERROR_NULL, $dataval);
		}
	}

	//商品预约搜索
	public function searchBespeakGoods($data){
		$keyword = isset($data['keyword']) ? trim($data['keyword']) : '';
		$page = isset($data['page']) ? intval($data['page']) : 1;
		$max = isset($data['max']) ? intval($data['max']) : 10;
		$search = get_load_model('Search');
		$dataval = $search->ModelsearchBespeakGoods($keyword, $page, $max);
		if (!empty($dataval)) {
			set_return_value(RESULT_SUCCESS, $dataval);
		} else {
			set_return_value(RESULT_ERROR_NULL, $dataval);
		}
	}
}