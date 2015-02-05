<?php
/**
 * 群发信息类
 */
class JbmWechatext {
	private $_Wxobj; //实例化微信接口对象
	public function __construct() {
		
		import ( 'Wechatext', APP_PATH . 'Common/Wechat', '.class.php' );
		$config=M('wxconfig')->field("ptaccount,ptpassword")->select();
	      $options = array(
			'account'=>$config[0]['ptaccount'],
			'password'=>$config[0]['ptpassword'],
			'datapath'=>C( 'datapath' ),
			'debug'=>C( 'debug' ),
			'logcallback'=>C( 'logcallback' )
		);

		$this->_Wxobj=new Wechatext( $options );
	}
	/**
	 * 获取所有用户信息
	 * return array 用户id 名称
	 */
	public function getAllUser() {
		$userdata=array();//定义一个数组用于装用户信息
		if ( $this->_Wxobj->checkValid() ) {
			$userlist = $this->_Wxobj->getUserlist( 0, 1000, -1 );
			//获取分组列表用户信息-1表示全部用户，获取条数为1000跳
			$i=0;
			foreach ( $userlist as $key=>$value ) {//循环获取用户id和名称
				$userdata[]=array(
					'FakeId'=>$userlist[$i]['id'],
					'nick_name'=> $userlist[$i]['nick_name']
				);
				$i++;
			}
		}
		else {
			$userdata[0]= '获取数据失败';
		}
		return $userdata;
	}
	/**
	 * 获取分组用户信息
	 * $groupid 分组id
	 *return array   分组里的成员  
	 */
	public function getGroupUserList($groupid) {
		$userdata=array();//定义一个数组用于装用户信息
		if ( $this->_Wxobj->checkValid() ) {
			$userlist = $this->_Wxobj->getUserlist( 0, 1000, $groupid );
			//获取分组列表用户信息-1表示全部用户，获取条数为1000跳
			$i=0;
			foreach ( $userlist as $key=>$value ) {//循环获取用户id和名称
				$userdata[]=array(
					'FakeId'=>$userlist[$i]['id'],
					'nick_name'=> $userlist[$i]['nick_name']
				);
				$i++;
			}
		}
		else {
			$userdata["nick_name"]= '获取数据失败';
		}
		return $userdata;
	}
	/**
	 * 获取公众号所有分组
	 */
		public function getAllGroup( ) {
		if ( $this->_Wxobj->checkValid() ) {
			return  $this->_Wxobj->getGroupList( 0,1000 );
		}
	}
	/**
	 * 获取用户信息 
	 * $id 用户id
	 */
	public function getUserInfo($id) {
		if ( $this->_Wxobj->checkValid() ) {
			return  $this->_Wxobj->getInfo($id);
		}
	}

	/**
	 * $id  用户id
	 * $msg   发送的信息
	 */
	public function sendMsg( $id, $msg ) {
		if ( $this->_Wxobj->checkValid() ) {
			$this->_Wxobj->send( $id, $msg );
		}
	}
	/**
	 * 公众平台群发
	 */
	public function sendMass( $msg ) {
		if ( $this->_Wxobj->checkValid() ) {
			return $this->_Wxobj->mass( $msg );
		}
	}
	/**
	 * 循环单发
	 */
	public function sendMassMsg( $msg ) {
		if ( $this->_Wxobj->checkValid() ) {
			$userlist=array();
			$userlist= $this->_Wxobj->getUserlist( 0, 1000, -1 );//获取分组列表用户信息
			$i=0;
			foreach ( $userlist as $key=>$value ) {
				$this->_Wxobj->send( $userlist[$i]['id'], $msg );
				$i++;
			}
		}
	}

}
?>
