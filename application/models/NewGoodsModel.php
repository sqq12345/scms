<?php
/**
 * function:新品推荐
 * User: zx
 * Date: 2018/8/13
 * Time: 14:20
 */
class NewGoodsModel
{
	//新品推荐列表
	public function ModelNewGoodsList(){
		$sql = "SELECT a.id,a.attr,a.price,a.vip_price,a.img,a.sort,(case a.region when '武汉' then '现货秒发' when '香港' then '海外直邮' else '其他' end) as type,'1' as ptype,b.name as goodsname,c.name as brandname from re_product_sku a LEFT JOIN re_product b on a.pid = b.id LEFT JOIN brand c on b.brand_id = c.id where a.commend = 1 union SELECT a.id,a.attr,a.price,a.vip_price,a.img,a.sort,'预约爆款' as type,'2' as ptype,b.name as goodsname,c.name as brandname from re_bespeak_product_sku a LEFT JOIN re_bespeak_product b on a.pid = b.id LEFT JOIN brand c on b.brand_id = c.id where a.commend = 1 ORDER BY sort DESC";
		$newgooslist = $GLOBALS['DB']->myquery($sql);
		if($newgooslist){
			$data['newgooslist'] = $newgooslist;
		}
		return $data;
	}

	//更新sort
	public function ModelUpdateSort($id, $sort, $goodstype){
        $setarray[] = "sort = {$sort}";
        $where = " AND id = {$id}";
        if($goodstype == 1){
        	$filepath = 're_product_sku';
        }elseif ($goodstype == 2) {
        	$filepath = 're_bespeak_product_sku';
        }
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $filepath, $setarray, $where);
        if($ret !== false){
            return true;
        }
        return false;
    }

	//删除推荐
    public function ModelDelCommend($id, $goodstype){
        $setarray[] = "sort = 0";
        $setarray[] = "commend = 0";
        $where = " AND id = {$id}";
        if($goodstype == 1){
        	$filepath = 're_product_sku';
        }elseif ($goodstype == 2) {
        	$filepath = 're_bespeak_product_sku';
        }
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $filepath, $setarray, $where);
        if($ret !== false){
            return true;
        }
        return false;
    }

    //销量排行
    public function ModelSalesSortList($allmax, $weekmax, $monthmax){
        $sql = "SELECT a.id,a.attr,a.price,a.vip_price,a.img,a.allsales,b.name as goodsname,c.name as brandname,'1' as type from re_product_sku a LEFT JOIN re_product b on a.pid = b.id LEFT JOIN brand c on b.brand_id = c.id union SELECT a.id,a.attr,a.price,a.vip_price,a.img,a.allsales,b.name as goodsname,c.name as brandname,'2' as type from re_bespeak_product_sku a LEFT JOIN re_bespeak_product b on a.pid = b.id LEFT JOIN brand c on b.brand_id = c.id ORDER BY allsales DESC LIMIT 0, {$allmax}";
        $allsales = $GLOBALS['DB']->myquery($sql);
        if($allsales){
            $data['allsales'] = $allsales;
        }
        $sql = "SELECT a.id,a.attr,a.price,a.vip_price,a.img,a.monthsales,b.name as goodsname,c.name as brandname,'1' as type from re_product_sku a LEFT JOIN re_product b on a.pid = b.id LEFT JOIN brand c on b.brand_id = c.id union SELECT a.id,a.attr,a.price,a.vip_price,a.img,a.monthsales,b.name as goodsname,c.name as brandname,'2' as type from re_bespeak_product_sku a LEFT JOIN re_bespeak_product b on a.pid = b.id LEFT JOIN brand c on b.brand_id = c.id ORDER BY monthsales DESC LIMIT 0, {$monthmax}";
        $monthsales = $GLOBALS['DB']->myquery($sql);
        if($monthsales){
            $data['monthsales'] = $monthsales;
        }
        $sql = "SELECT a.id,a.attr,a.price,a.vip_price,a.img,a.weeksales,b.name as goodsname,c.name as brandname,'1' as type from re_product_sku a LEFT JOIN re_product b on a.pid = b.id LEFT JOIN brand c on b.brand_id = c.id union SELECT a.id,a.attr,a.price,a.vip_price,a.img,a.weeksales,b.name as goodsname,c.name as brandname,'2' as type from re_bespeak_product_sku a LEFT JOIN re_bespeak_product b on a.pid = b.id LEFT JOIN brand c on b.brand_id = c.id ORDER BY weeksales DESC LIMIT 0, {$weekmax}";
        $weeksales = $GLOBALS['DB']->myquery($sql);
        if($weeksales){
            $data['weeksales'] = $weeksales;
        }
        return $data;
    }
}