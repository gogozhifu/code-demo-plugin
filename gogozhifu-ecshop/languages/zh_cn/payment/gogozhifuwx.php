<?php

/**
 * GOGO支付插件语言包
 *
 * GOGO支付 - 更好的个人支付解决方案
 * 免费注册使用、个人免签约、无手续费、免挂机、实时回调
 * https://www.gogozhifu.com
 * WX: gump994
 * QQ: 653107385
 */

global $_LANG;
define("JS_QR",false);//是否用js生成支付二维码。false:使用服务器端生成支付二维码，true：使用浏览器js生成支付二维码。默认为false
define("WXPAY_DEBUG",true);
define("QUERY_INTERVAL",5);//以秒为单位，首次请求默认为20秒，效果最佳, 值越小，用户体验越好，服务器压力越大，反之用户体验越差，服务器压力越小。 推荐10

$_LANG['gogozhifuwx'] = 'GOGO支付-微信支付';
$_LANG['gogozhifuwx_desc'] = '当前是GOGO支付的微信支付插件。GOGO支付 - 更好的个人支付解决方案。 免费注册使用、个人免签约、无手续费、免挂机、实时回调。';
$_LANG['gogozhifuwx_appid'] = '应用ID(AppID)';
$_LANG['gogozhifuwx_appsecret'] = '应用密钥(AppSecret)';
$_LANG['gogozhifuwx_button'] = '立即用GOGO支付';
