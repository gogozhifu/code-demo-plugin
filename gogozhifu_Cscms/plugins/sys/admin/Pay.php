<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2008-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-10-31
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pay extends Cscms_Controller {

	function __construct(){
	    parent::__construct();
	    $this->load->model('Csadmin');
		$this->lang->load('admin_pay');
        $this->Csadmin->Admin_Login();
	}

	public function index(){
        $this->load->view('pay_setting.html');
	}

	public function save(){

	    $CS_Alipay = intval($this->input->post('CS_Alipay', TRUE));
	    $CS_Alipay_JK = intval($this->input->post('CS_Alipay_JK', TRUE));
	    $CS_Alipay_ID = trim($this->input->post('CS_Alipay_ID', TRUE));
	    $CS_Alipay_Key = trim($this->input->post('CS_Alipay_Key', TRUE));
	    $CS_Alipay_Name = trim($this->input->post('CS_Alipay_Name', TRUE));

	    $CS_Tenpay = intval($this->input->post('CS_Tenpay', TRUE));
	    $CS_Tenpay_ID = trim($this->input->post('CS_Tenpay_ID', TRUE));
	    $CS_Tenpay_Key = trim($this->input->post('CS_Tenpay_Key', TRUE));

	    $CS_Wypay = intval($this->input->post('CS_Wypay', TRUE));
	    $CS_Wypay_ID = trim($this->input->post('CS_Wypay_ID', TRUE));
	    $CS_Wypay_Key = trim($this->input->post('CS_Wypay_Key', TRUE));

	    $CS_Ybpay = intval($this->input->post('CS_Ybpay', TRUE));
	    $CS_Ybpay_ID = trim($this->input->post('CS_Ybpay_ID', TRUE));
	    $CS_Ybpay_Key = trim($this->input->post('CS_Ybpay_Key', TRUE));

	    $CS_Cspay = intval($this->input->post('CS_Cspay', TRUE));
	    $CS_Cspay_ID = trim($this->input->post('CS_Cspay_ID', TRUE));
	    $CS_Cspay_Key = trim($this->input->post('CS_Cspay_Key', TRUE));

        $CS_Wxpay = intval($this->input->post('CS_Wxpay', TRUE));
        $CS_Wxpay_ID = trim($this->input->post('CS_Wxpay_ID', TRUE));
        $CS_Wxpay_Key = trim($this->input->post('CS_Wxpay_Key', TRUE));
        $CS_Wxpay_Mchid = trim($this->input->post('CS_Wxpay_Mchid', TRUE));

        $CS_Ggzf = intval($this->input->post('CS_Ggzf', TRUE));
        $CS_Ggzf_ID = trim($this->input->post('CS_Ggzf_ID', TRUE));
        $CS_Ggzf_Key = trim($this->input->post('CS_Ggzf_Key', TRUE));

        $strs="<?php"."\r\n";
        $strs.="define('CS_Alipay',".$CS_Alipay.");  //???????????????  \r\n";
        $strs.="define('CS_Alipay_JK',".$CS_Alipay_JK.");  //???????????????,1?????????????????????2???????????????\r\n";
        $strs.="define('CS_Alipay_Name','".$CS_Alipay_Name."');  //???????????????  \r\n";
        $strs.="define('CS_Alipay_ID','".$CS_Alipay_ID."');  //?????????ID  \r\n";
        $strs.="define('CS_Alipay_Key','".$CS_Alipay_Key."');  //???????????????KEY  \r\n";
        $strs.="define('CS_Tenpay',".$CS_Tenpay.");  //???????????????  \r\n";
        $strs.="define('CS_Tenpay_ID','".$CS_Tenpay_ID."');  //?????????ID  \r\n";
        $strs.="define('CS_Tenpay_Key','".$CS_Tenpay_Key."');  //???????????????KEY  \r\n";
        $strs.="define('CS_Wypay',".$CS_Wypay.");  //????????????  \r\n";
        $strs.="define('CS_Wypay_ID','".$CS_Wypay_ID."');  //??????ID  \r\n";
        $strs.="define('CS_Wypay_Key','".$CS_Wypay_Key."');  //???????????????KEY  \r\n";
        $strs.="define('CS_Ybpay',".$CS_Ybpay.");  //????????????  \r\n";
        $strs.="define('CS_Ybpay_ID','".$CS_Ybpay_ID."');  //??????ID  \r\n";
        $strs.="define('CS_Ybpay_Key','".$CS_Ybpay_Key."');  //???????????????KEY \r\n";
        $strs.="define('CS_Wxpay',".$CS_Wxpay.");  //????????????  \r\n";
        $strs.="define('CS_Wxpay_ID','".$CS_Wxpay_ID."');  //??????APPID  \r\n";
        $strs.="define('CS_Wxpay_Key','".$CS_Wxpay_Key."');  //???????????????KEY \r\n";
        $strs.="define('CS_Wxpay_Mchid','".$CS_Wxpay_Mchid."');  //??????Mchid \r\n";
        $strs.="define('CS_Cspay',".$CS_Cspay.");  //????????????  \r\n";
        $strs.="define('CS_Cspay_ID','".$CS_Cspay_ID."');  //??????ID  \r\n";
        $strs.="define('CS_Cspay_Key','".$CS_Cspay_Key."');  //???????????????KEY \r\n";

        $strs.="define('CS_Ggzf',".$CS_Ggzf.");  //GOGO??????  \r\n";
        $strs.="define('CS_Ggzf_ID','".$CS_Ggzf_ID."');  //GOGO?????? appid  \r\n";
        $strs.="define('CS_Ggzf_Key','".$CS_Ggzf_Key."');  //GOGO?????? secret  \r\n";

        //?????????
        if (!write_file(CSCMS.'sys/Cs_Pay.php', $strs)){
            getjson(L('plub_01'));
        }else{
            $info['url'] = site_url('pay');
            getjson($info,0);
        }
	}

    //??????????????????
	public function lists(){
        $kstime = $this->input->get_post('kstime',true);
        $jstime = $this->input->get_post('jstime',true);
        $dingdan= str_replace('%','',$this->input->get_post('dingdan',true));
        $pid  = intval($this->input->get_post('pid'));
        $zd   = $this->input->get_post('zd',true);
        $key  = $this->input->get_post('key',true);
	        $page = intval($this->input->get('page'));
        if($page==0) $page=1;
		$kstimes=empty($kstime)?0:strtotime($kstime)-86400;
		$jstimes=empty($jstime)?0:strtotime($jstime)+86400;
		if($kstimes>$jstimes) $kstimes=strtotime($kstime);

        $data['page'] = $page;
        $data['dingdan'] = $dingdan;
        $data['pid'] = $pid;
        $data['zd'] = $zd;
        $data['key'] = $key;
        $data['kstime'] = $kstime;
        $data['jstime'] = $jstime;

        $sql_string = "SELECT * FROM ".CS_SqlPrefix."pay where 1=1";
		if($pid>0){
             $sql_string.= " and pid=".($pid-1)."";
		}
		if(!empty($dingdan)){
             $sql_string.= " and dingdan like '%".$dingdan."%'";
		}
		if(!empty($key)){
			 if($zd=='name'){
                 $uid=getzd('user','id',$key,'name');
			 }else{
                 $uid=$key;
			 }
			 $sql_string.= " and uid=".intval($uid)."";
		}
		if($kstimes>0){
             $sql_string.= " and addtime>".$kstimes."";
		}
		if($jstimes>0){
             $sql_string.= " and addtime<".$jstimes."";
		}
        $sql_string.= " order by addtime desc";
        $count_sql = str_replace('*','count(*) as count',$sql_string);
        $query = $this->db->query($count_sql)->result_array();
        $total = $query[0]['count'];

        $base_url = site_url('pay/lists')."?dingdan=".$dingdan."&zd=".$zd."&kstime=".$kstime."&jstime=".$jstime."&key=".$key."&pid=".$pid."&page=";
        $per_page = 15; 
        $totalPages = ceil($total / $per_page)?ceil($total / $per_page):1; // ?????????
        $page = ($page>$totalPages)?$totalPages:$page;
        $data['nums'] = $total;
        if($total<$per_page){
            $per_page=$total;
        }
        $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
        $query = $this->db->query($sql_string);

        $data['pay'] = $query->result();
        $data['page_data'] = page_data($total,$page,$totalPages);
        $data['page_list'] = admin_page($base_url,$page,$totalPages); //???????????????
        $this->load->view('pay_list.html',$data);
	}

    //???????????????????????????
	public function init(){
        $id = intval($this->input->get('id'));
        $row=$this->db->query("SELECT * FROM ".CS_SqlPrefix."pay where id=".$id."")->row();
		if($row){
			$edit['pid']=1;
            $this->Csdb->get_update('pay',$id,$edit);
			 //?????????????????????
            $this->db->query("update ".CS_SqlPrefix."user set rmb=rmb+".$row->rmb." where id=".$row->uid."");
			 //????????????
			$email=getzd('user','email',$row->uid);
            $this->load->model('Csemail');
            $title = L('plub_02',array($row->rmb));
            $this->Csemail->send($email,L('plub_03'),$title);  //??????????????????
		}
        $info['url'] = site_url('pay/lists').'?v='.rand(1000,9999);
        $info['msg'] = L('plub_04');
        getjson($info,0);
	}

    //??????????????????
	public function del(){
        $id = $this->input->get_post('id',true);
		if(empty($id)) getjson(L('plub_05'));
        $this->Csdb->get_del('pay',$id);
        $info['url'] = site_url('pay/lists').'?v='.rand(1000,9999);
        getjson($info,0);
	}

    //??????????????????
	public function spend(){
        $kstime = $this->input->get_post('kstime',true);
        $jstime = $this->input->get_post('jstime',true);
        $sid  = intval($this->input->get_post('sid'));
        $dir  = $this->input->get_post('dir',true);
        $zd   = $this->input->get_post('zd',true);
        $key  = $this->input->get_post('key',true);
	        $page = intval($this->input->get('page'));
        if($page==0) $page=1;
		$kstimes=empty($kstime)?0:strtotime($kstime)-86400;
		$jstimes=empty($jstime)?0:strtotime($jstime)+86400;
		if($kstimes>$jstimes) $kstimes=strtotime($kstime);

        $data['page'] = $page;
        $data['dir'] = $dir;
        $data['sid'] = $sid;
        $data['zd'] = $zd;
        $data['key'] = $key;
        $data['kstime'] = $kstime;
        $data['jstime'] = $jstime;

        $sql_string = "SELECT * FROM ".CS_SqlPrefix."spend where 1=1";
		if($sid>0){
             $sql_string.= " and sid=".($sid-1)."";
		}
		if(!empty($key)){
			 if($zd=='name'){
                 $uid=getzd('user','id',$key,'name');
			 }else{
                 $uid=$key;
			 }
			 $sql_string.= " and uid=".intval($uid)."";
		}
		if(!empty($dir)){
			 $sql_string.= " and dir='".$dir."'";
		}
		if($kstimes>0){
             $sql_string.= " and addtime>".$kstimes."";
		}
		if($jstimes>0){
             $sql_string.= " and addtime<".$jstimes."";
		}
        $sql_string.= " order by addtime desc";
        $count_sql = str_replace('*','count(*) as count',$sql_string);
        $query = $this->db->query($count_sql)->result_array();
        $total = $query[0]['count'];

        $per_page = 15; 
        $totalPages = ceil($total / $per_page)?ceil($total / $per_page):1; // ?????????
        $page = ($page>$totalPages)?$totalPages:$page;
        $data['nums'] = $total;
        if($total<$per_page){
            $per_page=$total;
        }
        $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
        $query = $this->db->query($sql_string);

        $base_url = site_url('pay/spend')."?dir=".$dir."&zd=".$zd."&kstime=".$kstime."&jstime=".$jstime."&key=".$key."&sid=".$sid."&page=";
        $data['spend'] = $query->result();
        $data['page_data'] = page_data($total,$page,$totalPages);
        $data['page_list'] = admin_page($base_url,$page,$totalPages); //???????????????
        $this->load->view('pay_spend.html',$data);
	}

    //??????????????????
	public function spend_del(){
        $id = $this->input->get_post('id',true);
		if(empty($id)) getjson(L('plub_05'));
        $this->Csdb->get_del('spend',$id);
        $info['url'] = site_url('pay/spend').'?v='.rand(1000,9999);
        getjson($info,0);
	}

    //??????????????????
	public function income(){
        $kstime = $this->input->get_post('kstime',true);
        $jstime = $this->input->get_post('jstime',true);
        $dir  = $this->input->get_post('dir',true);
        $sid  = intval($this->input->get_post('sid'));
        $zd   = $this->input->get_post('zd',true);
        $key  = $this->input->get_post('key',true);
	        $page = intval($this->input->get('page'));
        if($page==0) $page=1;
		$kstimes=empty($kstime)?0:strtotime($kstime)-86400;
		$jstimes=empty($jstime)?0:strtotime($jstime)+86400;
		if($kstimes>$jstimes) $kstimes=strtotime($kstime);

        $data['page'] = $page;
        $data['dir'] = $dir;
        $data['sid'] = $sid;
        $data['zd'] = $zd;
        $data['key'] = $key;
        $data['kstime'] = $kstime;
        $data['jstime'] = $jstime;

        $sql_string = "SELECT * FROM ".CS_SqlPrefix."income where 1=1";
		if($sid>0){
            $sql_string.= " and sid=".($sid-1)."";
		}
		if(!empty($key)){
            if($zd=='name'){
                $uid=getzd('user','id',$key,'name');
            }else{
                $uid=$key;
            }
            $sql_string.= " and uid=".intval($uid)."";
		}
		if(!empty($dir)){
			$sql_string.= " and dir='".$dir."'";
		}
		if($kstimes>0){
            $sql_string.= " and addtime>".$kstimes."";
		}
		if($jstimes>0){
            $sql_string.= " and addtime<".$jstimes."";
		}
        $sql_string.= " order by addtime desc";
        $count_sql = str_replace('*','count(*) as count',$sql_string);
        $query = $this->db->query($count_sql)->result_array();
        $total = $query[0]['count'];

        $per_page = 15; 
        $totalPages = ceil($total / $per_page)?ceil($total / $per_page):1; // ?????????
        $page = ($page>$totalPages)?$totalPages:$page;
        $data['nums'] = $total;
        if($total<$per_page){
            $per_page=$total;
        }
        $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
        $query = $this->db->query($sql_string);
        $data['income'] = $query->result();

        $base_url = site_url('pay/income')."?dir=".$dir."&zd=".$zd."&kstime=".$kstime."&jstime=".$jstime."&key=".$key."&sid=".$sid."&page=";
        $data['page_data'] = page_data($total,$page,$totalPages);
        $data['page_list'] = admin_page($base_url,$page,$totalPages); //???????????????
        $this->load->view('pay_income.html',$data);
	}

    //??????????????????
	public function income_del(){
        $id = $this->input->get_post('id',true);
		if(empty($id)) getjson(L('plub_05'));
        $this->Csdb->get_del('income',$id);
        $info['url'] = site_url('pay/income').'?v='.rand(1000,9999);
        getjson($info,0);
	}

    //???????????????
	public function card(){
        $card = str_replace('%','',$this->input->get_post('card',true));
        $sid  = intval($this->input->get_post('sid'));
        $zd   = $this->input->get_post('zd',true);
        $key  = $this->input->get_post('key',true);
	    $page = intval($this->input->get('page'));
        if($page==0) $page=1;

        $data['sid'] = $sid;
        $data['zd'] = $zd;
        $data['key'] = $key;
        $data['card'] = $card;

        $sql_string = "SELECT * FROM ".CS_SqlPrefix."paycard where 1=1";
		if(!empty($card)){
            $sql_string.= " and card like '%".$card."%'";
		}
		if($sid==1){
            $sql_string.= " and uid>0";
		}
		if($sid==2){
            $sql_string.= " and uid=0";
		}
		if(!empty($key)){
            if($zd=='name'){
                $uid=getzd('user','id',$key,'name');
            }else{
                $uid=$key;
            }
            $sql_string.= " and uid=".intval($uid)."";
		}

        $sql_string.= " order by addtime desc";
        $count_sql = str_replace('*','count(*) as count',$sql_string);
        $query = $this->db->query($count_sql)->result_array();
        $total = $query[0]['count'];

        $per_page = 15; 
        $totalPages = ceil($total / $per_page)?ceil($total / $per_page):1; // ?????????
        $pag = ($page>$totalPages)?$totalPages:$page;
        $data['nums'] = $total;
        if($total<$per_page){
              $per_page=$total;
        }
        $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
        $query = $this->db->query($sql_string);
        $data['paycard'] = $query->result();

        $base_url = site_url('pay/card')."?zd=".$zd."&card=".$card."&key=".$key."&sid=".$sid."&page=";
        $data['page_data'] = page_data($total,$page,$totalPages);
        $data['page_list'] = admin_page($base_url,$page,$totalPages); //???????????????
        $this->load->view('pay_card.html',$data);
	}

    //???????????????
	public function card_add(){
        $this->load->view('pay_card_add.html');
	}

    //???????????????
	public function card_save(){
	    $this->load->helper('string');
        $rmb = intval($this->input->post('rmb'));
        $nums = intval($this->input->post('nums'));
		for($j=0;$j<$nums;$j++){	
            $add['rmb']=$rmb;
            $add['card']=date('Ymd').random_string('alnum',10);
            $add['pass']=random_string('alnum',6);
            $add['addtime']=time();
            $this->Csdb->get_insert('paycard',$add);
		}
        $info['url'] = site_url('pay/card').'?v='.rand(1000,9999);
        $info['parent'] = 1;
        getjson($info,0);
	}

    //???????????????
	public function card_del(){
        $id = $this->input->get_post('id',true);
		if(empty($id)) getjson(L('plub_05'));
        $this->Csdb->get_del('paycard',$id);
        $info['url'] = site_url('pay/card').'?v='.rand(1000,9999);
        getjson($info,0);
	}
    public function card_look(){
        $id = intval($this->input->get_post('id',true));
        $card = $this->db->query("select * from ".CS_SqlPrefix."paycard where id=".$id)->row();
        $data['card'] = $card;
        $this->load->view('pay_card_look.html',$data);
    }
}