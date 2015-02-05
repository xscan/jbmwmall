<?php
class MessmassageAction extends PublicAction {
  /**
   * 当月发送条数
   */
	public function index() {
		$_SESSION ["wxname"]=M ( "Wxconfig" )->field ('num')->select();
		$msglen=M( 'sendmsglog' )->field( 'count(*) len' )->where( "send_time like'" .date( 'Y-m', time() ). "%'" )->select();
		$this->assign( 'msglen', $msglen[0]['len'] );
		$this->display();
	}
	
	/**
	*群发信息
	*/
	public function massSendMsg() {
		import('jbmWechatext', APP_PATH . 'Common', '.class.php');
		import('ErrCode', APP_PATH . 'Common', '.class.php');
		//导入微信类
		$wechat = new jbmWechatext();
		$result = $wechat -> sendMass(I('masssendmsg'));
		$arr = json_decode($result);
		if ($arr -> base_resp -> ret == '0') {
			$userlist = $wechat -> getAllUser();
			$sendlog = M('sendmsglog');
			//写入数据库
			$i = 0;
			foreach ($userlist as $value) {//循环写入数据库
				$data['content'] = I('masssendmsg');
				$data['type'] = 'TEXT';
				$data['FakeId'] = $value['FakeId'];
				$data['send_time'] = date("Y/m/d h:i:s");
				$data['send_name'] = 'system';
				//$_SESSION ["username"];
				$data['rev_name'] = $value['nick_name'];
				$data['tel'] = '';
				$data['mem_id'] = '';
				$data['msg_type'] = '0';
				//0 微信 1 短信
				$data['send_start'] = '0';
				//0 发送成功，1发送失败
				$data['wx_name'] = $_SESSION["wxname"][0]['num'];
				$sendlog -> add($data);
			}
			$flag['statusCode'] = "200";
			$flag['message'] = "发送成功!";
		} else {
			$flag['statusCode'] = "300";
			$flag['message'] = "群发失败,错误信息:" . ErrCode::getErrText($arr -> base_resp -> ret);
		}
		$this -> ajaxReturn($flag);

	}
	
	/**
	*选人发信息
	*/
	public function SendMsg() 
    {
    import ( 'jbmWechatext', APP_PATH . 'Common', '.class.php' );//导入微信类
    $wechat=new jbmWechatext();
    //根据FekId循环获取发送者名称
    //I( 'id' )客户端传来的要发送信息的FekId
	$id=I( 'id' );
    foreach ( $id as $value ) {
      $wechat->SendMsg( $value, I( 'msg' ) );//单发信息
    }
    //循环写入数据库
    $i=0;
    $sendlog=M( 'sendmsglog' );//写入数据库
    foreach ( I( 'name' ) as $value ) {
      $data['content']=I( 'msg' );
      $data['type']='TEXT';
      $data['FakeId']= $id[$i++];
      $data['send_time']=date( "Y/m/d h:i:s" );
      $data['send_name']='system';//$_SESSION ["username"];
      $data['rev_name']=$value;
      $data['tel']='';
      $data['mem_id']='';
      $data['msg_type']='0';//0 微信 1 短信
      $data['send_start']='0';//0 发送成功，1发送失败
      $data['wx_name']= $_SESSION ["wxname"][0]['num'];
      $sendlog->add( $data );
    }
		$flag['statusCode']="200";
		$flag['message']="发送成功";
    $this->ajaxReturn( $flag );
  }
  
	/**
	*
	*/
	public function newssendusermsg(){
		   //获取用户信息
		import ( 'jbmWechatext', APP_PATH . 'Common', '.class.php' );//导入微信类
		$wechat=new jbmWechatext();
		$this->assign( 'groupdata', $wechat->getAllGroup() );//获取分组列表
	 $this->display();
	}
	
	/**
	*获取分组用户返回给客户端
	*/
	public function getGroupUser() {
		import ( 'jbmWechatext', APP_PATH . 'Common', '.class.php' );//导入微信类
		$wechat=new jbmWechatext();
		$arr=array();
		$arr=$wechat->getGroupUserList( I( 'id' ) );//获取分组列表用户
		//循环组装数据
		$i=0;
		foreach ( $arr as $key => $value ) {
		  $str.= " <a href='#' class='list-group-item'";
		  $str.="data-value='".$arr[$i]['FakeId']. "'";
			$str.="data-name='".$arr[$i]['nick_name']."'";
			$str.=" data-toggle='tooltip' data-placement='right' title='点击加入发送列表' >".$arr[$i]['nick_name']."</a>";
		  $i++;
		}
		//定义返回数据
		$flag['statusCode']="200";

		$flag['message']= $str;
		$this->ajaxReturn( $flag );
	}
	
	/**
	*
	*/
	public function sendmsglog() {

		/*  $info=M('sendmsglog');//实例化数据表
		import('ORG.Util.Page');// 导入分页类
	 //   $this->data = $info->select();查询数据集
	  $count = $info->count();//计算总记录数
	  $Page = new Page($count);// 实例化分页类 传入总记录数
		   $Page->setConfig ('theme',"%upPage% %downPage%");//设置分页格式
	  $show= $Page->show();// 分页显示输出
		//数据排序
		$list = $info->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('sendmsglog',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display();
	*/
		$sendlog=M( 'sendmsglog' )->order( 'id desc' )->select();//查询发送记录
		$this->assign( 'sendmsglog', $sendlog );
		$this->assign('date',date( 'Y-m-d', time() ));
		$this->display();
	}
	
	/**
	*
	*/
	public function loopsendmsglog() {
		$loopdata=M( 'sendmsglog' )
		->where( "send_time BETWEEN '".I( 'startdate' )."' AND '".I( 'enddate' )."'" )
		->order('send_time desc')
		->select();//查询发送记录

    //$loopdata=M( 'sendmsglog' )->select();   //  var_dump($data);
		if ( $loopdata==false ) {
		  $str="没有数据";
		}
		else {
		  $str='';
		  for ( $i=0, $j=1; $i <count( $loopdata ) ; $i++ ) {
			$str.="<tr>";
			$str.="<td>".+$j."</td>";
			$str.="<td>".$loopdata[$i]['content']."</td>";
			$str.="<td>".$loopdata[$i]['type']."</td>";
			$str.="<td>".$loopdata[$i]['send_name']."</td>";
			$str.=" <td>".$loopdata[$i]['rev_name']."</td>";
			if ( $loopdata[$i]['msg_type']==0 ) {
			  $str.=" <td>微信</td>";
			}
			else {
			  $str.=" <td>短信</td>";
			}
			if ( $loopdata[$i]['send_start']==0 ) {
			  $str.=" <td>已发送</td>";
			}
			else {
			  $str.=" <td>未发送</td>";
			}
			$str.=" <td>".$loopdata[$i]['wx_name']."</td>";
			$str.="  <td>".$loopdata[$i]['send_time']."</td> </tr>";
			$str.="</tr>";
			$j++;
		  }
		}
	$this->ajaxReturn( trim( $str ) );
  }

}
?>
