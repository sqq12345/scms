<?php
/**
 * 活动页面
 * User: zx
 * Date: 2018/4/9
 * Time: 16:49
 */
class ActivityModel{
    //首页活动
    public function getActivityList($page, $max){
        $limit = ($page - 1) * $max;
        $sql = "SELECT * from activity_url_jump where status = 1 order by sort asc limit {$limit}, {$max}";
        $activity = $GLOBALS['DB']->myquery($sql);
        if($activity){
            $data['activity'] = $activity;
        }
        return $data;
    }

    //3大商品分类2级品牌页面上部轮播图
    public function getHeaderBanner($landtype, $page, $max){
        $limit = ($page - 1) * $max;
        $sql = "SELECT * from product_banner where type = {$landtype} and status = 1 order by sort asc limit {$limit}, {$max}";
        $banner = $GLOBALS['DB']->myquery($sql);
        if($banner){
            $data['banner'] = $banner;
        }
        return $data;
    }

    //根据活动id获得活动轮播图
    public function getActivityBanner($aid, $page, $max){
        $limit = ($page - 1) * $max;
        $sql = "SELECT * from activity_banner where aid = {$aid} and status = 1 order by sort asc limit {$limit}, {$max}";
        $banner = $GLOBALS['DB']->myquery($sql);
        if($banner){
            $data['banner'] = $banner;
        }
        return $data;
    }

    //根据活动id获得活动商品列表
    public function getActivityProduct($aid, $page, $max){
        $limit = ($page - 1) * $max;
        $sql = "SELECT * from activity_goods where aid = {$aid} and status = 1 order by sort asc limit {$limit}, {$max}";
        $product = $GLOBALS['DB']->myquery($sql);
        foreach ($product as $key => $value) {
            if($value['type'] == 1 || $value['type'] == 2){
                $sql = "SELECT b.price,b.attr,b.img,c.name,d.name as goodsname from re_product_sku b LEFT JOIN re_product c on b.pid = c.id LEFT JOIN brand d on c.brand_id = d.id where b.id = {$value['goodsid']}";
            }elseif ($value['type'] == 3) {
                $sql = "SELECT b.price,b.attr,b.img,c.name,d.name as goodsname from re_bespeak_product_sku b LEFT JOIN re_bespeak_product c on b.pid = c.id LEFT JOIN brand d on c.brand_id = d.id where b.id = {$value['goodsid']}";
            }
            $goods = $GLOBALS['DB']->myquery($sql);
            if($goods){
                $product[$key]['price'] = $goods[0]['price'];
                $product[$key]['attr'] = $goods[0]['attr'];
                $product[$key]['img'] = $goods[0]['img'];
                $product[$key]['name'] = $goods[0]['name'];
                $product[$key]['goodsname'] = $goods[0]['goodsname'];
            }
        }
        if($product){
            $data['product'] = $product;
        }
        return $data;
    }


    //添加二级页面各分类下的轮播图
    public function addHeaderBanner($title, $img, $url, $sort, $status, $landtype){
        $setarray = array();
        $setarray[] = "title = '{$title}'";
        $setarray[] = "img = '{$img}'";
        $setarray[] = "url = '{$url}'";
        $setarray[] = "sort = {$sort}";
        $setarray[] = "type = {$landtype}";
        $setarray[] = "status = {$status}";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, 'product_banner', $setarray);
        return $ret;
    }


    //更新
    public function updateHeaderBanner($id, $title, $img, $url, $sort, $status, $landtype){
        $setarray = array();
        if (!empty($title)) $setarray[] = "title = '{$title}'";
        if (!empty($img)) $setarray[] = "img = '{$img}'";
        if (!empty($url)) $setarray[] = "url = '{$url}'";
        if (!empty($sort)) $setarray[] = "sort = {$sort}";
        if (!empty($status)) $setarray[] = "status = {$status}";
        if (!empty($landtype)) $setarray[] = "landtype = {$landtype}";

        $where = " AND id = {$id}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, 'product_banner', $setarray, $where);
        return $ret;
    }

}
