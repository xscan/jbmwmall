<?php
class PublicAction extends Action {
    /**
     *初始化方法
     */
	public $flag;
	
	function _initialize() {
	   //判断SESSION是否有数据
		if (!isset($_SESSION[C('USER_AUTH_KEY')])) {
			$this->redirect ( "Admin/Login/index" );
		}
		$notAuth = in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODEL'))) || in_array(ACTION_NAME, explode(',', C('NOT_AUTH_ACTION')));
		
		$flag = array(
			   "statusCode"=>"300",
			   "message"=>"没有权限",
			   "tabid"=>"",
			   "dialogid"=>"dialog-mask",
			   "closeCurrent"=>true,
			   "forward"=>"",
			   "forwardConfirm"=>""
			);
		if (C('USER_AUTH_ON') && !$notAuth) {
			import('ORG.Util.RBAC');
			if(!RBAC::AccessDecision(GROUP_NAME)){
				$this->ajaxReturn( $flag );
			}
		}
	}
    
    /**
     *上传文件
     */
	public function upload() {
		import ( 'ORG.Net.UploadFile' );
		$upload = new UploadFile (); // 实例化上传类
		$upload->maxSize = 3145728; // 设置附件上传大小
		$upload->allowExts = array (
				'jpg',
				'gif',
				'png',
				'jpeg' 
		); // 设置附件上传类型
		$upload->savePath = './Public/Uploads/'; // 设置附件上传目录
		if (! $upload->upload ()) { // 上传错误提示错误信息
			$this->error ( $upload->getErrorMsg () );
		} else { // 上传成功 获取上传文件信息
			$info = $upload->getUploadFileInfo ();
		}
		return $info;
	}
}