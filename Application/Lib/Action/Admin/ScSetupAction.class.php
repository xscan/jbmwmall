<?php
// 本类由系统自动生成，仅供测试用途
class ScSetupAction extends PublicAction {
   
    
    /**
     *设置
     */
	public function setting() {
		$result = R ( "Api/Api/setting", array (
				$_POST ["name"],
				$_POST ["notification"] 
		) );
		if($result){
			$flag = array(
				"statusCode"=>"200",
				"message"=>"修改设置成功!",
				"tabid"=>"",
				"dialogid"=>"dialog-mask",
				"closeCurrent"=>false,
				"forward"=>"",
				"forwardConfirm"=>""
			);
			
		}else{
			$flag = array(
				"statusCode"=>"300",
				"message"=>"修改设置失败!!!",
				"tabid"=>"",
				"dialogid"=>"dialog-mask",
				"closeCurrent"=>false,
				"forward"=>"",
				"forwardConfirm"=>""
			);
		}
		$this->ajaxReturn( $flag );
	}
    
    /**
     *模板设置显示
     */
	public function set() {
		if ($_SESSION ["wadmin"]) {
			$result = R ( "Api/Api/getsetting" );
			$this->assign ( "info", $result );
			
			$themedir = getDir("./Application/Tpl/App");
			
			for ($i = 0; $i < count($themedir); $i++) {
				$theme[$i] = simplexml_load_file("./Application/Tpl/App".$themedir[$i]."/config.xml");
				if (isset($theme[$i])) {
					$theme[$i]->dir = $themedir[$i];
				}
			}
			$this->assign("theme",$theme);
			$this->assign("settheme",$result["theme"]);
			$payresult = R( "Api/Api/getalipay" );
			$this->assign( "alipay", $payresult );
			$this->display();
		}
		
	}
    
    /**
     *设置模板
     */
	public function settheme(){
		$name = $_GET["name"];
		$data = array("id"=>1,"theme"=>$name);
		$result = M("Info")->save($data);
		if($result){
			$flag = array(
				"statusCode"=>"200",
				"message"=>"设置模板成功!",
				"tabid"=>"",
				"dialogid"=>"dialog-mask",
				"closeCurrent"=>false,
				"forward"=>"",
				"forwardConfirm"=>""
			);
			
		}else{
			$flag = array(
				"statusCode"=>"300",
				"message"=>"设置模板失败!!!",
				"tabid"=>"",
				"dialogid"=>"dialog-mask",
				"closeCurrent"=>false,
				"forward"=>"",
				"forwardConfirm"=>""
			);
		}
		$this->ajaxReturn( $flag );
	}
    
    /**
     *设置支付宝信息
     */
	public function setalipay(){
		$result = R ( "Api/Api/setalipay", array (
				$_POST ["alipayname"],
				$_POST ["partner"],
				$_POST ["key"]
		) );
		
		if($result){
			$flag = array(
				"statusCode"=>"200",
				"message"=>"设置支付宝信息成功!",
				"tabid"=>"",
				"dialogid"=>"dialog-mask",
				"closeCurrent"=>false,
				"forward"=>"",
				"forwardConfirm"=>""
			);
			
		}else{
			$flag = array(
				"statusCode"=>"300",
				"message"=>"设置支付宝信息失败!!!",
				"tabid"=>"",
				"dialogid"=>"dialog-mask",
				"closeCurrent"=>false,
				"forward"=>"",
				"forwardConfirm"=>""
			);
		}
		$this->ajaxReturn( $flag );
	}
    
    /**
     *预览模板
     */
    public function preview(){
        $this->display();
    }
}