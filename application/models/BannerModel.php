<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/9
 * Time: 14:56
 */
class BannerModel{
    private $filepath = 'banner';

    private $fields = 'id, title, img, url, priority, create_time, update_time, status';

    public function ModelList(){
        $dataval = array();
        $where = "AND status = 1 ";
        print_r($GLOBALS['DB']);die;
        $bannerlist = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where,'priority desc');
        if (empty($bannerlist)) {
            return $dataval;
        }
        foreach ($bannerlist as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['title'] = $val['title'];
            $dataval[$key]['img'] = $val['img'];
            $dataval[$key]['url'] = $val['url'];
        }
        return $dataval;
    }

    public function BcModelAdd($title,$img,$url,$priority,$status){
        $setarray = array();
        $setarray[] = "title = '{$title}'";
        $setarray[] = "img = '{$img}'";
        $setarray[] = "url = '{$url}'";
        $setarray[] = "priority = {$priority}";
        $setarray[] = "create_time = now()";
        $setarray[] = "status = {$status}";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $setarray);
        return $ret;
    }

    public function BcModelUpdate($id,$title,$img,$url,$priority){
        $setarray = array();
        if (!empty($title)) $setarray[] = "title = '{$title}'";
        if (!empty($img)) $setarray[] = "img = '{$img}'";
        if (!empty($url)) $setarray[] = "url = '{$url}'";
        if (!empty($priority)) $setarray[] = "priority = {$priority}";

        $where = " AND id = {$id}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        return $ret;
    }

    public function BcModelInfo($id) {
        $dataval = array();
        $where = " AND id = {$id} AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        print_r($row);die();
        if (empty($row)) {
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['title'] = $row['title'];
        $dataval['img'] = $row['img'];
        $dataval['url'] = $row['url'];
        $dataval['priority'] = intval($row['priority']);
        $dataval['createTime'] = $row['create_time'];
        $dataval['status'] = $row['status'];
        return $dataval;
    }

    public function BcModelList($offset,$max){
        $dataval = array();
        $where = "AND status = 1 ";
        $limit = " LIMIT {$offset}, {$max}";
        $orderby = "priority desc";
        $bannerlist = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where,$orderby,$limit);
        if (empty($bannerlist)) {
            return $dataval;
        }
        foreach ($bannerlist as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['title'] = $val['title'];
            $dataval[$key]['img'] = $val['img'];
            $dataval[$key]['url'] = $val['url'];
            $dataval[$key]['priority'] = intval($val['priority']);
            $dataval[$key]['status'] = intval($val['status']);
        }
        $data = array();
        $total = $GLOBALS['DB']->getSelectCount(ECHO_AQL_SWITCH,"*",$this->filepath,$where);
        $data['total'] = intval($total);
        $data['list'] = $dataval;
        return $data;
    }

    public function BcModelDelete($ids){
        $ids = json_encode($ids);
        $ids = str_replace("[","(",$ids);
        $ids = str_replace("]",")",$ids);
        $setarray = array();
        $setarray[] =  "status = 0";
        $where = " AND id in {$ids}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        if($ret !== false){
            return true;
        }
        return false;
    }

    public function BcModelUpdateStatus($id,$status){
        $setarray[] =  "status = {$status}";
        $where = " AND id = {$id}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        if($ret !== false){
            return true;
        }
        return false;
    }

    //随机跳转取值
    public function getRandomUrlJupm($max){
        $sql = "SELECT * from icon_url_jump limit 0,{$max}";
        $res = $GLOBALS['DB']->myquery($sql);
        if($res){
            $data = $res;
        }
        return $data;
    }

    //增加热门推荐
    public function addHotTag($tag,$sort, $status){
        $setarray = array();
        $setarray[] = "tag = '{$tag}'";
        $setarray[] = "sort = '{$sort}'";
        $setarray[] = "createtime = now()";
        $setarray[] = "status = '{$status}'";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, 'hot_tag', $setarray);
        return $ret;
    }

    //获取列表
    public function getTagList($page, $max){
        $limit = ($page-1)*$max;
        $sql = "SELECT * from hot_tag where status = 1 ORDER BY sort desc,id desc limit {$limit}, $max";
        $data = $GLOBALS['DB']->myquery($sql);
        return $data;
    }

    //更新标签
    public function updateTag($tid, $tag, $sort, $status){
        $setarray[] =  "tag = {$tag}";
        $setarray[] =  "sort = {$sort}";
        $setarray[] =  "status = {$status}";
        $where = " AND id = {$tid}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, 'hot_tag', $setarray, $where);
        if($ret !== false){
            return true;
        }
        return false;
    }

    //删除标签
    public function delTag($tid){
        $where = " and id = {$tid}";
        $ret = $GLOBALS['DB']->Set_Delete(ECHO_AQL_SWITCH, 1, 'hot_tag', $where);
        if($ret){
            return $ret;
        }else{
            return false;
        }
    }
}