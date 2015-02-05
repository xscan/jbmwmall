<?php
class OrderAction extends PublicAction {
    /**
     * 
     */
	function _initialize() {
		parent::_initialize ();
	}
    
    /**
     *订单显示 
     */
	public function index() {
		import ( 'ORG.Util.Page' );
		$m = D ( "Order" );
		
		$count = $m->count (); // 查询满足要求的总记录数
		$Page = new Page ( $count, 10 ); // 实例化分页类 传入总记录数和每页显示的记录数
		$Page -> setConfig('header', '条记录');
        $Page -> setConfig('theme', '<li><a>%totalRow% %header%</a></li> <li>%upPage%</li> <li>%downPage%</li> <li>%first%</li>  <li>%prePage%</li>  <li>%linkPage%</li>  <li>%nextPage%</li> <li>%end%</li> ');//(对thinkphp自带分页的格式进行自定义)
		$show = $Page->show (); // 分页显示输出
		
		$result = $m->limit ( $Page->firstRow . ',' . $Page->listRows )->order("id desc")->relation(true)->select ();
		$this->assign ( "result", $result );
		$this->assign ( "page", $show ); // 赋值分页输出
		$this->display ();
	}
    
    /**
     *删除 
     */
	public function del(){
		$result = R ( "Api/Api/delorder", array (
				$_GET ['id'],
			) );
		$flag=array(
			"statusCode"=>"200",
			"message"=>"操作成功",
			"tabid"=>"",
			"dialogid"=>"dialog-mask",
			"closeCurrent"=>false,
			"forward"=>"",
			"forwardConfirm"=>""
		);
		$this->ajaxReturn( $flag );
	}
    
    /**
     *公告发布 
     */
	public function publish(){
		$result = R ( "Api/Api/publish", array (
				$_GET ['id'],
			) );
		$flag=array(
			"statusCode"=>"200",
			"message"=>"操作公告成功",
			"tabid"=>"",
			"dialogid"=>"dialog-mask",
			"closeCurrent"=>false,
			"forward"=>"",
			"forwardConfirm"=>""
		);
		$this->ajaxReturn( $flag );
	}
    
    /**
     *支付 
     */
	public function payComplete(){
		$result = R ( "Api/Api/payComplete", array (
				$_GET ['id'],
			) );
		$flag=array(
			"statusCode"=>"200",
			"message"=>"操作支付成功",
			"tabid"=>"",
			"dialogid"=>"dialog-mask",
			"closeCurrent"=>false,
			"forward"=>"",
			"forwardConfirm"=>""
		);
		$this->ajaxReturn( $flag );
	}
}