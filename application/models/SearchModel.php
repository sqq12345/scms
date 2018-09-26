<?php
/**
 * zx
 * 20180813
 * 搜索模型
 */
class SearchModel
{
	//全局搜索
	public function ModelSearchAll($keyword, $page, $max){
		$sql = "SELECT * from brand where name = '{$keyword}' and status = 1";
		$category = $GLOBALS['DB']->myquery($sql);
		if($category){
			$data['category'] = $category;
		}
		$offset = ($page-1)*$max;
		$sql = "SELECT a.id,a.name,b.*,'1' as type from re_product a LEFT JOIN (select id as goodsid,pid, img,(case region when '韩国' then '5-7天' when '武汉' then '2-3天' when '香港' then '3-5天' else '其他' end) as tag,SUBSTRING_INDEX(GROUP_CONCAT(price order by price asc),',',1) price from (select * from re_product_sku order by pid asc ,price asc)  c where `status` = 1 GROUP BY pid) b on a.id = b.pid where a.name like '%{$keyword}%' and a.status = 1 union SELECT a.id,a.name,b.*,'2' as type from re_bespeak_product a LEFT JOIN (select id as goodsid,pid,img,'可预约'as tag,SUBSTRING_INDEX(GROUP_CONCAT(price order by price asc),',',1) price from (select * from re_bespeak_product_sku order by pid asc ,price asc)  c where `status` = 1 GROUP BY pid) b on a.id = b.pid where a.name like '%{$keyword}%' and a.status = 1 LIMIT {$offset},{$max}";
		$reproduct = $GLOBALS['DB']->myquery($sql);
		if($reproduct){
			$data['product'] = $reproduct;
		}
		return $data;
	}

	//现货秒发搜索
	public function ModelSearchNowGoods($keyword, $page, $max){
		$sql = "SELECT * from brand where name = '{$keyword}' and status = 1";
		$category = $GLOBALS['DB']->myquery($sql);
		if($category){
			$data['category'] = $category;
		}
		$offset = ($page-1)*$max;
		$sql = "SELECT a.id,a.name,b.* from re_product a LEFT JOIN (select id as goodsid,pid, img,region,SUBSTRING_INDEX(GROUP_CONCAT(price order by price asc),',',1) price from (select * from re_product_sku where region = '武汉' order by pid asc ,price asc)  c where `status` = 1 GROUP BY pid) b on a.id = b.pid where a.name like '%{$keyword}%' and a.status = 1 and b.region = '武汉' LIMIT {$offset},{$max}";
		$reproduct = $GLOBALS['DB']->myquery($sql);
		if($reproduct){
			$data['product'] = $reproduct;
		}
		return $data;
	}

	//海外直邮搜索
	public function ModelSearchOutGoods($keyword, $page, $max){
		$sql = "SELECT * from brand where name = '{$keyword}' and status = 1";
		$category = $GLOBALS['DB']->myquery($sql);
		if($category){
			$data['category'] = $category;
		}
		$offset = ($page-1)*$max;
		$sql = "SELECT a.id,a.name,b.* from re_product a LEFT JOIN (select id as goodsid,pid, img,region,SUBSTRING_INDEX(GROUP_CONCAT(price order by price asc),',',1) price from (select * from re_product_sku where region <> '武汉' order by pid asc ,price asc)  c where `status` = 1 GROUP BY pid) b on a.id = b.pid where a.name like '%{$keyword}%' and a.status = 1 and b.region <> '武汉' LIMIT {$offset},{$max}";
		$reproduct = $GLOBALS['DB']->myquery($sql);
		if($reproduct){
			$data['product'] = $reproduct;
		}
		return $data;
	}

	//商品预约搜索
	public function ModelsearchBespeakGoods($keyword, $page, $max){
		$sql = "SELECT * from brand where name = '{$keyword}' and status = 1";
		$category = $GLOBALS['DB']->myquery($sql);
		if($category){
			$data['category'] = $category;
		}
		$offset = ($page-1)*$max;
		$sql = "SELECT a.id,a.name,b.* from re_bespeak_product a LEFT JOIN (select id as goodsid,pid,img,SUBSTRING_INDEX(GROUP_CONCAT(price order by price asc),',',1) price from (select * from re_bespeak_product_sku order by pid asc ,price asc)  c where `status` = 1 GROUP BY pid) b on a.id = b.pid where a.name like '%{$keyword}%' and a.status = 1 LIMIT {$offset},{$max}";
		$reproduct = $GLOBALS['DB']->myquery($sql);
		if($reproduct){
			$data['product'] = $reproduct;
		}
		return $data;
	}
}