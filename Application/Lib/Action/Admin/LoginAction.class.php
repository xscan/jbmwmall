<?php
class LoginAction extends Action {
    
    /**
     *登录页显示
     */
	public function index() {
        
        //测试
			/*
        import ( 'Connadodb', APP_PATH . 'Common/COM', '.class.php' );
        $db = new Connadodb();
        $sql = "select mem_id,fz from mem_info";
        $db->opendb();
        $arr = $db->queryall($sql);
        $db->closedb();
        p($arr);
        */
		//p(getBeforeDaystime(90));
		/* $tmp = array(
			array(0=>0,'val'=>'00'),
			array(1=>1,'val'=>'01'),
			array(2=>2,'val'=>'02'),
		
		);
		foreach($tmp as $k=>$v){
			p($v);
		} */
		//p($tmp);
		$this->display ( "Public:login" );
	}
    
    /**
     *登录
     */
	public function login() {
		$time = date('Y-m-d h:i:s',time());
		$loginip = get_client_ip();
		$data = array (
				$_POST ["username"],
				$_POST ["password"],
				$time,
				$loginip
		);
		//echo $time.'<br />'.$loginip;die();
		$result = R ( "Api/Api/login", $data );
		if ($result) {
			$_SESSION ["wadmin"] = $result;
			$_SESSION ["uid"] = $result["id"];
			$_SESSION ["username"] = $result["username"];
			
			if($result['username'] == C(RBAC_SUPERADMIN)){
				session(C('ADMIN_AUTH_KEY'), true);
			}
			import('ORG.Util.RBAC');
			RBAC::saveAccessList();
			//echo "<pre>";
			//print_r($_SESSION);die;
			redirect(__GROUP__);
			//$this->success ( "登录成功", U ( "Admin/Index/index" ) );
			alert("success","登录成功",U( "Admin/Index/index" ) );
		} else {
			//$this->error ( "登录失败", U ( "Admin/Index/index" ) );
			alert( "error","登录失败!!!" ,U( "Admin/Index/index" ));
		}
	}
    
    /**
     *注销
     */
	public function logout() {
		unset ($_SESSION["wadmin"]  );
		unset ($_SESSION['superadmin']);
		unset ($_SESSION['uid']);
		unset ($_SESSION['username']);
		//$this->success ( '已注销登录！', U ( "Admin/Login/index" ) );
		alert( "info","已注销登录！", U( "Admin/Login/index" ) );
	}
}