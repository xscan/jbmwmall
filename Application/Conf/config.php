<?php
$arr1 =  array(
	//'配置项'=>'配置值'
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_PORT'   => '3306', // 端口
	'DB_CHARSET'=> 'utf8',// 数据库编码默认采用utf8
// 	'URL_ROUTER_ON'   => true,
// 	'SHOW_PAGE_TRACE' => true,
	'APP_GROUP_LIST' => 'Admin,App,Api', //项目分组设定
	'DEFAULT_GROUP'  => 'Admin', //默认分组
    
	
	'RBAC_SUPERADMIN' => 'admin',				//超级管理员名称
	'ADMIN_AUTH_KEY' => 'superadmin',			//超级管理员识别号
	'USER_AUTH_ON' => true,						//是否开启验证
	'USER_AUTH_TYPE' => 1,						//验证类型（1.登录时验证2.时时验证）
	'USER_AUTH_KEY' => 'uid',					//用户认证识别号
	'NOT_AUTH_MODEL' => 'Index,Login,Public',						//无需认证的控制器
	'NOT_AUTH_ACTION' => 'getgoodid,publish,payComplete',				//无需认证的动作方法
	'RBAC_ROLE_TABLE' => 'jbmwmall_role',				//角色表名称
	'RBAC_USER_TABLE' => 'jbmwmall_role_user',		//角色与用户的中间表名称
	'RBAC_ACCESS_TABLE' => 'jbmwmall_access',			//权限表名称
	'RBAC_NODE_TABLE' => 'jbmwmall_node',				//节点表名称
	
	
    //数据库类型 本地数据库odbc_mssql PHP4.3以上 数据库ado_mssql方式(OLEDB)PHP4.3(可以不设置)
    //'JBM_DB_TYPE' => 'odbc_mssql',
    //远程数据库服务器IP(可以不设置)
    //'SERVER_IP' => '127.0.0.1',
    //数据库服务器用户名(可以不设置)
    //'UID' => 'sa',
    //数据库服务器密码(可以不设置)
    //'PWD' => 'bm0731',
	
    //数据库调试模式(必须设置)
    'DB_DEBUG' => false,
    
    //数据库名称(可以不设置)
    //'JBM_DSN' => 'Driver={SQL Server};Server=localhost;Database=',
    //access数据库 d:\\northwind.mdb;Uid=Admin;Pwd=
    //mysql数据库 'mysql://root:pwd@localhost/mydb'
    //'JBM_DB' => 'bmdatawxtest',
    
    //微信类型 订阅号(DY) 服务号(FW) 企业号(QY)
    'WX_TYPE' => 'DY',
    
    'DEFAULT_FILTER' => 'htmlspecialchars',
	
	'account'=>'Hlmedia', //公众平台账号

	'password'=>'hnhl_101', //公众平台密码
	
	'datapath'=>'./data/cookie_', //cookie路径
	
	'debug'=>true, //调试模式()
	
	'logcallback'=>'logdebug'//调试日志
);

include './Public/Conf/config.php';

$arr2 = array(
	'DB_HOST'   => DB_HOST, // 服务器地址
	'DB_NAME'   => DB_NAME, // 数据库名
	'DB_USER'   => DB_USER, // 用户名
	'DB_PWD'    => DB_PWD,  // 密码
	'DB_PREFIX' => DB_PREFIX, // 数据库表前缀
);

return array_merge($arr1 , $arr2);
?>