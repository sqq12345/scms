<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/9
 * Time: 16:51
 */
class test{

    //抓取各银行各货币汇率
    public function getExchangeRate($data){
        set_time_limit(0);
        $url = "http://data.bank.hexun.com/other/cms/foreignexchangejson.ashx?callback=ShowDatalist";
        $output = CURL($url);
        $res = mb_convert_encoding($output, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
        $res = preg_replace('# #','',$res);
        preg_match_all(',{(.*?)},',$res,$match);

        foreach ($match[0] as $key => $value) {
            preg_match("|{bank:'(.*)',currency:'|",$value,$a);
            preg_match("|currency:'(.*)',code|",$value,$b);
            preg_match("|code:'(.*)',currencyUnit|",$value,$c);
            preg_match("|currencyUnit:'(.*)',cenPrice|",$value,$d);
            preg_match("|cenPrice:'(.*)',buyPrice1|",$value,$e);
            preg_match("|buyPrice1:'(.*)',sellPrice1|",$value,$f);
            preg_match("|sellPrice1:'(.*)',buyPrice2|",$value,$g);
            preg_match("|buyPrice2:'(.*)',sellPrice2|",$value,$h);
            preg_match("|sellPrice2:'(.*)',releasedate|",$value,$i);
            preg_match("|releasedate:'(.*)'}|",$value,$j);
            $array[$a[1]][$b[1]]['bank'] = $a[1];
            $array[$a[1]][$b[1]]['currency'] = $b[1];
            $array[$a[1]][$b[1]]['code'] = $c[1];
            $array[$a[1]][$b[1]]['currencyUnit'] = $d[1];
            $array[$a[1]][$b[1]]['cenPrice'] = $e[1]/100;
            $array[$a[1]][$b[1]]['buyPrice1'] = $f[1]/100;
            $array[$a[1]][$b[1]]['sellPrice1'] = $g[1]/100;
            $array[$a[1]][$b[1]]['buyPrice2'] = $h[1]/100;
            $array[$a[1]][$b[1]]['sellPrice2'] = $i[1]/100;
            $array[$a[1]][$b[1]]['releasedate'] = $j[1];

        }
        $currency = $data['currency'] ? $data['currency'] : "美元";
        if($currency == "all"){
            $changerate = $array;
        }else{
            foreach ($array as $key => $value) {
                if(isset($value[$currency])){
                    $changerate[$key] = $value[$currency];
                }
            }
        }
        $dataval['changerate'] = $changerate;
        set_return_value(RESULT_SUCCESS, $dataval);
    }

}