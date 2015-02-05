<?php
class WechatAction extends Action {
	private $jbmwx; //金博美微信组件
	private $ToUserName; //开发者微信号(公众号)
	private $FromUserName; //微信客户帐号（一个OpenID）
	private $revData; //公众平台接收到的信息
	private $handleData; //回复给客户信息
	private $ptName;	//平台名称
	private $ptTelemp; //平台联系电话联系人
	
	
    /**
     *初始化函数获取微信配置
     */
	public function init() {
        //import('类库名', '起始路径', '类库后缀')
		import ( 'Wechat', APP_PATH . 'Common/Wechat', '.class.php' );
		$config = M ( "wxconfig" )->where ( array (
				"id" => "1" 
		) )->find ();
		
		$options = array (
				'token' => $config ["token"], // 填写你设定的key
				'encodingaeskey' => $config ["encodingaeskey"], // 填写加密用的EncodingAESKey
				'appid' => $config ["appid"], // 填写高级调用功能的app id
				'appsecret' => $config ["appsecret"], // 填写高级调用功能的密钥
				'partnerid' => $config ["partnerid"], // 财付通商户身份标识
				'partnerkey' => $config ["partnerkey"], // 财付通商户权限密钥Key
				'paysignkey' => $config ["paysignkey"]  // 商户签名密钥Key
				);
		$this->ptName = $config ["num"];
		$this->ptTelemp = $config ["pttelemp"];
		$weObj = new Wechat( $options );
		return $weObj;
	}
    
    /**
     *微信入口
     */
	public function index() {
		$weObj = $this->init ();
		$weObj->valid ();
		//获取消息类型
		$type = $weObj->getRev ()->getRevType ();
		//获取公众微信号
       $this->ToUserName = $weObj->getRev()->getRevTo();
	   //获取用户微信号
		$this->FromUserName = $weObj->getRev()->getRevFrom();
		//获取关键字/或者客户发送过来的内容
		$eventype = $weObj->getRev()->getRevContent();
		import ( 'Jbmwx', APP_PATH . 'Common/COM', '.class.php' ); 
		$this->jbmwx = new Jbmwx($this->FromUserName , $this->ToUserName , $eventype , $type);
		$this->jbmwx->setDefaultRply($this->ptName,$this->ptTelemp);
		switch ($type) {
			case Wechat::MSGTYPE_TEXT :  //文本回复
				//判断是否金博美自定义的会员专区协议
				if (strlen($eventype) >= 2 ){
					$xy = strtoupper(substr($eventype,0,2));
					if ($xy == 'ZC' or $xy == 'CX' or $xy == 'XF' or $xy == 'ZD'){
						$this->handleData = $this->jbmwx->getRply();
						$weObj->text($this->handleData)->reply();
						exit();
						break;
					}elseif($xy == 'XX' ){ //详情是图文形式
						$this->handleData = $this->jbmwx->getRply();
						if(is_array($this->handleData)){
							//回复图文
							if (isset($this->handleData[0])){
								$weObj->news($this->handleData)->reply();
							}else{
								$weObj->text($this->jbmwx->getDefaultRply())->reply();
							}
						}else{
							$weObj->text($this->handleData)->reply();
						}

						exit();
						break;
					}
				}
				//查询自动回复关键字
				$replay = M("wxmessage")->where(array("key"=>$eventype))->select();
				for ($i = 0; $i < count($replay); $i++) {
					if ($replay[$i]["type"]==0) { //图文回复
						$appUrl = 'http://' . $this->_server ( 'HTTP_HOST' ) . __ROOT__;
						$newsArr[$i] = array(
								'Title' => $replay[$i]["title"],
								'Description' => $replay[$i]["description"],
								'PicUrl' => $appUrl . '/Public/Uploads/'.$replay[$i]["picurl"],
								'Url' => $replay[$i]["url"].'?uid=' . $weObj->getRevFrom ()
						);
					}else{ //文本回复
						$weObj->text( $replay[$i]["description"] )->reply ();
					}
				}
				if (!empty($newsArr)){
					$weObj->getRev()->news( $newsArr )->reply ();
				}
				exit();
				break;
                
			case Wechat::MSGTYPE_EVENT : //事件回复
				$eventype = $weObj->getRev()->getRevEvent ();
				//点击事件
				if ($eventype ['event'] == "CLICK") {
					if (strlen($eventype['key']) == 2 and strtoupper($eventype['key']) == 'XX'){
						$this->jbmwx->handleEvent($eventype);
						$this->handleData = $this->jbmwx->getRply();
						if(is_array($this->handleData)){
							if (isset($this->handleData[0])){
								$weObj->getRev()->news($this->handleData)->reply();
							}else{
								$weObj->text($this->jbmwx->getDefaultRply())->reply();
							}
						}else{
							$weObj->text($this->handleData)->reply();
						}
						exit();
						break;
					}elseif(strlen($eventype['key']) == 2 and (strtoupper($eventype['key']) == 'CX' or strtoupper($eventype['key']) == 'ZC' or strtoupper($eventype['key']) == 'ZD' or strtoupper($eventype['key']) == 'XF')){
						$this->jbmwx->setRevData($eventype['key']);
						$this->handleData = $this->jbmwx->getRply();
						$weObj->text($this->handleData)->reply();
						exit();
						break;

					}else{
						$appUrl = 'http://' . $this->_server ( 'HTTP_HOST' ) . __ROOT__;
						//自动回复新闻
						$news = M ( "wxmessage" )->where ( array (
								"key" => $eventype ['key'],
								"type" => 0 
						) )->select ();
						//默认回复的信息进入商城也是在此
						if ($news) {
							for($i = 0; $i < count( $news ); $i ++) {
								$newsArr[$i] = array(
									'Title' => $news[$i]["title"],
									'Description' => $news[$i]["description"],
									'PicUrl' => $appUrl . '/Public/Uploads/'.$news[$i]["picurl"],
									'Url' => $news[$i]["url"].'?uid=' . $weObj->getRevFrom ()
								);
							}
							$weObj->getRev()->news ( $newsArr )->reply ();
						}
					}
				}elseif ($eventype['event'] == "subscribe") {
    				$weObj->text ( "欢迎您关注" . $this->ptName ."！" )->reply ();
				}
				exit ();
				break;
				
			case Wechat::MSGTYPE_IMAGE: //图片处理
				$this->handleData = $this->jbmwx->getRply();
              $weObj->text($this->handleData)->reply();
				exit();
				break;
				
			case Wechat::MSGTYPE_LOCATION: //位置处理
				$this->handleData = $this->jbmwx->getRply();
				$weObj->text($this->handleData)->reply();
				exit();
				break;
			
			case Wechat::MSGTYPE_LINK: //连接处理
				$this->handleData = $this->jbmwx->getRply();
				$weObj->text($this->handleData)->reply();
				exit();
				break;
				
			case Wechat::MSGTYPE_NEWS:          //新闻
				$this->handleData = $this->jbmwx->getRply();
				$weObj->text($this->handleData)->reply();
				exit();
				break;
			case Wechat::MSGTYPE_VOICE:         //声音
				$this->handleData = $this->jbmwx->getRply();
				$weObj->text($this->handleData)->reply();
				exit();
				break;
				
			case Wechat::MSGTYPE_VIDEO:     //视频
				$this->handleData = $this->jbmwx->getRply();
				$weObj->text($this->handleData)->reply();
				exit();
				break;
				
			default : //回复默认信息
				$this->handleData = $this->jbmwx->getDefaultRply();
				$weObj->text($this->handleData)->reply();
				exit();
				break;
		}
	}
    /**
     *重新生成微信菜单
     */
	public function createMenu() {
	   // 创建菜单
	   
		$menu = M ( "Wxmenu" )->where( "pid = 0" )->order ( "listorder asc" )->select ();
		for($i = 0; $i < count ( $menu ); $i++) {
			$zmenu = M ( "Wxmenu" )->where( "pid = 1 AND status = ".$menu[$i]['menu_id'] )->order ( "listorder asc" )->select ();
			if($zmenu){
				$temp[$i]['name'] = $menu[$i]['menu_name'];
				$temp[$i]['sub_button']=array();
				for($j = 0; $j < count ( $zmenu ); $j++) {
					$temp[$i]['sub_button'][$j]['type'] =  $zmenu[$j]['menu_type'] ;
					$temp[$i]['sub_button'][$j]['name'] =  $zmenu[$j]['menu_name'] ;
					if($zmenu[$j]['menu_type'] == "view"){
						$temp[$i]['sub_button'][$j]['url'] =  $zmenu[$j]["view_url"] ;
					}else{
						$temp[$i]['sub_button'][$j]['key'] =  $zmenu[$j]["event_key"] ;
					}
					
				}
			}else{
				$temp[$i]['type'] = $menu[$i]['menu_type'];
				$temp[$i]['name'] = $menu[$i]['menu_name'];
				if($menu [$i] ["menu_type"] == "view"){
					$temp [$i] ["url"] = $menu [$i] ["view_url"];
				}else{
					$temp [$i] ["key"] = $menu [$i] ["event_key"];
				}
			}
		}
		
        $newmenu ["button"] = $temp;
		
       /*
		$menu = M ( "Wxmenu" )->order ( "listorder asc" )->select ();
		for($i = 0; $i < count ( $menu ); $i ++) {
			if ($menu [$i] ["menu_type"] == "view") {
				$menu [$i] ["type"] = $menu [$i] ["menu_type"];
				$menu [$i] ["name"] = $menu [$i] ["menu_name"];
				$menu [$i] ["url"] = $menu [$i] ["view_url"];
			} else {
				$menu [$i] ["type"] = $menu [$i] ["menu_type"];
				$menu [$i] ["name"] = $menu [$i] ["menu_name"];
				$menu [$i] ["key"] = $menu [$i] ["event_key"];
			}
			unset ( $menu [$i] ["menu_id"] );
			unset ( $menu [$i] ["pid"] );
			unset ( $menu [$i] ["listorder"] );
			unset ( $menu [$i] ["status"] );
			unset ( $menu [$i] ["menu_type"] );
			unset ( $menu [$i] ["menu_name"] );
			unset ( $menu [$i] ["event_key"] );
			unset ( $menu [$i] ["view_url"] );
		}
		$newmenu ["button"] = $menu;
		*/
		$weObj = $this->init ();
		$ret = $weObj->createMenu( $newmenu );
		if ($ret ){
			$flag = array(
    				"statusCode"=>"200",
    				"message"=>"生成微信微菜单成功!",
    				"tabid"=>"baseindex",
    				"dialogid"=>"dialog-mask",
    				"closeCurrent"=>false,
    				"forward"=>"",
    				"forwardConfirm"=>""
    			);
		}else{
			$flag = array(
    				"statusCode"=>"300",
    				"message"=>"生成微信菜单失败!!!",
    				"tabid"=>"baseindex",
    				"dialogid"=>"dialog-mask",
    				"closeCurrent"=>false,
    				"forward"=>"",
    				"forwardConfirm"=>""
    			);
		}
		$this->ajaxReturn($flag);
		//$this->success ( "重新创建菜单成功!" );
	}
}