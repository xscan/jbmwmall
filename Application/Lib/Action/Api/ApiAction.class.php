<?php
    class ApiAction extends Action {
        /**
         *登录
         */
    	public function login($username, $password, $time, $loginip) {
			$where ["username"] = $username;
			$where ["password"] = md5 ( $password );
			$result = M( "user" )->where ( $where )->find ();
			$where ['id'] = $result['id'];
			$where ['time'] = $time;
			$where ['loginip'] = $loginip;
			
			M('user')->save($where);
			if ($result['status']) {
				return $result;
			}
		}
        
        /**
         *获取设置信息
         */
    	public function getsetting() {
    		$result = M ( "Info" )->find ();
    		if ($result) {
    			return $result;
    		}
    	}
        
        /**
         *保存设置信息
         */
    	public function setting($name, $notification) {
    		$data ["id"] = 1;
    		$data ["name"] = $name;
    		$data ["notification"] = $notification;
    		$result = M ( "Info" )->save ( $data );
    		if ($result) {
    			return $result;
    		}
    	}
        
        /**
         *获取支付宝信息
         */
    	public function getalipay() {
    		$result = M ( "Alipay" )->find ();
    		if ($result) {
    			return $result;
    		}
    	}
        
        /**
         *保存支付宝信息
         */
    	public function setalipay($alipayname, $partner, $key) {
    		$select = M("Alipay")->select();
    		if ($select) {
    			$data ["id"] = 1;
    			$data ["alipayname"] = $alipayname;
    			$data ["partner"] = $partner;
    			$data ["key"] = $key;
    			
    			$result = M ( "Alipay" )->save ( $data );
    		}else{
    			$data ["alipayname"] = $alipayname;
    			$data ["partner"] = $partner;
    			$data ["key"] = $key;
    			
    			$result = M ( "Alipay" )->add ( $data );
    		}
    		
    		if ($result) {
    			return $result;
    		}
    	}
        
        /**
         *获取商品菜单树
         */
    	public function getarraymenu() {
    		$result = M ( "Menu" )->select ();
    		
    		import ( 'Tree', APP_PATH . 'Common', '.php' );
    		$tree = new Tree (); // new 之前请记得包含tree文件!
    		$tree->tree ( $result ); // 数据格式请参考 tree方法上面的注释!
    		                         
    		// 如果使用数组, 请使用 getArray方法
    		$result = $tree->getArray ();
    		// 下拉菜单选项使用 get_tree方法
    		// $tree->get_tree();
    		if ($result) {
    			return $result;
    		}
    	}
        
        /**
         *获取商品菜单
         */
    	public function getmenu() {
    		$result = M ( "Menu" )->select ();
    		if ($result) {
    			return $result;
    		}
    	}
        
        /**
         *增加商品菜单
         */
    	public function addmenu($parent, $name, $addmenu) {
    		if ($addmenu == "") {
    			$data ["name"] = $name;
    			$data ["pid"] = $parent;
    			$result = M ( "Menu" )->add ( $data );
    			if ($result) {
    				return $result;
    			}
    		} else {
    			$data ["id"] = $addmenu;
    			$data ["name"] = str_replace ( "│ ", "", $name );
    			$data ["pid"] = $parent;
    			$result = M ( "Menu" )->save ( $data );
    			if ($result) {
    				return $result;
    			}
    		}
    	}
        
        /**
         *删除商品菜单
         */
    	public function delmenu($id) {
    		$result = M ( "Menu" )->where ( array (
    				'id' => $id 
    		) )->delete ();
    		if ($result) {
    			return $result;
    		}
    	}
        
        /**
         *根据商品菜单编号获取菜单名
         */
    	public function getmenuvalue($menu_id) {
    		$result = M ( "Menu" )->where ( array (
    				"id" => $menu_id 
    		) )->find ();
    		if ($result) {
    			return $result ["name"];
    		}
    	}
        
        /**
         *获取商品信息
         */
    	public function getgood() {
    		$result = M ( "Good" )->select ();
    		if ($result) {
    			return $result;
    		}
    	}
        
        /**
         *添加商品信息
         */
    	public function addgood($data) {
    		if ($data["id"]) {
    			$result = M ( "Good" )->save($data);
    		}else{
    			$result = M ( "Good" )->add($data);
    		}
    		
    		if ($result) {
    			return $result;
    		}
    	}
        
        /**
         *删除商品信息
         */
    	public function delgood($id) {
    		$result = M ( "Good" )->where ( array (
    				"id" => $id 
    		) )->delete ();
    		if ($result) {
    			return $result;
    		}
    	}
        
        /**
         * 
         */
    	public function getorder() {
    	}
        
        /**
         *获取模板设置信息 
         */
    	public function gettheme() {
    		$m = M ( "Info" );
    		$result = $m->find ();
    		if ($result) {
    			return $result;
    		}
    	}
        
        /**
         *删除订单 
         */
    	public function delorder($id) {
    		$reuslt = M ( "Order" )->where ( array (
    				"id" => $id 
    		) )->delete ();
    		if ($reuslt) {
    			return $reuslt;
    		}
    	}
        
        /**
         * 
         */
    	public function publish($id) {
    		$data ["id"] = $id;
    		$data ["order_status"] = 1;
    		$result = M ( "Order" )->save ( $data );
    		if ($reuslt) {
    			return $reuslt;
    		}
    	}
        
        /**
         *支付完成 
         */
    	public function payComplete($id) {
    		$data ["id"] = $id;
    		$data ["pay_status"] = 1;
    		$result = M ( "Order" )->save ( $data );
    		if ($reuslt) {
    			return $reuslt;
    		}
    	}
        
        
        /**
         *更具客户编号获取用户信息 
         */
    	public function getuser($uid) {
    		$m = M ( "wscust" );
    		$where["uid"] = $uid;
    		$result = $m->where($where)->find ();
    		if ($result) {
    			return $result;
    		}
    	}
    }
?>










