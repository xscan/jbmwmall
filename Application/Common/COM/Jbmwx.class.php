<?php
	class Jbmwx{
        
		private $revData; //处理收到的数据
		private $toUsename; //公众号ID
		private $fromUsename; //客户微信OPENID
		private $systype;   //对接系统名称
		private $ctrxy;   //获取要操作的协议
		private $wxType;  //客户微信请求类型
		private $defaultRply; //默认回复
		private $reply; //回复内容
		private $docid; //账单号
		private $db;    //数据库连接
		private $ptname; //微信平台名称
		private $pttelemp; //微信平台服务人员联系方式
        
        
		//构造函数,必要的公众号必要的数据
		public function __construct($fromUsename,$toUsename,$revdata,$wxType){
			$this->systype = C('SYS_TYPE');
			$this->fromUsename = trim($fromUsename);
			$this->toUsename = trim($toUsename);
			$this->revData = trim($revdata);
			$this->wxType = trim($wxType);
			import('Connadodb',APP_PATH . 'Common/COM', '.class.php');
			$this->db = new Connadodb();
		}
		
		/**
		*设置平台名称
		*/
		public function setPtname($ptname){
			$this->ptname=$ptname;
		}
		
		/**
		*设置平台服务人员联系方式
		*/
		public function setPtTelemp($pttelemp){
			$this->pttelemp = $pttelemp;
		}
		/**
		*设置收到的内容
		*/
		public function setRevData($data){
			$this->revData = $data;
		}
		
		/**
		*设置默认回复内容
		*/
		public function setDefaultRply($ptname,$pttelemp){
			$this->ptname=$ptname;
			$this->pttelemp = $pttelemp;
			
			$this->defaultRply = "尊敬的客户你好,欢迎进入" . $this->ptname . "\n" .
                            "1 ZC#手机号# 注册微会员\n" . 
                            "2 CX  查询会员信息\n" .
                            "3 XF 查询消费\n". 
                            "4 ZD 获取账单信息\n" . 
                            $this->ptname . " \n\n客服电话:" . $this->pttelemp;
		}
        
		//获取要操作的协议
		//协议类型:
		//1 ZC#13973343798# ZC+手机号码 注册为微会员用户
		//2 CX 查询会员信息 余额 积分 计次信息等
		//3 XF 查询会员消费信息,最近一个月
		//4 ZD 获取最大账单信息
		public function getCtrxyByText(){
			//if (strcasecmp($this->wxType , 'text') == 0){
			if (strlen($this->revData) >= 2 ){
				$xy = substr($this->revData,0,2);
				$xy = strtoupper($xy);
					$this->ctrxy = $xy;
				}else{
					$this->ctrxy = 'NO';
			}
		}
        
		/**
      *处理接收到的事件
      */
		public function handleEvent($dataEvent){
			switch ($dataEvent['event']){
				case 'subscribe': //关注
					$this->reply = $this->defaultRply;
					break;
                    
				case 'unsubscribe': //取消关注
					$this->reply = "谢谢您的关注,欢迎下次关注" . $this->ptname;
					break;
                    
				case 'SCAN': //扫描
					$this->reply = "系统维护中," . $this->ptname . " \n\n客服电话:" . $this->pttelemp;
					break;
                    
				case 'CLICK': //点击事件
					$key = $dataEvent['key'];
					if (strlen($key) == 2 ){
						$this->revData = $key;
						$this->handleMsg(); //处理协议
					}else{
						//处理连接功能
						$this->reply = "系统维护中," . $this->ptname . " " . $this->pttelemp;
					}
                    
				case 'VIEW': //点跳转菜单
					break;
			}
		}
        
		//根据手机号码获取会员信息
		public function getInfoByinfo($sql){
			$rtnArr = array();
			$this->db->opendb();
			$rtnArr = $this->db->queryall($sql);
			$this->db->closedb();
			return $rtnArr;
		}
        
		public function updateByinfo($table,$arr,$update = ''){
			$this->db->opendb();
			if (strlen($update) > 0){
				$rtn = $this->db->updatearr($table,$arr,$update);
			}else{
				$this->db->insertarr($table,$arr);
			}
			$this->db->closedb();
			return $rtn;
		}
        
      /**
		*处理收到的信息做出回应
		*/
		public function handleMsg(){
			$this->getCtrxyByText();
			switch ($this->ctrxy){
				case 'ZC':
					//ZC#13973343798#
					$start = strpos($this->revData,'#');
					$end = strrpos($this->revData,'#');
					if (!$start) $start = 0 ;
					if (!$end) $start = 0 ;
					if (($start > 0) and ($end > 0) and (($end - $start) > 0)){
						$this->tele = substr($this->revData , ($start + 1) ,($end - $start - 1));
						//获取会员编号
						$sql = "select mem_id , mem_name from mem_info where state = 1 and tel_no = '" . $this->tele ."'";
						$arr = array();
						$arr = $this->getInfoByinfo($sql); //$this->db->queryall($sql);
						//判断数据长度
						if (count($arr) <> 1 ){
							$this->reply = '您不是实体会员或者手机号注册了多个会员,请用微网站注册!' . 
								" \n\n客服电话" . $this->pttelemp;
						}else{
							//判断重复注册问题
							$sql = "select id , mem_emp_id from wx_mem_emp where toUserName = '" . 
							$this->fromUsename . "'";
							$tmp = array();
							$tmp = $this->getInfoByinfo($sql);
							$sql = "select id from wx_mem_emp where mem_emp_id ='" . $tmp[0]['mem_emp_id'] . "'";
							$tmpmem = array();
							$tmpmem = $this->getInfoByinfo($sql);
							if (count($tmpmem) > 0 ){
								$memwx = array(
									//'mem_emp_id' => $arr[0]['mem_id'],
									'toUserName' => $this->fromUsename,
									'toNickname' => $arr[0]['mem_name'],
									'fromUserName' => $this->toUsename,
									'fromNickname' => mb_convert_encoding($this->pttelemp,'GBK','UTF-8'),
									'lastdate' => date('Y-m-d H:i:s'),
									'mem' => mb_convert_encoding('重新注册','GBK','UTF-8')
								);
								$update = "id=" . $tmpmem[0]['id'] . " and mem_emp_id = '" . $tmp[0]['mem_id'] . "'";
								$rtn = $this->updateByinfo("wx_mem_emp",$memwx,$update);
							}else{
									$memwx = array(
										'mem_emp_id' => $arr[0]['mem_id'],
										'toUserName' => $this->fromUsename,
										'toNickname' => $arr[0]['mem_name'],
										'fromUserName' => $this->toUsename,
										'fromNickname' => mb_convert_encoding($this->pttelemp,'GBK','UTF-8'),
										'regdate' => date('Y-m-d H:i:s'),
										'lastdate' => date('Y-m-d H:i:s'),
										'mem' => mb_convert_encoding('新注册','GBK','UTF-8')
									);
								$rtn = $this->updateByinfo("wx_mem_emp",$memwx);
							}
							if ($rtn){
								$this->reply = '恭喜注册微会员成功!' ."\n\n" ."客服电话:" .$this->pttelemp;
							}else{
								$this->reply = '注册微会员失败,请重新操作!' ."\n\n" ."如需帮助客服电话:" . $this->pttelemp;
							}
						}
					}else{
						$this->reply = "没有手机号或号码错误,请输入号码,\n例:ZC#13988888888#" ."\n\n" ."客服电话:" . $this->pttelemp; 
					}
					break;

				case 'CX':
					$tmp = array();
					//根据微信OPENID获取会员编号
					$sql = "select mem_emp_id from wx_mem_emp where toUserName = '" . $this->fromUsename ."'";
					$tmp = $this->getInfoByinfo($sql,$this->fromUsename);
					if (count($tmp) == 1 ){
						//获取会员余额 积分等信息
						$sql = "select m.mem_id, m.mem_name ,m.card_id , c.level_name ,m.balance,m.integral,i.s_name " . 
							"from mem_info as m " .
							"left join card_level as c " .
							"on m.level_id = c.level_id " .
							"left join  info_storage as i on m.fz = i.s_id " .
							"where m.mem_id = '" . $tmp[0]['mem_emp_id'] . "'";
						$meminfo = array();
						$meminfo = $this->getInfoByinfo($sql); 
						if (count($tmp) == 1 ){
							$this->reply = '您查询的信息:' . "\n\n" .
								'姓名:' . mb_convert_encoding($meminfo[0]['mem_name'],'UTF-8','GBK') . "\n" .
								'卡级:' . mb_convert_encoding($meminfo[0]['level_name'],'UTF-8','GBK') . "\n" .
								'卡号:' . $meminfo[0]['card_id'] . "\n" .
								'余额:' . $meminfo[0]['balance'] . "\n" .
								'积分:'. $meminfo[0]['integral'] . "\n" .
								'分店:' . mb_convert_encoding($meminfo[0]['s_name'],'UTF-8','GBK') . 
								"\n\n" ."客服电话:" . $this->pttelemp ;
                                
								//跟新微信账号状态
                            
						}else{
							$this->reply = '您查询的信息不存在!'."\n\n" ."客服电话:" .$this->pttelemp;
						}
					}else{
                        $this->reply = '您没有注册为微会员,或者注册错误,请尝试重新注册微会员!' . 
								"\n\n" ."客服电话:" . $this->pttelemp;
					}
					break;

				case 'XF':
					//默认查询最近一个月消费现金与卡消费额度,测试开发是总消费
					$tmp = array();
					//根据微信OPENID获取会员编号
					$sql = "select mem_emp_id from wx_mem_emp where toUserName = '" . $this->fromUsename ."'";
					$tmp = $this->getInfoByinfo($sql);//$this->db->queryall($sql);
					if (count($tmp) == 1 ){
						$sql = "select m.mem_id, m.mem_name ,m.card_id , c.level_name ,m.balance,m.integral,i.s_name " . 
							"from mem_info as m " .
							"left join card_level as c " .
							"on m.level_id = c.level_id " .
							"left join  info_storage as i on m.fz = i.s_id " .
							"where m.mem_id = '" . $tmp[0]['mem_emp_id'] . "'";
						$meminfo = array();
						$meminfo = $this->getInfoByinfo($sql);
						$sql = "select sum(cash) as cash , sum(card) as card  
						from doc_m where mem_id = '" . $tmp[0]['mem_emp_id'] . "'";
						$arr = array();
						$arr = $this->getInfoByinfo($sql);
						if (count($arr) == 1 and count($meminfo) == 1 ){
							$this->reply = '您消费的信息:' . "\n\n" .
								'姓名:' . mb_convert_encoding($meminfo[0]['mem_name'],'UTF-8','GBK') . "\n" .
								'卡级:' . mb_convert_encoding($meminfo[0]['level_name'],'UTF-8','GBK') . "\n" .
								'卡号:' . $meminfo[0]['card_id'] . "\n" .
								'余额:' . $meminfo[0]['balance'] . "\n" .
								'积分:'. $meminfo[0]['integral'] . "\n" .
								'分店:' . mb_convert_encoding($meminfo[0]['s_name'],'UTF-8','GBK') . "\n" .
								'现金:' . $arr[0]['cash'] ."\n" .
								'卡扣:' . $arr[0]['card'] ;
						}else{
							$this->reply = '没有您的消费记录' . "\n\n" ."客服电话:" . $this->pttelemp; 
						}
					}else{
						$this->reply = '您没有注册为微会员,或者注册错误,请尝试重新注册微会员!' . 
							"\n\n" ."客服电话:" . $this->pttelemp;
					}
					break;
                   
				case 'ZD':
					//根据会员编号获取最大账单号
					$tmp = array();
					//根据微信OPENID获取会员编号
					$sql = "select mem_emp_id from wx_mem_emp where toUserName = '" . $this->fromUsename ."'";
					$tmp = $this->getInfoByinfo($sql);
					if (count($tmp) == 1 ){
						//获取最近账单号
						$sql = "select max(doc_id) as doc_id from doc_m where mem_id = '" .$tmp[0]['mem_emp_id'] . "' and state > 1";
						$docid = array();
						$docid = $this->getInfoByinfo($sql);
						if (count($docid) == 1){
							//获取会员信息
							$sql = "select m.mem_id, m.mem_name ,m.card_id , c.level_name ,m.balance,m.integral,i.s_name " . 
								"from mem_info as m " .
								"left join card_level as c " .
								"on m.level_id = c.level_id " .
								"left join  info_storage as i on m.fz = i.s_id " .
								"where mem_id = '" . $tmp[0]['mem_emp_id'] . "'";
							$meminfo = array();
							$meminfo = $this->getInfoByinfo($sql);
							//查询项目消费信息
							$sql = "select item.item_name,doc.money " .
							"from doc_item as doc join info_item as item on doc.item_id = item.item_id " .
							"where doc.doc_id = '" . $docid[0]['doc_id'] . "'";
							$docitem = array();
							$docitem = $this->getInfoByinfo($sql);
							if (count($docitem) < 1) {
								$itemmsg = "";
							}else{
								$itemmsg = "项目消费:" . "\n";
							}
							for ($i = 0 ; $i <= count(docitem) ; $i++){
								$itemmsg = $itemmsg ."  名称:" . mb_convert_encoding(trim($docitem[$i]['item_name']),'UTF-8','GBK') . "\n";
								$itemmsg = $itemmsg ."  金额:" . $docitem[$i]['money'] . "\n";
							}
							//查询产品消费明细
							$sql = "select ware.ware_name,doc.money " .
								"from doc_ware as doc join info_ware as ware on doc.ware_id = ware.ware_id " .
								"where doc.doc_id = '" . $docid[0]['doc_id'] . "'";
							$docware = array();
							$docware = $this->getInfoByinfo($sql);
							if (count($docware) < 1) {
								$waremsg = "";
							}else{
								$waremsg = "产品消费:" . "\n";
							}
							for ($i = 0 ; $i <= count($docware) ; $i++){
								$waremsg = $waremsg ."  名称:" . mb_convert_encoding(trim($docware[$i]['ware_name']),'UTF-8','GBK') . "\n";
								$waremsg = $waremsg ."  金额:" . $docware[$i]['money'] . "\n";
							}
							//查询计次充值信息
							$sql = "select item.item_name,doc.money " .
								"from doc_mem_item as doc join info_item as item on doc.item_id = item.item_id " .
								"where doc.doc_id = '" . $docid[0]['doc_id'] . "'";
							$docmem = array();
							$docmem = $this->getInfoByinfo($sql);
							if (count($docmem) < 1) {
								$docmemmsg = "";
							}else{
								$docmemmsg = "计次充值:" . "\n";
							}
							for ($i = 0 ; $i <= count($docmem) ; $i++){
								$docmemmsg = $docmemmsg ."  名称:" . mb_convert_encoding(trim($docmem[$i]['item_name']),'UTF-8','GBK') . "\n";
								$docmemmsg = $docmemmsg ."  金额:" . $docmem[$i]['money'] . "\n";
							}
                            
							$this->reply = '您的账单信息:' . "\n" .
								'姓名:' . mb_convert_encoding(trim($meminfo[0]['mem_name']),'UTF-8','GBK') . //"\n" .
								'卡级:' . mb_convert_encoding(trim($meminfo[0]['level_name']),'UTF-8','GBK') . "\n" .
								'卡号:' . $meminfo[0]['card_id'] . "\n" .
								'余额:' . $meminfo[0]['balance'] . "\n" .
								'积分:'. $meminfo[0]['integral'] . "\n" .
								'单号:'. $docid[0]['doc_id'] . "\n" .
								$itemmsg . //"\n" . 
								$waremsg . //"\n" .
								$docmemmsg ."客服电话:\n" . $this->pttelemp;
                                
						}else{
							$this->reply =  "系统没有您的消费记录!" ."客服电话:" . $this->pttelemp;
						}
					}else{
						$this->reply =  $this->defaultRply ;
					}
					break;
                    
				case 'XX':
					//消费明细单
					$title="我的消费明细";
					$Description="查看消费明细";
					//$PicUrl="http://103.6.223.212/jlwx/Public/image/jlcard.jpg";
					//会员卡图片目录
					$PicUrl = 'http://' . $_SERVER ['HTTP_HOST'] . __ROOT__ . "/Public/images/jbmwxcard.jpg";
					//$Url="http://103.6.223.212/jlwx/index.php/Weiweb?openid=" . $this->fromUsename;
					$Url = 'http://' . $_SERVER ['HTTP_HOST'] . __APP__ . "/Weiweb?openid=" . $this->fromUsename;
					$content = array();	
					$content[] = array(
						"Title"=>$title,  
						"Description"=>$Description, 
						"PicUrl"=>$PicUrl, 
						"Url" =>$Url);
					$this->reply = $content; 
					break;
					
				default :
					$this->reply =  $this->defaultRply ;
					break;
			}
		}
        
		//获取事件回复处理
		public function getEventRply($data){
			$this->revData = $data;
			$this->handleEvent($data);
			return $this->reply; 
		}
        
		//根据协议获取回复客户信息
		public function getRply(){
			$this->handleMsg();
			return $this->reply;
		}
        
		//获取默认回复信息
		public function getDefaultRply(){
			return $this->defaultRply;
		}
        
    }
?>