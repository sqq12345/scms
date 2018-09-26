<?php
/**
 * Created by PhpStorm.
 * 微信支付
 * User: huiyong.yu
 * Date: 2018/5/22
 * Time: 16:11
 */
class wxpay{

    public function doH5Pay($data){
        require_once (dirname ( __FILE__ ).DIRECTORY_SEPARATOR."wxpay/lib/WxPay.Api.php");
        require_once (dirname ( __FILE__ ).DIRECTORY_SEPARATOR."wxpay/lib/WxPay.JsApiPay.php");
        if (!empty($data['orderNo'])&& trim($data['orderNo'])!=""){
            $input = new WxPayUnifiedOrder();
            $input->SetBody($data['body']);
            $input->SetOut_trade_no('H5'.$data['orderNo']);
            $input->SetTotal_fee($data['fee']);
 //           $input->SetTotal_fee(1);
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            $input->SetNotify_url("https://api.shichamaishou.com/application/libraries/wxpay/notify.php");
            $input->SetTrade_type("MWEB");
            $order = WxPayApi::unifiedOrder($input);
            $url = '';
            if($order['result_code'] == 'SUCCESS' && $order['return_code'] == 'SUCCESS'){
                if(strstr($data['orderNo'],'CO')){
                    $url = $order['mweb_url'].'&redirect_url='.urlencode('https://www.shichamaishou.com/doSucc/0');
                }elseif (strstr($data['orderNo'],'YY')){
                    $url = $order['mweb_url'].'&redirect_url='.urlencode('https://www.shichamaishou.com/doSucc/1');
                }elseif (strstr($data['orderNo'],'RR')){
                    $url = $order['mweb_url'].'&redirect_url='.urlencode('https://www.shichamaishou.com/doSucc/4');
                }else{
                    $url = $order['mweb_url'].'&redirect_url='.urlencode('https://www.shichamaishou.com/doSucc');
                }
            }
            return $url;
        }else{
            return '';
        }
    }

    public function doJsApiPay($data){
        require_once (dirname ( __FILE__ ).DIRECTORY_SEPARATOR."wxpay/lib/WxPay.Api.php");
        require_once (dirname ( __FILE__ ).DIRECTORY_SEPARATOR."wxpay/lib/WxPay.JsApiPay.php");
        //①、获取用户openid
        $tools = new JsApiPay();
        $openId = $data['openId'];
        if(empty($openId)){
            return '';
        }
        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody($data['body']);
        $input->SetOut_trade_no('WS'.$data['orderNo']);
        $input->SetTotal_fee($data['fee']);
 //       $input->SetTotal_fee(1);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetNotify_url("https://api.shichamaishou.com/application/libraries/wxpay/notify.php");
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($input);
        $jsApiParameters = '';
      get_cpalog($data['orderNo'] . '-', json_encode($order));
        if($order['result_code'] == 'SUCCESS' && $order['return_code'] == 'SUCCESS'){
            $jsApiParameters = $tools->GetJsApiParameters($order);
        }
        return $jsApiParameters;
    }

    public function doQrCodePay($data){
        require_once (dirname ( __FILE__ ).DIRECTORY_SEPARATOR."wxpay/lib/WxPay.Data.php");
        require_once (dirname ( __FILE__ ).DIRECTORY_SEPARATOR."wxpay/lib/WxPay.NativePay.php");
        if (!empty($data['orderNo'])&& trim($data['orderNo'])!=""){
            $notify = new NativePay();
            $input = new WxPayUnifiedOrder();
            $input->SetBody($data['body']);
            $input->SetOut_trade_no('QR'.$data['orderNo']);
           $input->SetTotal_fee($data['fee']);
  //          $input->SetTotal_fee(1);
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            $input->SetNotify_url("https://api.shichamaishou.com/application/libraries/wxpay/notify.php");
            $input->SetTrade_type("NATIVE");
            $input->SetProduct_id("123456789");
            $result = $notify->GetPayUrl($input);
            $url = '';
            if($result['result_code'] == 'SUCCESS' && $result['return_code'] == 'SUCCESS'){
                $url = $result['code_url'];
            }
            return $url;
        }else{
            return '';
        }
    }

    public function doRefund($data){
        require_once (dirname ( __FILE__ ).DIRECTORY_SEPARATOR."wxpay/lib/WxPay.Api.php");
        require_once (dirname ( __FILE__ ).DIRECTORY_SEPARATOR."wxpay/lib/WxPay.Data.php");
        require_once (dirname ( __FILE__ ).DIRECTORY_SEPARATOR."wxpay/lib/WxPay.Config.php");
        $out_trade_no = $data["out_trade_no"];
        $total_fee = $data["total_fee"];
        $refund_fee = $data["refund_fee"];
        $out_refund_no = $data["out_refund_no"];
        $input = new WxPayRefund();

        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee($total_fee);
        $input->SetRefund_fee($refund_fee);
        $input->SetOut_refund_no($out_refund_no);
        $input->SetOp_user_id(WxPayConfig::MCHID);
        $result = WxPayApi::refund($input);
	get_cpalog('申请退款'.$data['out_trade_no'] . '-', json_encode($result));
        return $result;
    }

    public function doXcxPay($data){
        require_once (dirname ( __FILE__ ).DIRECTORY_SEPARATOR."wxpay/lib/WxPay.Api.php");
        require_once (dirname ( __FILE__ ).DIRECTORY_SEPARATOR."wxpay/lib/WxPay.JsApiPay.php");
        //①、获取用户openid
        $tools = new JsApiPay();
        $openId = $data['openId'];
        if(empty($openId)){
            return '';
        }
        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody($data['body']);
        $input->SetOut_trade_no('WS'.$data['orderNo']);
        $input->SetTotal_fee($data['fee']);
 //       $input->SetTotal_fee(1);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetNotify_url("https://api.shichamaishou.com/application/libraries/wxpay/notify.php");
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrderXcx($input);
        $jsApiParameters = '';
        get_cpalog('小程序：'.$data['orderNo'] . '-', json_encode($order));
	get_cpalog('小程序：'.$data['orderNo'] . '-', json_encode($data));
        if($order['result_code'] == 'SUCCESS' && $order['return_code'] == 'SUCCESS'){
            $jsApiParameters = $tools->GetJsApiParameters($order);
        }
        return $jsApiParameters;
    }
}