<?php
/**
*RBAC权限控制器
**/

class RbacAction extends PublicAction{
	/**
	*用户列表
	*/
	public function userList(){
		$this->user = D('user')->field('password', true)->relation(true)->where("username <> 'admin'")->select();
		//echo "<pre>";
		//print_r($user);
		$this->display();
	}
	
	/**
	*添加或修改用户显示窗体
	*/
	public function addUser(){
		if($_GET['id']){
			$this->user = M('user')->where( array ( 'id' => $_GET['id'] ) )->find();
			$this->role_id = M('role_user')->where( 'user_id ='. $_GET['id'] )->find();
		}
		$this->role = M('role')->select();
		$this->display();
	}
	
	/**
	*添加或修改用户处理
	*/
	public function addUserHandle(){
		//修改用户
		if($_POST['id']){
			if(M('user')->where( array ( 'password' => $_POST['password'] ) )->find()){
				$user ['password'] = $_POST['password'];
			}else{
				$user ['password'] = md5($_POST['password']);
			}
			$user ['id'] = $_POST['id'];
			$user ['username'] = $_POST['username'];
			$user ['time'] = date('Y-m-d H:i:s',time());
			$user ['loginip'] = get_client_ip();
			$user ['status'] = $_POST['status'];
		}else{
			$user = array(
					'username' => $_POST['username'],
					'password' => md5($_POST['password']),
					'time' => date('Y-m-d H:i:s',time()),
					'loginip' => get_client_ip(),
					'status' => $_POST['status'],
				);
		}
		//添加用户处理
		if($result = M('user')->where('username = '.$_POST['username'])->select()){
			$flag = array(
				   "statusCode"=>"300",
				   "message"=>"用户名已存在!!!",
				   "tabid"=>"Menu",
				   "dialogid"=>"dialog-mask",
				   "closeCurrent"=>false,
				   "forward"=>"",
				   "forwardConfirm"=>""
				);
		}else{
			if($user['id']){
				$result = M('user')->save($user);
				$data['user_id'] = $user['id'];
				$data['role_id'] = $_POST['role_id'];
				M('role_user')->where('user_id = '.$user['id'])->delete();
				$result1 = M('role_user')->add($data);
				if($result || $result1) $result = 1;
				
			}else{
				//判断用户是否是内置用户admin
				if(strtoupper($user['username']) == 'ADMIN'){
					$flag = array(
					   "statusCode"=>"300",
					   "message"=>"用户增加失败,原因==>admin是系统内置用户,不允许添加!!!",
					   "tabid"=>"Menu",
					   "dialogid"=>"dialog-mask",
					   "closeCurrent"=>false,
					   "forward"=>"",
					   "forwardConfirm"=>""
					);
					$this->ajaxReturn( $flag );
				}else{
					$result = M('user')->add($user);
					$data['user_id'] = $result;
					$data['role_id'] = $_POST['role_id'];
					$result = M('role_user')->add($data);
				}
			}
			if($result){
				$flag = array(
				   "statusCode"=>"200",
				   "message"=>"用户信息保存成功!",
				   "tabid"=>"Menu",
				   "dialogid"=>"dialog-mask",
				   "closeCurrent"=>true,
				   "forward"=>"",
				   "forwardConfirm"=>""
				);
			}else{
				$flag = array(
				   "statusCode"=>"300",
				   "message"=>"用户信息保存失败!!!",
				   "tabid"=>"Menu",
				   "dialogid"=>"dialog-mask",
				   "closeCurrent"=>false,
				   "forward"=>"",
				   "forwardConfirm"=>""
				);
			}
		}
		$this->ajaxReturn( $flag );
	}
	
	/**
	*删除用户
	*/
	public function delUser(){
		$id = $_GET['id'];
		$result = M('user')->where( array ( 'id' => $id ))->delete();
		if($result){
			$flag = array(
			   "statusCode"=>"200",
			   "message"=>"用户删除成功!",
			   "tabid"=>"Menu",
			   "dialogid"=>"dialog-mask",
			   "closeCurrent"=>false,
			   "forward"=>"",
			   "forwardConfirm"=>""
			);
		}else{
			$flag = array(
			   "statusCode"=>"300",
			   "message"=>"用户删除失败!!!",
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
	*角色列表显示
	*/
	public function roleList(){
		$this->role = M('role')->select();
		$this->display();
	}
	
	/**
	*添加或修改角色窗体显示
	*/
	public function addRole(){
		if($_GET['id']){
			$this->role = M('role')->where( array ( 'id' => $_GET['id'] ) )->find();
		}
		$this->display();
	}
	
	/**
	*添加或修改角色处理
	*/
	public function addRoleHandle(){
		$role = $_POST;
		if($role['id']){
			$result = M('role')->save($role);
		}else{
			//判断角色名称是否存在
			if(M('role')->where( array ( 'name' => $role['name'] ) )->find()){
				$flag = array(
					"statusCode"=>"300",
					"message"=>"角色保存失败,原因==>角色名称重复!!!",
					"tabid"=>"Menu",
					"dialogid"=>"dialog-mask",
					"closeCurrent"=>false,
					"forward"=>"",
					"forwardConfirm"=>""
				);
				$this->ajaxReturn( $flag );
			}else{
				$result = M('role')->add($role);
			}
		}
		if($result){
			$flag = array(
			   "statusCode"=>"200",
			   "message"=>"角色保存成功!",
			   "tabid"=>"Menu",
			   "dialogid"=>"dialog-mask",
			   "closeCurrent"=>true,
			   "forward"=>"",
			   "forwardConfirm"=>""
			);
		}else{
			$flag = array(
			   "statusCode"=>"300",
			   "message"=>"角色保存失败!!!",
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
	*删除角色处理
	*/
	public function delRole(){
		$id = $_GET['id'];
		$result = M('role')->where( array ( 'id' => $id ))->delete();
		if($result){
			$flag = array(
			   "statusCode"=>"200",
			   "message"=>"删除角色成功!",
			   "tabid"=>"Menu",
			   "dialogid"=>"dialog-mask",
			   "closeCurrent"=>false,
			   "forward"=>"",
			   "forwardConfirm"=>""
			);
		}else{
			$flag = array(
			   "statusCode"=>"300",
			   "message"=>"删除角色失败!!!",
			   "tabid"=>"Menu",
			   "dialogid"=>"dialog-mask",
			   "closeCurrent"=>false,
			   "forward"=>"",
			   "forwardConfirm"=>""
			);
		}
		$this->ajaxReturn( $flag );
	}
	
	//节点列表
	public function nodeList(){
		$field = array('id','name','title','pid');
		$node = M('node')->field($field)->order('sort')->select();
		$this->node = node_merge($node);
		//$field = array('id','name','title','pid','status','level');
		//$this->node = M('node')->field($field)->order('sort')->select();
		$this->display();
	}
	
	/**
	*添加节点
	*/
	public function addNode(){
		$this->pid = I('pid', 0, 'intval');
		$this->level = I('level', 1, 'intval');
		if($_GET['id']){
			$node = M('node')->where( array ( 'id' => $_GET['id'] ) )->find();
			$this->node = $node;
			$this->pid = $node['pid'];
			$this->level = $node['level'];
			
		}
		switch($this->level){
			case 1:
				$this->type = "应用";
				break;
			case 2:
				$this->type = "控制器";
				break;
			case 3:
				$this->type = "方法";
				break;
		}
		$this->display();
	}
	
	/**
	*添加节点处理
	*/
	public function addNodeHandle(){
		$Node = $_POST;
		if($Node['id']){
			$result = M('node')->save($Node);
		}else{
			$result = M('node')->add($Node);
		}
		if($result){
			$flag = array(
			   "statusCode"=>"200",
			   "message"=>"保存节点成功!",
			   "tabid"=>"Menu",
			   "dialogid"=>"dialog-mask",
			   "closeCurrent"=>true,
			   "forward"=>"",
			   "forwardConfirm"=>""
			);
		}else{
			$flag = array(
			   "statusCode"=>"300",
			   "message"=>"保存节点失败!!!",
			   "tabid"=>"Menu",
			   "dialogid"=>"dialog-mask",
			   "closeCurrent"=>false,
			   "forward"=>"",
			   "forwardConfirm"=>""
			);
		}
		$this->ajaxReturn( $flag );
	}
	//删除节点
	public function delNode(){
		$id = $_GET['id'];
		$result = M('node')->where( array ( 'id' => $id ))->delete();
		if($result){
			$flag = array(
			   "statusCode"=>"200",
			   "message"=>"删除成功",
			   "tabid"=>"Menu",
			   "dialogid"=>"dialog-mask",
			   "closeCurrent"=>false,
			   "forward"=>"",
			   "forwardConfirm"=>""
			);
		}else{
			$flag = array(
			   "statusCode"=>"300",
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
	*配置权限列表显示
	*/
	public function access(){
		$rid = I('rid', 0, 'intval');
		$field = array('id', 'name', 'title', 'pid');
		$node = M('node')->order('sort')->field($field)->select();
		
		//原有权限
		$access = M('access')->where(array('role_id' => $rid))->getField('node_id', true);
		$this->node = node_merge($node, $access);
		$this->rid = $rid;
		$this->display();
	}
	
	/**
	*保存权限
	*/
	public function setAccess(){
		$rid = I('rid', 0, 'intval');
		$db = M('access');
		
		//删除原来的权限
		$db->where(array('role_id' => $rid))->delete();
		
		$data = array();
		foreach ($_POST['access'] as $v){
			$tmp = explode('_', $v);
			$data[] = array(
				'role_id' => $rid,
				'node_id' => $tmp[0],
				'level' => $tmp[1],
			);
		}
		
		if($db->addAll($data)){
			$flag = array(
			   "statusCode"=>"200",
			   "message"=>"保存权限成功!",
			   "tabid"=>"Menu",
			   "dialogid"=>"dialog-mask",
			   "closeCurrent"=>true,
			   "forward"=>"",
			   "forwardConfirm"=>""
			);
		} else {
			$flag = array(
			   "statusCode"=>"300",
			   "message"=>"保存权限失败!!!",
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
?>