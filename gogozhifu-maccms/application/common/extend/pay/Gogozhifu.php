<?php
/**
 * GOGO支付 - 更好的个人支付解决方案
 * 免费注册使用、个人免签约、无手续费、实时回调
 * https://www.gogozhifu.com
 * WX: gump994
 */
namespace app\common\extend\pay;

class Gogozhifu
{

    public $name = 'GOGO支付 - www.gogozhifu.com';
    public $ver = '1.0';

    public function submit($user, $order, $params)
    {
        $appId = trim($GLOBALS['config']['pay']['gogozhifu']['appid']);
        $appSecret = trim($GLOBALS['config']['pay']['gogozhifu']['appsecret']);
        $url = "https://www.gogozhifu.com/shop/api/createOrder";

        $payId = $order['order_code'];
        $param = 'userId' . $user['user_id'];
        $type = $params['paytype'];
        $price = $order['order_price'];
        $sign = md5($appId . $payId . $param . $type . $price. $appSecret);
        $data = [
            'payId' => $payId,
            'param' => $param,
            'type' => $type,
            'price' => $price,
            'sign' => $sign,
            'isHtml' => 1,
            'notifyUrl' => $GLOBALS['http_type'] . $_SERVER['HTTP_HOST'] . '/index.php/payment/notify/pay_type/gogozhifu',//通知地址
            'returnUrl' =>$GLOBALS['http_type'] . $_SERVER['HTTP_HOST'] . '/index.php/payment/notify/pay_type/gogozhifu',//跳转地址
        ];
        return $this->ggPost($url, $data);
    }

    public function notify()
    {
        $data = input();
        //校验签名，确保安全
        $appId = trim($GLOBALS['config']['pay']['gogozhifu']['appid']); //APPID
        $appSecret = trim($GLOBALS['config']['pay']['gogozhifu']['appsecret']); //APPSECRET
        $payId = $data['payId'];//商户订单号
        $param = $data['param'];//创建订单的时候传入的参数
        $type = $data['type'];//支付方式 ：微信支付为1 支付宝支付为2
        $price = $data['price'];//订单金额
        $reallyPrice = $data['reallyPrice'];//实际支付金额
        $sign = $data['sign'];//校验签名，计算方式 = md5(appId + payId + param + type + price + reallyPrice + appSecret)

        //开始校验签名
        $_sign =  md5($appId . $payId . $param . $type . $price . $reallyPrice . $appSecret);
        if ($_sign != $sign) {
            echo "error_sign";//sign校验不通过
            exit();
        }
        echo "success";

        //处理后续业务逻辑
        model('Order')->notify($data['payId'], 'gogozhifu');
    }

    /**
     * ggf使用的post
     * @param $url
     * @param $data
     * @return mixed
     */
    public function ggPost($url, $data)
    {
        $headerArray = [
            "App-Id: " . trim($GLOBALS['config']['pay']['gogozhifu']['appid']),
            "App-Secret: " . trim($GLOBALS['config']['pay']['gogozhifu']['appsecret'])
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
        //将返回的json对象解码成数组对象并返回
        //$output = json_decode($output,true);
        return $output;
    }
}