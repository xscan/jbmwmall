<?php
	/*
	* 用户与角色模型
	*/
	class userModel extends RelationModel
	{
		//定义主表的名称
		protected $tableName = 'user';

		//定义关联关系
		protected $_link = array(
				'role' => array( //关联的表
						'mapping_type'=>MANY_TO_MANY ,	//多对多关系
						'foreign_key' => 'user_id',	//主表在中间表中的字段字称
						'relation_foreign_key' => 'role_id',	//副表在中间表中的名称
						'relation_table' => 'jbmwmall_role_user',	//中间表名称
						'mapping_fields' => 'id, name, remark',	//显示的字段
					),
			);
	}
?>