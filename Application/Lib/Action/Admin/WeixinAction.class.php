<?php
    /**
     *微信设置 
     */
    class WeixinAction extends PublicAction {
        /**
         *配置显示
         */
    	public function index() {
    		$config = M( "Wxconfig" )->find();
    		$this->assign( "config", $config );
			//echo 'http://' . $_SERVER ['HTTP_HOST'] . __APP__;
    		$this->assign( "url", 'http://' . $_SERVER ['HTTP_HOST'] . __APP__ . '/Admin/Wechat/index' );
    		
    		$menu = M( "Wxmenu" )->select ();
    		$this->assign( "menu", $menu );
    		$this->assign ( "menu1", $menu );
			
    		$message = M ( "Wxmessage" )->select();
    		$this->assign( "message", $message );
            
            //上级菜单
           $sjmenu = M ( "Wxmenu" )->where( "pid = 0" )->select ();
           $this->assign("sjmenu", $sjmenu );
            
           //外部数据源
           $extdb = M ( "extdb" )->find();
           $this->assign( "extdb", $extdb);
            
    		$this->display ();
    	}
        
        /**
         *保存微信配置 
         */ 
    	public function setconfig() {
            //p($_post);
            //echo '1';
    		$result = M("Wxconfig" )->where ( array (
    				"id" => "1" 
    		) )->save ( $_POST );
            
            if($result){
    			$flag = array(
    				"statusCode"=>"200",
    				"message"=>"保存微信配置成功!",
    				"tabid"=>"baseindex",
    				"dialogid"=>"dialog-mask",
    				"closeCurrent"=>false,
    				"forward"=>"",
    				"forwardConfirm"=>""
    			);
    			
    		}else{
    			$flag = array(
    				"statusCode"=>"300",
    				"message"=>"保存微信配置失败,或没有修改数据!!!",
    				"tabid"=>"baseindex",
    				"dialogid"=>"dialog-mask",
    				"closeCurrent"=>false,
    				"forward"=>"",
    				"forwardConfirm"=>""
    			);
    		}
            $this->ajaxReturn( $flag );
    		//$this->success ( "配置成功!" );
    	}
        
        /**
         *增加修改菜单
         */
    	public function addmenu() {
		if ($_POST ["menu_id"]) {
			if($_POST ["pid"] == 0)
				$_POST ["status"] = '0';
                $result = M ( "Wxmenu" )->save ( $_POST );
                if ($result) {
    				$flag = array(
    					"statusCode"=>"200",
    					"message"=>"添加一级菜单成功!",
    					"tabid"=>"baseindex",
    					"dialogid"=>"dialog-mask",
    					"closeCurrent"=>false,
    					"forward"=>"",
    					"forwardConfirm"=>""
    				);
				
                }else{
    				$flag = array(
    					"statusCode"=>"300",
    					"message"=>"添加一级菜单失败!!!",
    					"tabid"=>"baseindex",
    					"dialogid"=>"dialog-mask",
    					"closeCurrent"=>false,
    					"forward"=>"",
    					"forwardConfirm"=>""
    				);
                }
            } else {
    			if($_POST ["pid"] == 0)
                    $_POST ["status"] = '0';
                    unset ( $_POST ["menu_id"] );
    			
        			$result = M ( "Wxmenu" )->add ( $_POST );
        			if($result){
        				$flag = array(
        					"statusCode"=>"200",
        					"message"=>"添加二级菜单成功!",
        					"tabid"=>"",
        					"dialogid"=>"dialog-mask",
        					"closeCurrent"=>false,
        					"forward"=>"",
        					"forwardConfirm"=>""
        				);
    			}else{
    				$flag = array(
    					"statusCode"=>"300",
    					"message"=>"添加二级菜单失败!!!",
    					"tabid"=>"",
    					"dialogid"=>"dialog-mask",
    					"closeCurrent"=>false,
    					"forward"=>"",
    					"forwardConfirm"=>""
    				);
    			}
			
			/*if ($result) {
				$this->success ( "添加菜单成功!" );
			}*/
            }
            $this->ajaxReturn( $flag );
	   }
       
       /**
        *修改菜单 
        */
    	public function UpdateMenu() {
    		if ($_POST ["menu_id"]) {
    			if($_POST ["pid"] == 0){
    				$_POST ["status"] = '0';
    			}
    			$result = M ( "Wxmenu" )->save ( $_POST );
    			if($result){
    				$flag = array(
    					"statusCode"=>"200",
    					"message"=>"修改菜单成功!",
    					"tabid"=>"",
    					"dialogid"=>"dialog-mask",
    					"closeCurrent"=>false,
    					"forward"=>"",
    					"forwardConfirm"=>""
    				);
    				
    			}else{
    				$flag = array(
    					"statusCode"=>"300",
    					"message"=>"修改菜单失败,或没有修改数据!!!",
    					"tabid"=>"",
    					"dialogid"=>"dialog-mask",
    					"closeCurrent"=>false,
    					"forward"=>"",
    					"forwardConfirm"=>""
    				);
    			}
    			$this->ajaxReturn( $flag );
    			/*if ($result) {
    				$this->success ( "修改菜单成功!" );
    			}*/
    		}else{
    			$id = $_GET ["id"];
    			$UMenu = M ( "Wxmenu" )->where( "menu_id = ".$id )->select ();
    			$this->assign ( "UMenu", $UMenu[0] );
    			$sjmenu = M ( "Wxmenu" )->where( "pid = 0" )->select ();
    			$this->assign ( "sjmenu", $sjmenu );
    			$this->display();
    		}
    	}
        
        
        /**
         *删除菜单 
         */ 
    	public function delmenu() {
    		$id = $_GET ["id"];
    		$f =M( "Wxmenu" )->where (" pid = 1 AND status=".$id )->select();
    		if($f){
    			$flag = array(
    				"statusCode"=>"300",
    				"message"=>"请先删除该菜单的子菜单",
    				"tabid"=>"",
    				"dialogid"=>"dialog-mask",
    				"closeCurrent"=>false,
    				"forward"=>"",
    				"forwardConfirm"=>""
    			);
    		}else{
    			$result = M ( "Wxmenu" )->where ( array ( "menu_id" => $id ) )->delete ();
    			if($result){
    				$flag = array(
    					"statusCode"=>"200",
    					"message"=>"删除菜单成功!",
    					"tabid"=>"",
    					"dialogid"=>"dialog-mask",
    					"closeCurrent"=>false,
    					"forward"=>"",
    					"forwardConfirm"=>""
    				);
    				
    			}else{
    				$flag = array(
    					"statusCode"=>"300",
    					"message"=>"删除菜单失败!!!",
    					"tabid"=>"",
    					"dialogid"=>"dialog-mask",
    					"closeCurrent"=>false,
    					"forward"=>"",
    					"forwardConfirm"=>""
    				);
    			}
    		}
    		$this->ajaxReturn( $flag );
    		/*if ($result) {
    			$this->success ( "删除菜单成功!" );
    		}*/
    	}
        
        /**
         *增加自定义回复
         */
    	public function addmessage() {
    	   $id = $_GET['id'];
            $data = $_POST;
            if (!empty($data) and empty($_GET['id'])) { //增加
                if ($_FILES["fileselect"]["error"][0] <= 0) {
            		if ($_FILES ['picurl'] ['name'] !== '') {
            			$img = $this->upload ();
            			$picurl = $img [0] ['savename'];
            			$data ["picurl"] = $picurl;
            		}
                }
        		if ($_POST ["message_id"] == "") {
        			unset ( $data ["message_id"] );
        			$result = M ( "Wxmessage" )->add ( $data );
        		} else {
  		            //p($_POST);
					//die;
					$wxmessage = M("wxmessage")->where(array("id"=>$data ["message_id"]))->select();
					$this->assign("wxmessage",$wxmessage[0]);
        			$data ["id"] = $data ["message_id"];
        			unset ( $data ["message_id"] );
        			$result = M ( "Wxmessage" )->save ( $data );
        		}
    		
        		if($result){
                    $flag = array(
                        "statusCode"=>"200",
                        "message"=>"增加自定义回复成功!",
                        "tabid"=>"",
                        "dialogid"=>"dialog-mask",
                        "closeCurrent"=>true,
                        "forward"=>"",
                        "forwardConfirm"=>""
                    );
        				
                }else{
                    $flag = array(
                        "statusCode"=>"300",
                        "message"=>"增加自定义回复失败!!!",
                        "tabid"=>"baseindex",
                        "dialogid"=>"addmessage",
                        "closeCurrent"=>false,
                        "forward"=>"",
                        "forwardConfirm"=>""
                    );
       			}
                $this->ajaxReturn( $flag );
                //alert("success","",U( 'Admin/Weixin/index' ));
            }else{ //编辑
                $wxmessage = M("Wxmessage")->where(array("id"=>$id))->select();
                //p($wxmessage[0]);
                $this->assign("wxmessage",$wxmessage[0]);
                unset($_GET);
                $this->display();
            }
    	}
        
        /**
         *删除自定义回复
         */
    	public function delmessage(){
    		$result = M("Wxmessage")->where(array("id"=>$_GET["id"]))->delete();
    		if($result){
                $flag = array(
                    "statusCode"=>"200",
                    "message"=>"删除自定义回复成功!",
                    "tabid"=>"",
                    "dialogid"=>"dialog-mask",
                    "closeCurrent"=>false,
                    "forward"=>"",
                    "forwardConfirm"=>""
                );
    				
            }else{
                $flag = array(
                    "statusCode"=>"300",
                    "message"=>"删除自定义回复失败!!!",
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
         *设置外部数据源 
         */
        public function setext(){
            $data = $_POST;
            if ($data['extflag'] === '1'){
                unset($data['extflag']);
                $result = M("extdb" )->where( array(
        				"id" => "1" 
        		))->save($data);
                if($result){
        			$flag = array(
        				"statusCode"=>"200",
        				"message"=>"保存外部数据源成功!",
        				"tabid"=>"baseindex",
        				"dialogid"=>"dialog-mask",
        				"closeCurrent"=>false,
        				"forward"=>"",
        				"forwardConfirm"=>""
        			);
        			
        		}else{
        			$flag = array(
        				"statusCode"=>"300",
        				"message"=>"保存外部数据源,或没有修改数据!!!",
        				"tabid"=>"baseindex",
        				"dialogid"=>"dialog-mask",
        				"closeCurrent"=>false,
        				"forward"=>"",
        				"forwardConfirm"=>""
        			);
        		}
            }else{
               //测试连接数据源成功
               import( 'Connadodb', APP_PATH . 'Common/COM', '.class.php' );
               //C('JBM_DB_TYPE',$data['ext_type']);
               //C('JBM_DB',$data['ext_dbname']);
               //C('SERVER_IP',$data['ext_ip']);
               //C('UID',$data['ext_uid']);
               //C('PWD',$data['ext_pwd']);
               //p($data);
               //die;
               $db = new Connadodb($data['ext_dbname'], $data['ext_type'], $data['ext_ip'],$data['ext_uid'],$data['ext_pwd'],false);
               //p($data);
               //die;
               $db->opendb();
               $result = $db->getDbState();
               //echo $result;
               //die;
               if($result){
        			$flag = array(
        				"statusCode"=>"200",
        				"message"=>"测试连接数据源成功!",
        				"tabid"=>"",
        				"dialogid"=>"dialog-mask",
        				"closeCurrent"=>false,
        				"forward"=>"",
        				"forwardConfirm"=>""
        			);
        			
        		}else{
        			$flag = array(
        				"statusCode"=>"300",
        				"message"=>"测试连接数据源失败!!!" . $result,
        				"tabid"=>"",
        				"dialogid"=>"dialog-mask",
        				"closeCurrent"=>false,
        				"forward"=>"",
        				"forwardConfirm"=>""
        			);
        		}
				//$this->ajaxReturn( $flag );
            }
            $this->ajaxReturn( $flag );
        }
    }
?>
