<?php
    //采用ADODB连接万能数据库类
    //设计 金博美言工
    //时间 2014-11-9
    
    class connadodb{
		private $mydns; //连接协议
		private $mydb;  //数据库名称
		private $db;    //数据库连接对象
		private $rs;    //结果集
		private $dbstate;   //数据库连接状态
		private $db_typ;    //数据库类型
		private $ip;    //数据服务器IP
		private $uid;   //数据库用户名
		private $pwd;   //数据库密码
		private $errormsg; //错误消息
		private $records; //记录条数
		private $db_debug=0; //数据库调试模式
        
        
        //构造函数
		public function __construct($mydb='',$db_type='',$ip='',$uid='',$pwd='',$db_debug=false){
			include_once("adodb5/adodb.inc.php");
			//获取外部数据源设置
			$extdb = $result = M("extdb" )->where( array(
        				"id" => "1" 
        		))->select();
			if (!empty($extdb)){
				$this->ip = $extdb[0]['ext_ip'];
				$this->db_type = $extdb[0]['ext_type'];
				$this->mydb = $extdb[0]['ext_dbname'];
				$this->uid = $extdb[0]['ext_uid'];
				$this->pwd = $extdb[0]['ext_pwd'];
				$this->db_debug = C('DB_DEBUG');
				//p($extdb);
			}else{	
				if(!$mydb){
					$this->mydb=C('JBM_DB');
				}else{
					$this->mydb = $mydb;
				}
				if(!$db_type){
					$this->db_type = C('JBM_DB_TYPE'); 
				}else{
					$this->db_type=$db_type;
				}
				if(!$ip) {
					$this->ip = C('SERVER_IP'); 
				}else{
					$this->ip = $ip;
				}
				if(!$uid){
					$this->uid = C('UID');
				}else{
				   $this->uid = $uid;
				}
				if(!$pwd){
					$this->pwd = C('PWD');
				}else{
					$this->pwd = $pwd;
				}
				if($db_debug){ 
					$this->db_debug = C('DB_DEBUG');
				}else{
					$this->db_debug = $db_debug;
				}
			}
        }
        
        //获取数据连接对象
        public function getdb(){
            return $this->db;
        }
        
        //获取数据库处理错误信息
        public function geterror(){
            return $this->db->ErrorMsg();
        }
        //打开数据库连接
        public function opendb(){
            switch ($this->db_type){ 
            	case 'odbc_mssql':  //连接本地数据库PHP5以上版本
                    $this->mydns='Driver={SQL Server};Server=' . $this->ip . 
                    ';Database=' . $this->mydb .';';
                    $this->db = NewADOConnection('odbc_mssql');
                    //开启调试模式
                    //$this->db->debug = true;
                    //$this->db->charPage =65001; 
					   //echo $this->mydns;die;
                    $this->db->debug = $this->db_debug;
                    $this->db->Connect($this->mydns,$this->uid,$this->pwd); //连接数据库
                    $this->db->Execute("set names 'GBK'");
                    if (! $this->db){
                        $this->dbstate = false; 
                    }else{
                        $this->dbstate = true;  
                    }
                    break;
                    
                case 'ado_mssql': //连接远程数据库PHP4.3一下版本
                    $this->mydns='PROVIDER=MSDASQL;DRIVER={SQL Server};SERVER=' . $this->ip .
                    ';DATABASE=' . $this->mydb . ';UID=' . $this->uid . ';PWD=' .$this->pwd.';';
                    $this->db = NewADOConnection('ado_mssql');
                    //开启调试模式
                    $this->db->debug = $this->db_debug;
                    //$this->db->charPage =65001; 
                    $this->db->Connect($this->mydns); //连接数据库
                    $this->db->Execute("set names 'GBK'");
                    if (! $this->db){
                        $this->dbstate = false; 
                    }else{
                        $this->dbstate = true;  
                    }
                    break;

            	case 'access':
                    $this->mydns='Driver={Microsoft Access Driver (*.mdb)};Dbq='.$this->mydb .';';
                    $this->db = NewADOConnection('access');
                    $this->db->Connect($this->mydns); //连接数据库
                    $this->db->Execute("set names 'GBK'");
                    if (! $this->db){
                       $this->dbstate = false; 
                    }else{
                       $this->dbstate = true;  
                    }
                    break;
            
            	default : //默认连接mysql数据库
                    $this->db = NewADOConnection($this->mydb);
                    if (! $this->db){
                        $this->dbstate = false; 
                    }else{
                        //$this->db->charPage =65001; 
                        $this->dbstate = true;  
                    }
                    break;
            }
            //return $this->db;  //用于insert update等操作
        }
        
        public function getDbState(){
            return $this->dbstate;
        }
        //根据SQL语句查询,返回数组
        //$colbig指列明的大小写问题
        public function queryall($sql,$colbig = false){
            $arr = array();
            if ($this->dbstate){
                $this->rs=$this->db->Execute($sql);
                //获取记录条数
                if (!$this->rs) {
                    $this->records = 0;
                }else{
                    $this->records = $this->rs->RecordCount();
                }
                while( !$this->rs->EOF ) {
                    $row = $this->rs->fields;
                    //$arr[] = array(0 => $row[0],1 => $row[1]); 
                    $arr[] = $this->rs->GetRowAssoc($colbig);
                    $this->rs->MoveNext();
                }
            }
            return $arr;
        }
        
        //传参数条件查询
        //只支持OCR8I INTERBASE数据库
        public function querybyarr($sql,$csarr,$colbig = false){
            $arr = array();
            if ($this->dbstate){
                //$this->db->SetFetchMode(ADODB_FETCH_ASSOC);
                $this->rs=$this->db->Execute($sql,$arr);
                //获取记录条数
                if (!$this->rs) {
                    $this->records = 0;
                }else{
                    $this->records = $this->rs->RecordCount();
                }
                while( !$this->rs->EOF ) {
                    $row = $this->rs->fields;
                    $arr[] = array(0 => $row[0],1 => $row[1]); 
                    //$arr[] = $this->rs->GetRowAssoc($colbig);
                    $this->rs->MoveNext();
                }
            }
            return $arr;
        }
        
        //插入数据
        //返回插入的记录条数
        public function insertarr($tbl,$arr){
            $rtn = $this->db->AutoExecute($tbl,$arr,'INSERT');
            return $rtn;
        }
        
        //跟新记录
        //返回跟新记录条数
        public function updatearr($tbl,$arr,$where){
            $rtn = $this->db->AutoExecute($tbl,$arr,'UPDATE',$where);
            return $rtn;
        }
        
        /*
        public function querycs($sql,$arr,$colbig = false){
            $arr = array();
            if ($this->dbstate){
                $this->db->Execute("set names 'GBK'");
                $this->rs=$this->db->prepare($sql,$arr);
                //获取记录条数
                if (!$this->rs) {
                    $this->records = 0;
                    return $arr;
                }else{
                    //$this->records = $this->rs->RecordCount();
                }
                while( !$this->rs->EOF ) {
                    $row = $this->rs->fields;
                    $arr[] = array(0 => $row[0],1 => $row[1]); 
                    //$arr[] = $this->rs->GetRowAssoc($colbig);
                    $this->rs->MoveNext();
                }
            }
            return $arr;
        }
        */
        
        //获取结果集记录条数
        public function getrecords(){
            return $this->records;
        }
        
        //根据SQL查询数据,默认是查询一列结果
        public function query($sql,$cols = 1){
            $arr = array();
            if ($this->dbstate){
                //设定字符集
                $this->db->Execute("set names 'GBK'");
                //设置输出键以数组形式
                //$this->db->SetFetchMode(ADODB_FETCH_ASSOC);
                $this->rs=$this->db->Execute($sql);
                if (!$this->rs) {
                    $this->records = 0;
                }else{
                    $this->records = $this->rs->RecordCount();
                }
                //判断数据集
                if (!$this->rs){
                    $this->errormsg = $this->db->ErrorMsg();
                    return $arr;
                }
                //遍历出数据
    			while($row=$this->rs->FetchRow()){
	            //while($this->rs->EOF){
    			     //$row = $this->rs;
                     //进行字符集转换
    			     //$arr[] = array(0 => $row[0],1 => mb_convert_encoding($row[1],"UTF-8","GBK"));
    			     switch ($cols){ 
    			         case 1:
                            $arr[] = array(0 => $row[0]); 
                            break;
                            
                         case 2:
                            $arr[] = array(0 => $row[0],1 => $row[1]); 
                            break;
                            
                         case 3:
                            $arr[] = array(0 => $row[0],
                            1 => $row[1],
                            2 => $row[2]
                            ); 
                            break;
                            
                         case 4:
                            $arr[] = array(0 => $row[0],
                            1 => $row[1],
                            2 => $row[2],
                            3 => $row[3]
                            ); 
                            break;
                            
                         case 5:
                            $arr[] = array(0 => $row[0],
                            1 => $row[1],
                            2 => $row[2],
                            3 => $row[3],
                            4 => $row[4]
                            ); 
                            break;
                            
                         case 6:
                            $arr[] = array(0 => $row[0],
                            1 => $row[1],
                            2 => $row[2],
                            3 => $row[3],
                            4 => $row[4],
                            5 => $row[5]
                            ); 
                            break;
                            
                         case 7:
                            $arr[] = array(0 => $row[0],
                            1 => $row[1],
                            2 => $row[2],
                            3 => $row[3],
                            4 => $row[4],
                            5 => $row[5],
                            6 => $row[6]
                            ); 
                            break;
                            
                         case 8:
                            $arr[] = array(0 => $row[0],
                            1 => $row[1],
                            2 => $row[2],
                            3 => $row[3],
                            4 => $row[4],
                            5 => $row[5],
                            6 => $row[6],
                            7 => $row[7]
                            ); 
                            break;
                            
                         case 9:
                            $arr[] = array(0 => $row[0],
                            1 => $row[1],
                            2 => $row[2],
                            3 => $row[3],
                            4 => $row[4],
                            5 => $row[5],
                            6 => $row[6],
                            7 => $row[7],
                            8 => $row[8]
                            ); 
                            break;
                            
                         case 10:
                            $arr[] = array(0 => $row[0],
                            1 => $row[1],
                            2 => $row[2],
                            3 => $row[3],
                            4 => $row[4],
                            5 => $row[5],
                            6 => $row[6],
                            7 => $row[7],
                            8 => $row[8],
                            9 => $row[9]
                            ); 
                            break;
                         default :
                            $arr[] = array(0 => $row[0]); 
                            break;
                     }
                   //$this->rsMoveNext(); 
    			}
            }
            //返回去到的数组
            return $arr;
        }
        
        //关闭连接和结果集
        public function closedb(){
            if ($this->rs){
                $this->rs->Close();
            }
            if ($this->db){
                $this->db->close();
            }
        }
    
        //析构函数,释放数据库资源
        public function __destroy(){
			
		}
    }

?>