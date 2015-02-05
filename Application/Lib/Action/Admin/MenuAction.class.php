<?php
class MenuAction extends PublicAction {
    /**
     *商品类别菜单显示 
     */
	public function index() {
		$result = R ( "Api/Api/getarraymenu" );
		$this->assign ( "menu", $result );
		$this->assign ( "menulist", $result );
		$this->display ();
	}
    
    /**
     *增加类别菜单 
     */
	public function addmenu() {
		$result = R ( "Api/Api/getarraymenu" );
		$this->assign ( "menulist", $result );
		if($_POST){
			$result = R ( "Api/Api/addmenu", array (
					$_POST ['parent'],
					$_POST ['name'],
					$_POST ['addmenu'] 
			) );
			if($result){
				$flag=array(
				   "statusCode"=>"200",
				   "message"=>"保存类别成功!",
				   "tabid"=>"",
				   "dialogid"=>"dialog-mask",
				   "closeCurrent"=>true,
				   "forward"=>"",
				   "forwardConfirm"=>""
				);
			}else{
				$flag=array(
				   "statusCode"=>"301",
				   "message"=>"保存类别失败!!!",
				   "tabid"=>"Menu",
				   "dialogid"=>"dialog-mask",
				   "closeCurrent"=>false,
				   "forward"=>"",
				   "forwardConfirm"=>""
				);
			}
			$this->ajaxReturn( $flag );
		}else if($_GET){
			if($_GET['id']){
				$Umenu =  M ( "Menu" )->where('id = '.$_GET['id'])->select ();
			}else{
				$Umenu[0]['pid'] = $_GET['zid'];
			}
			$this->assign ( "Umenu", $Umenu[0] );
			$this->display();
		}else{
			$this->display();
		}
	}
    
    /**
     *删除类别菜单 
     */
	public function del() {
		if($result){
			$flag=array(
			   "statusCode"=>"200",
			   "message"=>"删除类别成功",
			   "tabid"=>"",
			   "dialogid"=>"dialog-mask",
			   "closeCurrent"=>false,
			   "forward"=>U('Admin/Menu/index'),
			   "forwardConfirm"=>""
			);
		}else{
			$flag=array(
			   "statusCode"=>"301",
			   "message"=>"删除类别失败",
			   "tabid"=>"",
			   "dialogid"=>"dialog-mask",
			   "closeCurrent"=>false,
			   "forward"=>"",
			   "forwardConfirm"=>""
			);
		}
		$this->ajaxReturn( $flag );
	}
}