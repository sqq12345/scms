<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/5/14
 * Time: 10:37
 */
class alipay{

    public function doPay($postData){
        require_once (dirname ( __FILE__ ).DIRECTORY_SEPARATOR."alipay/wappay/service/AlipayTradeService.php");
        require_once (dirname ( __FILE__ ).DIRECTORY_SEPARATOR."alipay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php");
        require_once (dirname ( __FILE__ ).DIRECTORY_SEPARATOR."alipay/config.php");
        if (!empty($postData['orderNo'])&& trim($postData['orderNo'])!=""){
            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no = $postData['orderNo'];

            //订单名称，必填
            $subject = $postData['title'];

            //付款金额，必填
            $total_amount = $postData['fee'];

            //商品描述，可空
            $body = $postData['body'];

            //超时时间
            $timeout_express="1m";
            $payRequestBuilder = new AlipayTradeWapPayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setOutTradeNo($out_trade_no);
            $payRequestBuilder->setTotalAmount($total_amount);
 //           $payRequestBuilder->setTotalAmount(0.01);
            $payRequestBuilder->setTimeExpress($timeout_express);

            $payResponse = new AlipayTradeService($config);
            if(strstr($out_trade_no,'CO')){
                $config['return_url'] = $config['return_url'].'/0';
            }elseif (strstr($out_trade_no,'YY')){
                $config['return_url'] = $config['return_url'].'/1';
            }elseif (strstr($out_trade_no,'RR')){
                $config['return_url'] = $config['return_url'].'/4';
            }else{

            }
            $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);
//            var_dump($result);
            return $result;
        }else{
            return '';
        }
//        require_once (dirname ( __FILE__ ).DIRECTORY_SEPARATOR."alipay/aop/AopClient.php");
//        $aop = new AopClient ();
//        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
//        $aop->appId = '2018050302625459';
//        $aop->rsaPrivateKey = 'MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCNmgx89pap8Sv0s5t8ModwqPFoUA+gpplQhrtl1+wOL3cNHWzSSZNbJOGuSVRl8ZcC3HkiwrM1vBmf+LJE1S9Ip85pHAv+G8pfoQB0ZM5Tn3LUC2AhYNPnb+bom0Iwi2bGp8lcaHjnoNWOaFLbX3+RndqM4LUiaMordeqYflf1j75n+bmb+MGVgylZzTtatmptZzfgBE2j9ewaxrjCOfs7sUN1AIluFEQoTIWMun0Lb0lWDg0TIn+OssLvEvOg5Puvy0RPI5NGNV9UUjKGQRpX9CUMx71P35qTfwNhvPlBPSR64vbxrhrthYeLr6ew73aOPSS+re0enMrj75ebvv2nAgMBAAECggEAX5559lyRqtpnw0sRoNGCMipzMex2URaPCxigLQqcpYuZyepn1KzIa9DA8O8lpd15Cv6UckultpB5gVPwFZkb3+Uo9kNxObvMcb0H0JFN3pwab2PrGz8GeQ9QjxxgmuVXlqgwykzl8AvkidCauvaG72736Q/IYR7//k8XBriybugISR9DTYABBJmi1pkX2f4q/tnN427I+K6wAvz9FIEoPMSKmWxb/KvusdWQJZr6/cVcdIDzM87vOt+9cNbsSJjTFNyKhNGgAyceaJK0jZ+uVKwrrY2lc3kEonLt+ySMqtrYUvhCj9w/2k9mhANGsrZqNA85cStwnLDWWZcrE6i6WQKBgQDiINP3Vwj89N4bbtQ1mKz2yfM+K1qnzWR/PjEoJSgzpl9RM1YDYv4d1e5lcp1dDvmP+gQp3LBcRxxcamHhWaMBCkXA5xZgXu7dfcB0V8RxzmWLFmhx6HboBxvzzi+dy66lY2lRmXoUuzGQjK3j14Ox6F8O0GCuv10G9kJhrl0fJQKBgQCgTrdohY4jLhZfjiz4zjyVFdQT0nMkdisx3CrxE11D3fiMUE6sJYvHUN4ypA9L0WWW30dRB66QagGidhy8Fvj6g+0ql6/375fOqn1T/xv301lYiKKrHb6Tax81yhY8I4QCAV5ePp/i9vzNeLGzhmqQwEKsEzzJnSF4ilD7ZLEl2wKBgQCEX81WJNg5JKuFCasmuPq/+dbwVPbb9ovXRTQHmUDgg4uXAGNg1imGk77cGm1ulZ6YnzaivvNrAaHjo88q2Ytnx3iwBVd/EPPqK3xnXx27taSR+Isp63j4OXkuj0wmpp7VaM21nA/wZOkOApylHXuVwT8sb+W5RoMR2UVg427WFQKBgFxCETHZMx0yB/REacNjReBzKJOj2VpRm7hdQmVtxI4rcECocy6FiTVTaB1y5861mybCJ1QN/LvmFjy+hvkEq5PZWyPZGo+xVwe8fNZbimgNPW1DbYLXYneK/fJB9Jv1wKI60Wmh1viTNpi17mcoY6oczAImLCTIBpxN1h9oKr9jAoGBANXQ5ZO8u0es/mBsuIW2k7Yy7CdyZpc/iJPv25JmWkeqCBQPTkAyFVAY7QwfzdBRSgGGKi5DdGnZJxmj8olRDWqTVRZZZNr7M0iU8X1LiU6nMnrlZSsxEyF/pml1ea6hybNuddEbvTingt75iqnIaQQwWi/ZnEjZXiNH1HRnSgMR';
//        $aop->alipayrsaPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAioZDQAbbpTtXGkR9jK1dl9mpMY1nPxq0OJESDBmuhUxDo4bCpwyMRJnMSgI3CClbl2lA941yl3qj+pZe55UimLSGLdgJv7kz+O25vxKlbkwxVzeRWkIjFuLqSUyLmVHLMuWF9tu71ZG4eoP1P4UTf9ZWLnbSwbO/W1YBHcO4mnCJbn03XjAQByOViiySi4qqPKb49EILNjgBPwkKzda1LGE0A4m+Eoq3U8wLh0EQnkUZu1vEekDVKJXVFg87sxdMa9nzglHGmjmLCWf7geur0IxWOpJqfO9GJelorjUbcZ+7kLVO8hi7KS+Hba9InlR9XtJr5UTxVbClk2Jp5VViiQIDAQAB';
//        $aop->apiVersion = '1.0';
//        $aop->postCharset='utf-8';
//        $aop->format='json';
//        $aop->signType='RSA2';
//        require_once (dirname ( __FILE__ ).DIRECTORY_SEPARATOR."alipay/aop/request/AlipayTradeWapPayRequest.php");
//        $request = new AlipayTradeWapPayRequest ();
//        $request->setBizContent("{" .
//            "    \"body\":\"对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加传给body。\"," . "    \"subject\":\"大乐透\"," . "    \"out_trade_no\":\"7050111111666333455\"," . "    \"timeout_express\":\"90m\"," . "    \"total_amount\":0.01," . "    \"product_code\":\"QUICK_WAP_WAY\"" . "  }");
//        $result = $aop->pageExecute($request);
//        var_dump($result);
//        echo $result;
    }

    public function doRefund($data){
        require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'alipay/wappay/service/AlipayTradeService.php';
        require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'alipay/wappay/buildermodel/AlipayTradeRefundContentBuilder.php';
        require dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'alipay/config.php';
        if (!empty($data['orderNo']) || !empty($data['orderNo'])){

            //商户订单号和支付宝交易号不能同时为空。 trade_no、  out_trade_no如果同时存在优先取trade_no
            //商户订单号，和支付宝交易号二选一
            $out_trade_no = trim($data['orderNo']);

            //支付宝交易号，和商户订单号二选一
//            $trade_no = trim($_POST['WIDtrade_no']);

            //退款金额，不能大于订单总金额
            $refund_amount=trim($data['WIDrefund_amount']);

            //退款的原因说明
            $refund_reason=trim($data['WIDrefund_reason']);

            //标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传。
            $out_request_no=trim($data['WIDout_request_no']);

            $RequestBuilder = new AlipayTradeRefundContentBuilder();
//            $RequestBuilder->setTradeNo($trade_no);
            $RequestBuilder->setOutTradeNo($out_trade_no);
            $RequestBuilder->setRefundAmount($refund_amount);
            $RequestBuilder->setRefundReason($refund_reason);
            $RequestBuilder->setOutRequestNo($out_request_no);

            $Response = new AlipayTradeService($config);
            $result=$Response->Refund($RequestBuilder);
            return $result;
        }
    }
}