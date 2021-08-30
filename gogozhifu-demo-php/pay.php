<?php
/* GOGO支付接入代码DEMO - PHP版本 */

pay(1, 0.18, 'go-test-' . time());

// $type支付类型（1:微信，2支付宝）；$price产品价格，支持到小数点后两位；$payId不重复的交易单号
function pay($type, $price, $payId)
{
    // GOGO支付创建订单API地址
    $apiUrl = 'https://www.gogozhifu.com/shop/api/createOrder';

    // 选填，支付完成后通知开发者服务器的url。(不传会获取GOGO支付商户后台设置的默认回调地址)
    //这里要修改成商户自己接收支付成功回调通知的地址，该地址不能有访问权限，POST请求方式
    $notifyUrl = 'http://localhost/notify.php';

    // 选填，跳转页面url。(不传会获取GOGO支付商户后台设置的默认跳转地址)
    $returnUrl = 'http://localhost/return.php';

    // 选填, 商户自定义的参数，回调通知的时候会原样返回
    $param = 'GOTEST';

    // 计算sign
    $sign = md5(getAppId() . $payId . $param . $type . $price . getAppSecret());

    $data = array(
        'payId' => $payId,
        'param' => $param,
        'type' => $type,
        'price' => $price,
        'sign' => $sign,
        'notifyUrl' => $notifyUrl,
        'returnUrl' => $returnUrl,
        'isHtml' => 1
    );
    $ret = goPost($apiUrl, $data);
    echo $ret;
}

// 必需，填入商户自己的AppId
function getAppId()
{
    return "填入GOOG支付商户自己的AppId";
}

// 必需，填入商户自己的AppSecret
function getAppSecret()
{
    return "填入GOOG支付商户自己的AppSecret";
}

// 发起POST请求，请求头里必须设置商户的App-Id和App-Secret
function goPost($url, $data)
{
    $headerArray = [
        "App-Id: " . getAppId(),
        "App-Secret: " . getAppSecret(),
    ];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

?> 
