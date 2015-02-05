<?php
class GoodAction extends PublicAction {
	/**
	*初始化方法
	*/
	function _initialize() {
		parent::_initialize ();
	}
	
	/**
	*商品显示
	*/
	public function index() {
		import ( 'ORG.Util.Page' );
		$m = M( "Good" );
		
		$count = $m->count (); // 查询满足要求的总记录数
		$Page = new Page ( $count, 12 ); // 实例化分页类 传入总记录数和每页显示的记录数
		$Page -> setConfig('header', '条记录');
        $Page -> setConfig('theme', '<li><a>%totalRow% %header%</a></li> <li>%upPage%</li> <li>%downPage%</li> <li>%first%</li>  <li>%prePage%</li>  <li>%linkPage%</li>  <li>%nextPage%</li> <li>%end%</li> ');//(对thinkphp自带分页的格式进行自定义)
		$show = $Page->show (); // 分页显示输出
		
		$result = $m->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();
		for($i = 0; $i < count ( $result ); $i ++) {
			$menu_id = $result [$i] ["menu_id"];
			$result [$i] ["menu"] = R ( "Api/Api/getmenuvalue", array (
					$menu_id 
			) );
		}
		$menu = R ( "Api/Api/getarraymenu" );
		
		$this->assign ( "menu", $menu );
		$this->assign ( "addmenu", $menu );
		$this->assign ( "page", $show ); // 赋值分页输出
		$this->assign ( "result", $result ); //商品资料
		$this->display ();
	}
	
	/**
	*添加编辑商品
	*/
	public function addgood() {
		if ($_POST["goodid"]) { //编辑商品
			$data ["id"] = $_POST["goodid"];
			$data ["menu_id"] = $_POST ["addmenuid"];
			$data ["name"] = $_POST ["addname"];
			$data ["price"] = $_POST ["addprice"];
			$data ["old_price"] = $_POST ["add_old_price"];
			$data ["sort"] = $_POST ["addsort"];
			$data ["image"] = $_POST["addimage"];
			if ($_FILES["fileselect"]["error"][0] <= 0) {
    			if ($_FILES ['addimage'] ['name'] !== '') {
    				$img = $this->upload ();
    				$picurl = $img [0] [savename];
    				$data ["image"] = $picurl;
    			}
         }
            
			$data ["status"] = $_POST ["addstatus"];
			$data ["detail"] = $_POST ["editorValue"];
			$result = R ( "Api/Api/addgood", array($data) );
			if($result){
				$flag=array(
				   "statusCode"=>"200",
				   "message"=>"保存商品信息成功!",
				   "tabid"=>"",
				   "dialogid"=>"dialog-mask",
				   "closeCurrent"=>false,
				   "forward"=>"",
				   "forwardConfirm"=>""
				);
			}else{
				$flag=array(
				   "statusCode"=>"301",
				   "message"=>"保存商品信息失败!!!",
				   "tabid"=>"Menu",
				   "dialogid"=>"dialog-mask",
				   "closeCurrent"=>false,
				   "forward"=>"",
				   "forwardConfirm"=>""
				);
			}
			$this->ajaxReturn( $flag );
		}else{ //添加商品
			$data ["menu_id"] = $_POST ["addmenuid"];
			$data ["name"] = $_POST ["addname"];
			$data ["price"] = $_POST ["addprice"];
			$data ["old_price"] = $_POST ["add_old_price"];
			$data ["sort"] = $_POST ["addsort"];
			if ($_FILES ['addimage'] ['name'] !== '') {
				$img = $this->upload ();
				$picurl = $img [0] [savename];
				$data ["image"] = $picurl;
			} else {
				$this->error ( "未上传图片！" );
			}
            //p($_FILES);
			$data ["status"] = $_POST ["addstatus"];
			$data ["detail"] = $_POST ["editorValue"];
			$result = R( "Api/Api/addgood", array($data) );
			if($result){
				$flag=array(
				   "statusCode"=>"200",
				   "message"=>"保存成功",
				   "tabid"=>"",
				   "dialogid"=>"dialog-mask",
				   "closeCurrent"=>false,
				   "forward"=>"",
				   "forwardConfirm"=>""
				);
			}else{
				$flag=array(
				   "statusCode"=>"301",
				   "message"=>"保存失败",
				   "tabid"=>"Menu",
				   "dialogid"=>"dialog-mask",
				   "closeCurrent"=>false,
				   "forward"=>"",
				   "forwardConfirm"=>""
				);
			}
			$this->ajaxReturn( $flag );
		}
	}
	
	/**
	*删除商品信息
	*/
	public function delgood() {
		$result = R ( "Api/Api/delgood", array (
				$_GET ["id"] 
		) );
		if($result){
				$flag=array(
				   "statusCode"=>"200",
				   "message"=>"删除成功",
				   "tabid"=>"",
				   "dialogid"=>"dialog-mask",
				   "closeCurrent"=>false,
				   "forward"=>"",
				   "forwardConfirm"=>""
				);
			}else{
				$flag=array(
				   "statusCode"=>"301",
				   "message"=>"删除失败",
				   "tabid"=>"Menu",
				   "dialogid"=>"dialog-mask",
				   "closeCurrent"=>false,
				   "forward"=>"",
				   "forwardConfirm"=>""
				);
			}
			$this->ajaxReturn( $flag );
	}
	
	/**
	*根据编号获取商品信息
	*/
	public function getgoodid() {
		$id = $_POST ["id"];
		$result = M ( "Good" )->where ( array (
				"id" => $id 
		) )->find ();
		if ($result) {
			$this->ajaxReturn ( $result );
		}
	}
}