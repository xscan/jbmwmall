<?php
// 本类由系统自动生成，仅供测试用途
class WeiwebAction extends Action {
	public $config; //微信配置信息
	public $ajaxdata;
	
	
	Public function _initialize(){
		//获取微信配置信息
		$this->config = M( "Wxconfig" )->where (array("id" => "1"))->find();
	}
	 
	public function index(){
		header("content-type:text/html;charset=utf-8");
		//import('@.COM.Connadodb');
		import('Connadodb',APP_PATH . 'Common/COM', '.class.php');
		//get传入openid，
	
		$this->assign( "config", $this->config);
		
		$db= new Connadodb();
		$db->opendb();
		$openid=I('get.openid');
		$sqlo="select mem_emp_id
			from wx_mem_emp 
			where toUserName='".$openid."'"; 
        $arr = $db->queryall($sqlo);   //根据openid查询mem_id
        //判断用户是否注册为微会员，没有注册跳转到注册页面，注册则执行查询
		if(empty($arr[0]))
		{
			$db->closedb();//关闭数据库
			//跳转到注册页
			//$this->redirect('Weiweb/adduser','openid=' . $openid, 5, '<h4>跳转到注册页面</h1>');
			$this->redirect('Weiweb/adduser','openid=' . $openid, 0, '');
			//alert("info","欢迎使用微信平台",U( "Weiweb/adduser/openid/".$openid ));
		}
		$dayinfo = getBeforeDaystime(90);
        //获取项目消费明细(默认三个月年内)
		$mem_id=$arr[0]['mem_emp_id'];                
		$sql="select a.doc_date, c.item_name,b.money ,b.r_j 
			from  dbo.doc_m as a ,dbo.doc_item as b,info_item as c
			where a.doc_id=b.doc_id and c.item_id = b.item_id
			and a.mem_id='".$mem_id."' and a.doc_date >='" . $dayinfo['beginTime'] . "' " .
			"and a.doc_date <= '" . $dayinfo['endTime'] . "' and a.state > 1 order by a.doc_date desc ";
        $arr = $db->queryall($sql);    
        $this->assign('doc_item',$arr);
        
        //获取产品消费明细
        $sqla="select a.doc_date , c.ware_name , b.money , b.amount 
			from  dbo.doc_m as a,doc_ware as b,info_ware as c
			where a.mem_id='".$mem_id."'
			and a.doc_id=b.doc_id and b.ware_id=c.ware_id 
			and a.doc_date >='" . $dayinfo['beginTime'] . "' " .
			"and a.doc_date <= '" . $dayinfo['endTime'] . "' and a.state > 1 order by a.doc_date desc ";
        $arr = $db->queryall($sqla);    
        $this->assign('doc_ware',$arr);
        
        //获取会员资金变动明细
        $sqlb="select b.fund_time , b.mem , b.fund_type , b.item2
			from mem_fund as b
			where b.mem_id='".$mem_id."'
			and fund_time >='" . $dayinfo['beginTime'] . "' 
			and fund_time <='" . $dayinfo['endTime'] ."' order by fund_time desc";
        $arr = $db->queryall($sqlb);    
        $this->assign('mem_fund',$arr);
        
        //获取会员计次信息
        $sqlc="select c.item_name , b.sum_t - b.pay_t as num , last_time
			from mem_item as b , info_item as c
			where b.mem_id='".$mem_id."' 
			and b.item_id=c.item_id and (b.sum_t-b.pay_t) > 0 order by last_time";
        $arr = $db->queryall($sqlc);    
        $this->assign('mem_item',$arr);

        //获取会员信息
        $sqld="select a.mem_name,a.tel_no,a.mem_id,c.level_name , a.balance , a.integral
			from mem_info as a , card_level as c
			where a.level_id=c.level_id
			and a.mem_id='" .$mem_id . "'";
        $arr = $db->queryall($sqld);    //返回用户信息
        $this->assign('mem_info',$arr);	
        $db->closedb();//关闭数据库
	 
        $this->display();
    }

    //输出注册页面
    public function adduser(){
       $openid=I('get.openid');
		$this->assign( "config", $this->config);
       $this->assign('openid',$openid);		   
       $this->display();
    }
    
	//注册后台逻辑
    public function insertuser(){
		//p($_POST);
		//die;
        header("content-type:text/html;charset=utf-8");
        $openid=I("post.openid");//获取表单提交的openid
        $tel=I("post.tel_no");//获取表单提交的手机号码
        $memid=I("post.mem_id");//获取表单提交的会员编号
        //import('@.COM.Connadodb');//引入数据库类
		$this->assign( "config", $this->config);
		import('Connadodb',APP_PATH . 'Common/COM', '.class.php');
        $db= new Connadodb();
        $db->opendb();
        //1.判断是否是实体会员，？是，注册。否，跳转到提示页面 	
        $sql="select mem_name from mem_info 
			where mem_id='".$memid."' and tel_no='" . $tel . "'";
        $arr = $db->queryall($sql);
        if(empty($arr[0]))//判断用户是否注册为微会员，没有注册跳转到注册页面，注册则执行查询
        {
			$this->ajaxdata['status'] = 300;
			$this->ajaxdata['info']  = "你没有注册实体会员或手机号码错误，请联系服务员!!!";
			$this->ajaxdata['size'] = 20;
			$this->ajaxdata['url'] = U("Weiweb/adduser?openid=".$openid );//"Weiweb/adduser?openid=".$openid;
			$this->ajaxReturn($this->ajaxdata);
		 	//$this->redirect('Weiweb/adduser','openid=' . $openid, 5, '<h4>你没有注册实体会员或手机号码错误，请联系店面</h1>');	
			//alter("error","欢迎使用微信平台",U("Weiweb/adduser/openid/".$openid ));
        }
        //$this->redirect('weiweb/ok');
        $wx_mem_emp = array(
            'mem_emp_id' => mb_convert_encoding($memid,'GBK','UTF-8'),
            'toUserName' => mb_convert_encoding($openid,'GBK','UTF-8'),
            'toNickname' => $arr[0]['mem_name'],
            'fromUserName' => mb_convert_encoding($this->config['ini_num'],'GBK','UTF-8'),
            'fromNickname' => mb_convert_encoding($this->config['num'],'GBK','UTF-8'),
            'regdate' => date('Y-m-d H:i:s'),
            'lastdate' =>  date('Y-m-d H:i:s') ,
            'mem' => mb_convert_encoding('手机注册','GBK','UTF-8')
            );
        $db->getdb()->BeginTrans();
        $insertSQL = $db->insertarr('wx_mem_emp', $wx_mem_emp);
        if ($insertSQL > 0){
			$db->getdb()->CommitTrans();
			//$rtn = "注册微会员成功:".$insertSQL;
			//$this->redirect('weiweb/ok');
			//alert("info","欢迎使用微信平台",U( "Weiweb/index/openid/".$openid ));
			$this->ajaxdata['status'] = 200;
			$this->ajaxdata['info']  = "注册微会员成功!";
			$this->ajaxdata['url'] = U("Weiweb/index?openid=".$openid );
			$this->ajaxReturn($this->ajaxdata);
        }else{
			$db->getdb()->RollbackTrans();
			//$rtn = "注册微会员失败:".$db->getdb()->ErrorMsg();
			//echo mb_convert_encoding($rtn,'GBK','UTF-8');
			//alert("error","注册微会员失败!!!",U( "Weiweb/adduser/openid/".$openid ));
			$this->ajaxdata['status'] = 300;
			$this->ajaxdata['info']  = "注册微会员失败!!!";
			$this->ajaxdata['url'] = U("Weiweb/adduser?openid=".$openid );
			$this->ajaxReturn($this->ajaxdata);
        }
        $this->display();
    }

    //成功
    public function ok(){
        $this->display();
    }

}