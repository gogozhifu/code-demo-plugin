<?php
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-04-10
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ggzf extends Cscms_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Csuser');
        $this->lang->load('pay');
    }

    //请求支付
    public function index($id=0,$sid=1)
    {
        $this->Csuser->User_Login();
        $id=(int)$id; //订单ID
        $sid=(int)$sid; //支付方式
        if($id==0)  msg_url(L('pay_01'),spacelink('pay'));
        $row=$this->Csdb->get_row('pay','*',$id);
        if(!$row || $row->uid!=$_SESSION['cscms__id']){
            msg_url(L('pay_02'),spacelink('pay'));
        }

        $appId = trim(CS_Ggzf_ID);
        $appSecret = trim(CS_Ggzf_Key);
        $url = "https://www.gogozhifu.com/shop/api/createOrder";

        $payId = $row->dingdan; /* 商户订单号*/
        $param = 'orderId' . $id;
        $type = $sid;
        $price = $row->rmb;
        $sign = md5($appId . $payId . $param . $type . $price. $appSecret);
        $data = array(
            'payId' => $payId,
            'param' => $param,
            'type' => $type,
            'price' => $price,
            'sign' => $sign,
            'isHtml' => 1,
            'notifyUrl' => get_link('pay/ggzf/notify_url'),/* 同步返回地址 */
            'returnUrl' => get_link('pay/ggzf/return_url'),/* 异步返回地址 */
        );
        echo $this->ggPost($url, $data);
    }

    //同步返回
    public function return_url()
    {
        msg_url('充值成功！',spacelink('pay'));
    }

    //异步返回
    public function notify_url()
    {
        //校验签名，确保安全
        $appId = trim(CS_Ggzf_ID); //APPID
        $appSecret = trim(CS_Ggzf_Key); //APPSECRET
        $payId = $this->input->get('payId',TRUE,TRUE);//商户订单号
        $param = $this->input->get('param',TRUE,TRUE);//创建订单的时候传入的参数
        $type = $this->input->get('type',TRUE,TRUE);//支付方式 ：微信支付为1 支付宝支付为2
        $price = $this->input->get('price',TRUE,TRUE);//订单金额
        $reallyPrice = $this->input->get('reallyPrice',TRUE,TRUE);//实际支付金额
        $sign = $this->input->get('sign',TRUE,TRUE);//校验签名，计算方式 = md5(appId + payId + param + type + price + reallyPrice + appSecret)

        //开始校验签名
        $_sign =  md5($appId . $payId . $param . $type . $price . $reallyPrice . $appSecret);
        if ($_sign != $sign) {
            echo "error_sign";//sign校验不通过
            exit();
        }
        $row=$this->Csdb->get_row('pay','*',$payId,'dingdan');
        if($row && $row->pid!=1){
            //增加金钱
            $this->db->query("update ".CS_SqlPrefix."user set rmb=rmb+".$row->rmb." where id=".$row->uid."");
            //改变状态
            $this->db->query("update ".CS_SqlPrefix."pay set pid=1 where id=".$row->id."");
            //发送通知
            $add['uida']=$row->uid;
            $add['uidb']=0;
            $add['name']=L('pay_11');
            $add['neir']=L('pay_17',array($row->rmb,$payId));
            $add['addtime']=time();
            $this->Csdb->get_insert('msg',$add);
        }
        echo "success";
    }

    /**
     * ggzf使用的post
     * @param $url
     * @param $data
     * @return mixed
     */
    public function ggPost($url, $data)
    {
        $headerArray = array(
            "App-Id: " . trim(CS_Ggzf_ID),
            "App-Secret: " . trim(CS_Ggzf_Key)
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
        //将返回的json对象解码成数组对象并返回
        //$output = json_decode($output,true);
        return $output;
    }
}
