<?php

/**
 * 本插件由GOGO支付开发
 *
 * GOGO支付 - 更好的个人支付解决方案
 * 免费注册使用、个人免签约、无手续费、免挂机、实时回调
 * https://www.gogozhifu.com
 * WX: gump994
 * QQ: 653107385
 */


if (!defined('IN_ECS')) {
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/payment/gogozhifuzfb.php';

if (file_exists($payment_lang)) {
    global $_LANG;

    include_once($payment_lang);
}


/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE) {
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code'] = "gogozhifuzfb";

    /* 描述对应的语言项 */
    $modules[$i]['desc'] = 'gogozhifuzfb_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod'] = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online'] = '1';

    /* 作者 */
    $modules[$i]['author'] = 'GOGO支付';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.gogozhifu.com';

    /* 版本号 */
    $modules[$i]['version'] = '1.1.0';

    /* 配置信息 */
    $modules[$i]['config'] = array(
        array('name' => 'gogozhifuzfb_appid', 'type' => 'text', 'value' => ''),
        array('name' => 'gogozhifuzfb_appsecret', 'type' => 'text', 'value' => ''),
    );
    return;
}

/**
 * 类
 */
class gogozhifuzfb
{

    var $parameters; // cft 参数
    var $payment; // 配置信息

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
        $this->gogozhifuzfb();
    }

    function gogozhifuzfb()
    {
    }

    /**
     * 生成支付代码
     * @param   array $order 订单信息
     * @param   array $payment 支付方式信息
     */
    function get_code($order, $payment)
    {
        if (!defined('EC_CHARSET')) {
            $charset = 'utf-8';
        } else {
            $charset = EC_CHARSET;
        }
        //为respond做准备
        $this->payment = $payment;
        $charset = strtoupper($charset);
        $root = $GLOBALS['ecs']->url();

        $appId = $this->payment['gogozhifuzfb_appid']; // APPID
        $appSecret = $this->payment['gogozhifuzfb_appsecret']; // APPSECRET

        // GOGO支付创建订单API地址
        $apiUrl = 'https://www.gogozhifu.com/shop/api/createOrder';
        // 选填, 商户自定义的参数，回调通知的时候会原样返回
        $param = $order['log_id'];

        $type = 2; //支付宝
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
            'notifyUrl' => return_url(basename(__FILE__, '.php')),
            'returnUrl' => return_url(basename(__FILE__, '.php')),
            'isHtml' => 1,
            'returnParam' => 1
        );
        $ret = $this->goPost($apiUrl, $data, $appId, $appSecret);
        $javascript = '<style>#paymentDiv{width:100%;}#qrcode{display:block;}#qrcode img{height:260px;width:260px;border:1px solid #ddd}#qrcode p{padding:15px 0;background:#54c340;color:#fff;margin:0 auto;width:262px;}</style> ';

        if (stripos($ret, '<script>') === false) {
            $retArr = json_decode($ret, true);
            $btnMsg = isset($retArr['msg']) && !empty($retArr['msg']) ? $retArr['msg'] : '生成支付按钮出错(error_code:001)';
            $button = '<div id="paymentDiv"><div style="text-align:center" id="qrcode"><p>' . $btnMsg . '</p></div></div>';
            $this->logResult("error::get_code::", $ret);
            return $javascript . $button;
        }
        $payUrl = substr($ret, stripos($ret, 'http'));
        $payUrl = explode("'<", $payUrl)[0];


        $button = '<div id="paymentDiv"><a href="' . $payUrl . '"><div style="text-align:center" id="qrcode"><p>立即支付</p></div></a></div>';
        $this->logResult("log::get_code::code_url:", $payUrl);
        $this->logResult("log::get_code::button:" . $button);
        return $javascript . $button;
    }

    function logResult($word = '', $var = array())
    {
        if (!WXPAY_DEBUG) {
            return true;
        }
        $output = strftime("%Y%m%d %H:%M:%S", time()) . "\n";
        $output .= $word . "\n";
        if (!empty($var)) {
            $output .= print_r($var, true) . "\n";
        }
        $output .= "\n";

        $log_path = ROOT_PATH . "/data/log/";
        if (!is_dir($log_path)) {
            @mkdir($log_path, 0777, true);
        }

        file_put_contents($log_path . "gogozhifuzfb.txt", $output, FILE_APPEND | LOCK_EX);
    }

    /**
     * 响应操作
     */
    function respond()
    {
        $this->payment = get_payment($_GET['code']);

        $appId = $this->payment['gogozhifuzfb_appid']; // APPID
        $appSecret = $this->payment['gogozhifuzfb_appsecret']; // APPSECRET

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