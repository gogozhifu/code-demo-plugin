<?php

/**
 * GOGO支付 支付宝WAP插件
 */

if (!defined('IN_ECTOUCH')) {
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'lang/' . $GLOBALS['_CFG']['lang'] . '/payment/gogowx.php';

if (file_exists($payment_lang)) {
    global $_LANG;

    include_once($payment_lang);
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE) {
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code'] = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc'] = 'gogowx_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod'] = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online'] = '1';

    /* 作者 */
    $modules[$i]['author'] = 'GOGO支付';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.gogozhifu.com';

    /* 版本号 */
    $modules[$i]['version'] = '1.3';

    /* 配置信息 */
    $modules[$i]['config'] = array(
        array('name' => 'gogowx_appid', 'type' => 'text', 'value' => ''),
        array('name' => 'gogowx_appsecret', 'type' => 'text', 'value' => ''),
    );

    return;
}

/**
 * 类
 */
class gogowx
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function __construct()
    {
        $this->gogowx();
    }

    function gogowx()
    {
    }

    /**
     * 生成支付代码
     * @param   array $order 订单信息
     * @param   array $payment 支付方式信息
     */
    function get_code($order, $payment)
    {

        $appId = $payment['gogowx_appid']; // APPID
        $appSecret = $payment['gogowx_appsecret']; // APPSECRET
        // GOGO支付创建订单API地址
        $apiUrl = 'https://www.gogozhifu.com/shop/api/createOrder';
        // 选填，支付完成后通知开发者服务器的url。(不传会获取GOGO支付商户后台设置的默认回调地址)
        //这里要修改成商户自己接收支付成功回调通知的地址，该地址不能有访问权限，POST请求方式
        $notifyUrl = return_url(basename(__FILE__, '.php'));
        // 选填，跳转页面url。(不传会获取GOGO支付商户后台设置的默认跳转地址)
        $returnUrl = return_url(basename(__FILE__, '.php'));
        // 选填, 商户自定义的参数，回调通知的时候会原样返回
        $param = $order['log_id'];

        $type = 1; //微信
        $payId = $order['order_sn'] . '-' . $type; //使用本商户订单号
        $price = $order['order_amount'];

        // 计算sign
        $sign = md5($appId . $payId . $param . $type . $price . $appSecret);

        $data = array(
            'payId' => $payId,
            'param' => $param,
            'type' => $type,
            'price' => $price,
            'sign' => $sign,
            'notifyUrl' => $notifyUrl,
            'returnUrl' => $returnUrl,
            'isHtml' => 1,
            'returnParam' => 1
        );

        $ret = $this->goPost($apiUrl, $data, $appId, $appSecret);

        if (stripos($ret, '<script>') === false) {
            $retArr = json_decode($ret, true);
            $btnMsg = isset($retArr['msg']) && !empty($retArr['msg']) ? $retArr['msg'] : '支付出错(error_code:001)';
            $button = "<a class='c-btn3'>" . $btnMsg . "</a>";
            return $button;
        }
        $payUrl = substr($ret, stripos($ret, 'http'));
        $payUrl = explode("'<", $payUrl)[0];

        $button = "<a class='c-btn3' href='" . $payUrl . "'>立即支付</a>";
        return $button;
    }

    /**
     * 响应操作
     */
    function respond()
    {
       /* if (!empty($_POST)) {
            foreach ($_POST as $key => $data) {
                $_GET[$key] = $data;
            }
        }*/

        $payment = get_payment($_GET['code']);

        $appId = $payment['gogowx_appid']; // APPID
        $appSecret = $payment['gogowx_appsecret']; // APPSECRET

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
        //处理订单支付
        order_paid($param, 2);
        //如果是回调post，返回success
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
            echo "success";
            exit();
        }
        return true;
    }

    // 发起POST请求，请求头里必须设置商户的App-Id和App-Secret
    function goPost($url, $data, $appId, $appSecret)
    {
        $headerArray = array(
            "App-Id: " . $appId,
            "App-Secret: " . $appSecret,
        );
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

}

?>