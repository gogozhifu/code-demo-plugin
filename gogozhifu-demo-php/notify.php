<?php
/* GOGO支付接入代码DEMO - PHP版本 */

ini_set("error_reporting", "E_ALL & ~E_NOTICE");
$appId = "填入GOOG支付商户自己的AppId"; //APPID
$appSecret = "填入GOOG支付商户自己的AppSecret"; //APPSECRET

//获取回调的参数（回调请求是POST方式，参数获取同时支持GET、POST）
$payId = $_GET['payId'];//商户订单号
$param = $_GET['param'];//创建订单的时候传入的参数
$type = $_GET['type'];//支付方式 ：微信支付为1 支付宝支付为2
$price = $_GET['price'];//订单金额
$reallyPrice = $_GET['reallyPrice'];//实际支付金额
$sign = $_GET['sign'];//校验签名，计算方式 = md5(appId + payId + param + type + price + reallyPrice + appSecret)

//开始校验签名
$_sign = md5($appId . $payId . $param . $type . $price . $reallyPrice . $appSecret);
if ($_sign != $sign) {
    echo "error_sign";//sign校验不通过
    exit();
}
echo "success";
//继续处理商户自己的业务流程
//echo "商户订单号：" . $payId . "<br>自定义参数：" . $param . "<br>支付方式：" . $type . "<br>订单金额：" . $price . "<br>实际支付金额：" . $reallyPrice;
?>