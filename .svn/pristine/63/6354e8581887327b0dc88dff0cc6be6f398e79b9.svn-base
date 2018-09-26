<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/6/11
 * Time: 17:27
 */
class UploadExcel{

    public function process($data){
    	$dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        //判断是否选择了要上传的表格
        if (empty($_FILES['excelFile']) && sizeof($_FILES)) {
            set_return_value(EXCEL_NULL_ERROR, '');
            return false;
        }
        //限制上传表格类型
        $file_type = $_FILES['excelFile']['type'];
        if ($file_type!='application/vnd.ms-excel') {
            set_return_value(EXCEL_TYPE_ERROR, '');
            return false;
        }

        //判断表格是否上传成功
        if (is_uploaded_file($_FILES['excelFile']['tmp_name'])) {

            require_once '/www/wwwroot/www.shichamaishou.com/trunk/application/libraries/PHPExcel.php';
            require_once '/www/wwwroot/www.shichamaishou.com/trunk/application/libraries/PHPExcel/IOFactory.php';
            require_once '/www/wwwroot/www.shichamaishou.com/trunk/application/libraries/PHPExcel/Reader/Excel5.php';
            //以上三步加载phpExcel的类

            $objReader = PHPExcel_IOFactory::createReader('Excel5');
            //接收存在缓存中的excel表格
            $filename = $_FILES['excelFile']['tmp_name'];
            $objPHPExcel = $objReader->load($filename); //$filename可以是上传的表格，或者是指定的表格
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            // $highestColumn = $sheet->getHighestColumn(); // 取得总列数

            $pdo = $this->mydqlpdo();
            //循环读取excel表格,读取一条,插入一条
            //j表示从哪一行开始读取  从第二行开始读取，因为第一行是标题不保存
            //$a表示列号
            for($j=2;$j<=$highestRow;$j++) {
                $brandNmae = $objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue();
                $brandNmae = preg_replace('/^( |\s)*|( |\s)*$/', '', $brandNmae);
                $productName = $objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue();
                $productName = preg_replace('/^( |\s)*|( |\s)*$/', '', $productName);
                $distributionFee = $objPHPExcel->getActiveSheet()->getCell("C".$j)->getValue();
                $skuModel = $objPHPExcel->getActiveSheet()->getCell("D".$j)->getValue();
                $skuModel = preg_replace('/^( |\s)*|( |\s)*$/', '', $skuModel);
                $skuAttr = $objPHPExcel->getActiveSheet()->getCell("E".$j)->getValue();
                $skuAttr = preg_replace('/^( |\s)*|( |\s)*$/', '', $skuAttr);
                $skuRef = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
                $skuRef = preg_replace('/^( |\s)*|( |\s)*$/', '', $skuRef);
                $skuDutyFreePrice = $objPHPExcel->getActiveSheet()->getCell("G".$j)->getValue();
                $skuPrice = $objPHPExcel->getActiveSheet()->getCell("H".$j)->getValue();
                $skuCostPrice = $objPHPExcel->getActiveSheet()->getCell("I".$j)->getValue();
                $skuMinPurchaseNum = $objPHPExcel->getActiveSheet()->getCell("J".$j)->getValue();
                $skuLimitTime = $objPHPExcel->getActiveSheet()->getCell("K".$j)->getValue();
                $skuLimitNum = $objPHPExcel->getActiveSheet()->getCell("L".$j)->getValue();
                $skuStock = $objPHPExcel->getActiveSheet()->getCell("M".$j)->getValue();
                $productHotLevel = $objPHPExcel->getActiveSheet()->getCell("N".$j)->getValue();

                if(empty($brandNmae) || empty($productName)){
                    continue;
                }
                //查询品牌的信息
                $sql = "SELECT * FROM brand WHERE name = '{$brandNmae}'";
                $res = $pdo->query($sql);
                $row = $res->fetch();
                $brandId = 0;
                if(!empty($row)){
                    $brandId = $row['id'];
                }else{
                    $sql = "INSERT INTO brand (name,img ,type,priority,status) values ('{$brandNmae}', '' , 2, 1,1 )";
                    $pdo->exec($sql);
                    $brandId = $pdo->lastInsertId();
                }

                //查询分类的信息
                $sql = "SELECT * FROM category WHERE name = '{$brandNmae}'";
                $res = $pdo->query($sql);
                $row = $res->fetch();
                $categoryId = 0;
                if(!empty($row)){
                    $categoryId = ','.$row['id'].',';
                }else{
                    $sql = "INSERT INTO category (parent_id,parent_name ,name,summary,img,is_show,priority,status) values (0, '' , '{$brandNmae}','','',1, 1,1 )";
                    $pdo->exec($sql);
                    $id = $pdo->lastInsertId();
                    $categoryId = ','.$id.',';
                }
                //验证商品存不存在，不存在就插入新数据
                $sql = "SELECT * FROM re_product WHERE name = '{$productName}' AND brand_id = {$brandId}";
                $res = $pdo->query($sql);
                $row = $res->fetch();
                $pid = 0;
                if(!empty($row)){
                    $pid = $row['id'];
                }else{
                    $img = '["http://upload.hljr.com.cn/p_img_n@3x.png"]';
                    $sql = "insert into re_product (name,info,category_ids,brand_id,shortage_display,is_reduce_stock,distribution_fee,hot_level,good_icon,good_img,create_by,create_time,update_by,update_time,status)
     values ('{$productName}', '', '{$categoryId}', {$brandId}, '无货', '1', {$distributionFee}, {$productHotLevel}, 'http://upload.hljr.com.cn/p_img_n@3x.png', '{$img}', 2, now(), 2, now(), 1)";
                    $pdo->exec($sql);
                    $pid = $pdo->lastInsertId();
                }

                //sku数据处理
                if($skuModel == ''){
                    $skuModel = "型号";
                }
                if($skuAttr == ''){
                    $skuAttr = "默认型号";
                }
                $sql = "SELECT * FROM re_product_sku WHERE pid = {$pid} AND model = '{$skuModel}' AND attr = '{$skuAttr}'";
                $res = $pdo->query($sql);
                $row = $res->fetch();
                if(empty($row)){
                    $sql = "insert into re_product_sku ( pid,
															model,
															attr,
															img,
															ref,
															stock,
															duty_free_price,
															cost_price,
															price,
															min_purchase_num,
															limit_time,
															limit_num,
															create_by,
															create_time,
															update_by,
															update_time,
															status)
    values ( {$pid}, '{$skuModel}', '{$skuAttr}', '', '{$skuRef}', {$skuStock}, {$skuDutyFreePrice}, {$skuCostPrice}, {$skuPrice}, {$skuMinPurchaseNum}, {$skuLimitTime},{$skuLimitNum},2, now(), 1, now(), 1)";
                    $pdo->exec($sql);
                }else{
                    $dataval[] = $j;
                }
            }
            $pdo = null;
            set_return_value(RESULT_SUCCESS, $dataval);
            return false;
        }else{
            set_return_value(DEFEATED_ERROR, '');
            return false;
        }
    }

    function mydqlpdo(){
        try{
            $pdo = new PDO(DB_DSN, DB_USER, DB_PASSWD);
            $pdo->query('set names utf8');
        } catch(Exception $e) {
            echo "db error!";
        }
        return $pdo;
    }

    public function updateStock($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        //判断是否选择了要上传的表格
        if (empty($_FILES['excelFile']) && sizeof($_FILES)) {
            set_return_value(EXCEL_NULL_ERROR, '');
            return false;
        }
        //限制上传表格类型
        $file_type = $_FILES['excelFile']['type'];
        if ($file_type!='application/vnd.ms-excel') {
            set_return_value(EXCEL_TYPE_ERROR, '');
            return false;
        }

        //判断表格是否上传成功
        if (is_uploaded_file($_FILES['excelFile']['tmp_name'])) {

            require_once '/www/wwwroot/www.shichamaishou.com/trunk/application/libraries/PHPExcel.php';
            require_once '/www/wwwroot/www.shichamaishou.com/trunk/application/libraries/PHPExcel/IOFactory.php';
            require_once '/www/wwwroot/www.shichamaishou.com/trunk/application/libraries/PHPExcel/Reader/Excel5.php';
            //以上三步加载phpExcel的类

            $objReader = PHPExcel_IOFactory::createReader('Excel5');
            //接收存在缓存中的excel表格
            $filename = $_FILES['excelFile']['tmp_name'];
            $objPHPExcel = $objReader->load($filename); //$filename可以是上传的表格，或者是指定的表格
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            // $highestColumn = $sheet->getHighestColumn(); // 取得总列数

            $pdo = $this->mydqlpdo();
            //循环读取excel表格,读取一条,插入一条
            //j表示从哪一行开始读取  从第二行开始读取，因为第一行是标题不保存
            //$a表示列号
            for($j=2;$j<=$highestRow;$j++) {
                $ref = $objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue();
                $ref = preg_replace('/^( |\s)*|( |\s)*$/', '', $ref);
                $stock = $objPHPExcel->getActiveSheet()->getCell("E".$j)->getValue();
                if(empty($ref)){
                    continue;
                }

                $sql = "SELECT * FROM re_product_sku WHERE ref = '{$ref}'";
                $res = $pdo->query($sql);
                $row = $res->fetch();
                if(!empty($row)){
                    $sql = "UPDATE re_product_sku SET stock = {$stock} WHERE ref = '{$ref}'";
                    $pdo->exec($sql);
                }else{
                    $dataval[] = $j;
                }
            }
            $pdo = null;
            set_return_value(RESULT_SUCCESS, $dataval);
            return false;
        }else{
            set_return_value(DEFEATED_ERROR, '');
            return false;
        }
    }
}