<?php
/**
 * GOGO支付 - 更好的个人支付解决方案
 * 免费注册使用、个人免签约、无手续费、实时回调
 * https://www.gogozhifu.com
 * WX: gump994
 */
namespace app\index\controller;

class Go extends Base
{
    public function __construct()
    {
        parent::__construct();

        define('THIRD_LOGIN_CALLBACK',  $GLOBALS['http_type'] . $_SERVER['HTTP_HOST'] . '/index.php/user/logincallback/type/');

        //判断用户登录状态
        $ac = request()->action();
        if (in_array($ac, ['login', 'logout', 'ajax_login', 'reg', 'findpass', 'findpass_msg', 'findpass_reset', 'reg_msg', 'oauth', 'logincallback','visit'])) {

        } else {
            if ($GLOBALS['user']['user_id'] < 1) {
                model('User')->logout();
                return $this->error(lang('index/no_login').'', url('user/login'));
            }
            /*
            $res = model('User')->checkLogin();
            if($res['code']>1){
                model('User')->logout();
                return $this->error($res['msg'], url('user/login'));
            }
            */
            $this->assign('obj', $GLOBALS['user']);
        }
    }

    public function gogozhifu()
    {
        $param = input();

        $order_code = htmlspecialchars(urldecode(trim($param['order_code'])));
        $order_id = intval((trim($param['order_id'])));
        $payment = strtolower(htmlspecialchars(urldecode(trim($param['payment']))));

        if (empty($order_code) && empty($order_id) && empty($payment)) {
            return $this->error(lang('param_err'));
        }

        if ($GLOBALS['config']['pay'][$payment]['appid'] == '') {
            return $this->error(lang('index/payment_status'));
        }

        //核实订单
        $where['order_id'] = $order_id;
        $where['order_code'] = $order_code;
        $where['user_id'] = $GLOBALS['user']['user_id'];
        $res = model('Order')->infoData($where);
        if ($res['code'] > 1) {
            return $this->error(lang('index/order_not'));
        }
        if ($res['info']['order_status'] == 1) {
            return $this->error(lang('index/order_payed'));
        }

        $cp = 'app\\common\\extend\\pay\\' . ucfirst($payment);
        if (class_exists($cp)) {
            $c = new $cp;
            $payment_res = $c->submit($GLOBALS['user'], $res['info'], $param);
        }
        if ($payment == 'gogozhifu') {
            echo $payment_res;
        }
    }

}
