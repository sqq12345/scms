<?php
/**
 * 接口入口文件
 */

require_once('application/init.php');

//header('Access-Control-Allow-Origin:*');
//
//header('Access-Control-Allow-Methods', 'GET,POST,OPTIONS');
//header('Access-Control-Allow-Methods:*');
//header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
//header("Access-Control-Allow-Credentials", "true");
//header('Access-Control-Max-Age:60');

$data = url_request();

$act_array = explode('_',$data['act']);
$act = isset($act_array[0]) ? $act_array[0] : '';
$methods = isset($act_array[1]) ? $act_array[1] : '';

switch ($act) {
    case 'customer' :
    require_once('application/controllers/Customer.php');
    $customer =  new Customer();
    if(!method_exists($customer,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $customer->$methods($data);
    break;
    case 'address' :
    require_once('application/controllers/Address.php');
    $address =  new Address();
    if(!method_exists($address,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $address->$methods($data);
    break;
    case 'bcTemplate' :
    require_once('application/controllers/BcTemplate.php');
    $bcTemplate =  new BcTemplate();
    if(!method_exists($bcTemplate,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $bcTemplate->$methods($data);
    break;
    case 'bcBanner' :
    require_once('application/controllers/BcBanner.php');

    $bcBanner =  new BcBanner();
    if(!method_exists($bcBanner,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }

    $bcBanner->$methods($data);
    break;
    case 'banner' :
    require_once('application/controllers/Banner.php');
    $banner =  new Banner();
    if(!method_exists($banner,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $banner->$methods($data);
    break;
    case 'bcBrand' :
    require_once('application/controllers/BcBrand.php');
    $bcBrand =  new BcBrand();
    if(!method_exists($bcBrand,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $bcBrand->$methods($data);
    break;
    case 'brand' :
    require_once('application/controllers/Brand.php');
    $brand =  new Brand();
    if(!method_exists($brand,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $brand->$methods($data);
    break;
    case 'product' :
    require_once('application/controllers/Product.php');
    $product =  new Product();
    if(!method_exists($product,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $product->$methods($data);
    break;
    case 'bcProduct' :
    require_once('application/controllers/BcProduct.php');
    $bcProduct =  new BcProduct();
    if(!method_exists($bcProduct,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $bcProduct->$methods($data);
    break;
    case 'bcPurchase' :
    require_once('application/controllers/BcPurchase.php');
    $bcPurchase =  new BcPurchase();
    if(!method_exists($bcPurchase,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $bcPurchase->$methods($data);
    break;
    case 'purchase' :
    require_once('application/controllers/Purchase.php');
    $purchase =  new Purchase();
    if(!method_exists($purchase,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $purchase->$methods($data);
    break;
    case 'token' :
    require_once('application/controllers/Token.php');
    $token =  new Token();
    if(!method_exists($token,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $token->$methods($data);
    break;
    case 'bcCategory' :
    require_once('application/controllers/BcCategory.php');
    $bcCategory =  new BcCategory();
    if(!method_exists($bcCategory,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $bcCategory->$methods($data);
    break;
    case 'captcha' :
    require_once('application/controllers/Captcha.php');
    $captcha =  new Captcha();
    if(!method_exists($captcha,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $captcha->$methods($data);
    break;
    case 'upload' :
    require_once('application/controllers/Upload.php');
    $upload =  new Upload();
    if(!method_exists($upload,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $upload->$methods($data);
    break;
    case 'bespeakOrder' :
    require_once('application/controllers/BespeakOrder.php');
    $bespeakOrder =  new BespeakOrder();
    if(!method_exists($bespeakOrder,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $bespeakOrder->$methods($data);
    break;
    case 'bcBespeakOrder' :
    require_once('application/controllers/BcBespeakOrder.php');
    $bcBespeakOrder =  new BcBespeakOrder();
    if(!method_exists($bcBespeakOrder,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $bcBespeakOrder->$methods($data);
    break;
    case 'bcSpecialProduct' :
    require_once('application/controllers/BcSpecialProduct.php');
    $bcSpecialProduct =  new BcSpecialProduct();
    if(!method_exists($bcSpecialProduct,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $bcSpecialProduct->$methods($data);
    break;
    case 'bcBespeakCategory' :
    require_once('application/controllers/BcBespeakCategory.php');
    $bcBespeakCategory =  new BcBespeakCategory();
    if(!method_exists($bcBespeakCategory,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $bcBespeakCategory->$methods($data);
    break;
    case 'order' :
    require_once('application/controllers/Order.php');
    $order =  new Order();
    if(!method_exists($order,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $order->$methods($data);
    break;
    case 'bcCustomer' :
    require_once('application/controllers/BcCustomer.php');
    $bcCustomer =  new BcCustomer();
    if(!method_exists($bcCustomer,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $bcCustomer->$methods($data);
    break;
    case 'bcAddress' :
    require_once('application/controllers/BcAddress.php');
    $bcAddress =  new BcAddress();
    if(!method_exists($bcAddress,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $bcAddress->$methods($data);
    break;
    case 'reBcProduct' :
    require_once('application/controllers/ReBcProduct.php');
    $reBcProduct =  new ReBcProduct();
    if(!method_exists($reBcProduct,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $reBcProduct->$methods($data);
    break;
    case 'reBcProductSku' :
    require_once('application/controllers/ReBcProductSku.php');
    $reBcProductSku =  new ReBcProductSku();
    if(!method_exists($reBcProductSku,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $reBcProductSku->$methods($data);
    break;
    case 'reBcSpecialProduct' :
    require_once('application/controllers/ReBcSpecialProduct.php');
    $reBcSpecialProduct =  new ReBcSpecialProduct();
    if(!method_exists($reBcSpecialProduct,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $reBcSpecialProduct->$methods($data);
    break;
    case 'cart' :
    require_once('application/controllers/Cart.php');
    $cart =  new Cart();
    if(!method_exists($cart,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $cart->$methods($data);
    break;
    case 'reBcBespeakProduct' :
    require_once('application/controllers/ReBcBespeakProduct.php');
    $reBcBespeakProduct =  new ReBcBespeakProduct();
    if(!method_exists($reBcBespeakProduct,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $reBcBespeakProduct->$methods($data);
    break;
    case 'reBcBespeakProductSku' :
    require_once('application/controllers/ReBcBespeakProductSku.php');
    $reBcBespeakProductSku =  new ReBcBespeakProductSku();
    if(!method_exists($reBcBespeakProductSku,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $reBcBespeakProductSku->$methods($data);
    break;
    case 'reProduct' :
    require_once('application/controllers/ReProduct.php');
    $reProduct =  new ReProduct();
    if(!method_exists($reProduct,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $reProduct->$methods($data);
    break;
    case 'reConfig' :
    require_once('application/controllers/ReConfig.php');
    $reConfig =  new ReConfig();
    if(!method_exists($reConfig,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $reConfig->$methods($data);
    break;
    case 'bcOrder' :
    require_once('application/controllers/BcOrder.php');
    $bcOrder =  new BcOrder();
    if(!method_exists($bcOrder,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $bcOrder->$methods($data);
    break;
    case 'reBespeakProduct' :
    require_once('application/controllers/ReBespeakProduct.php');
    $reBespeakProduct =  new ReBespeakProduct();
    if(!method_exists($reBespeakProduct,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $reBespeakProduct->$methods($data);
    break;
    case 'bcRefundOrder' :
    require_once('application/controllers/BcRefundOrder.php');
    $bcRefundOrder =  new BcRefundOrder();
    if(!method_exists($bcRefundOrder,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $bcRefundOrder->$methods($data);
    break;
    case 'reSpecialProduct' :
    require_once('application/controllers/ReSpecialProduct.php');
    $reSpecialProduct =  new ReSpecialProduct();
    if(!method_exists($reSpecialProduct,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $reSpecialProduct->$methods($data);
    break;
    case 'refundOrder' :
    require_once('application/controllers/RefundOrder.php');
    $refundOrder =  new RefundOrder();
    if(!method_exists($refundOrder,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $refundOrder->$methods($data);
    break;
    case 'buyer' :
    require_once('application/controllers/Buyer.php');
    $buyer =  new Buyer();
    if(!method_exists($buyer,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $buyer->$methods($data);
    break;
    case 'bespeakConfig' :
    require_once('application/controllers/BespeakConfig.php');
    $bespeakConfig =  new BespeakConfig();
    if(!method_exists($bespeakConfig,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $bespeakConfig->$methods($data);
    break;
    case 'category' :
    require_once('application/controllers/Category.php');
    $category =  new Category();
    if(!method_exists($category,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $category->$methods($data);
    break;
    case 'statistics' :
    require_once('application/controllers/Statistics.php');
    $statistics =  new Statistics();
    if(!method_exists($statistics,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $statistics->$methods($data);
    break;
    case 'payment' :
    require_once('application/controllers/Payment.php');
    $payment =  new Payment();
    if(!method_exists($payment,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $payment->$methods($data);
    break;
    case 'sendMsg' :
    require_once('application/controllers/SendMsg.php');
    $sendMsg =  new SendMsg();
    if(!method_exists($sendMsg,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $sendMsg->$methods($data);
    break;
    case 'company' :
    require_once('application/controllers/Company.php');
    $company =  new Company();
    if(!method_exists($company,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $company->$methods($data);
    break;
    case 'validate' :
    require_once('application/controllers/Validate.php');
    $validate =  new Validate();
    if(!method_exists($validate,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $validate->$methods($data);
    break;
    case 'uploadExcel' :
    require_once('application/controllers/UploadExcel.php');
    $uploadExcel =  new UploadExcel();
    if(!method_exists($uploadExcel,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $uploadExcel->$methods($data);
    break;
    case 'bespeakCategory' :
    require_once('application/controllers/BespeakCategory.php');
    $bespeakCategory =  new BespeakCategory();
    if(!method_exists($bespeakCategory,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $bespeakCategory->$methods($data);
    break;
    case 'wxChat' :
    require_once('application/controllers/WxChat.php');
    $wxChat =  new WxChat();
    if(!method_exists($wxChat,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }

    $wxChat->$methods($data);
    break;
    case 'bcSplitOrder' :
    require_once('application/controllers/BcSplitOrder.php');
    $bcSplitOrder =  new BcSplitOrder();
    if(!method_exists($bcSplitOrder,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $bcSplitOrder->$methods($data);
    break;
    case 'search' :
    require_once('application/controllers/Search.php');
    $search =  new Search();
    if(!method_exists($search,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $search->$methods($data);
    break;
    case 'newgoods' :
    require_once('application/controllers/NewGoods.php');
    $newgoods =  new NewGoods();
    if(!method_exists($newgoods,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $newgoods->$methods($data);
    break;
    case 'ExchangeRate' :
    require_once('application/controllers/ExchangeRate.php');
    $ExchangeRate =  new ExchangeRate();
    if(!method_exists($ExchangeRate,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $ExchangeRate->$methods($data);
    break;
    case 'test' :
    require_once('application/controllers/test.php');
    $test =  new test();
    if(!method_exists($test,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $test->$methods($data);
    break;
    case 'activity' :
    require_once('application/controllers/Activity.php');
    $activity =  new activity();
    if(!method_exists($activity,$methods)){
        Set_Return_Value(KEY_STR_ERROR, '');
        break;
    }
    $activity->$methods($data);
    break;
    default:
    Set_Return_Value(KEY_STR_ERROR, '');
}
get_cpalog(json_encode($data),json_encode($GLOBALS['ReturnValues']));
json_var($GLOBALS['ReturnValues']);
