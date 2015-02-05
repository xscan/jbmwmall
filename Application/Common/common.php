<?php
    /**
     * 其它版本
     * 使用方法：
     * $post_string = "app=request&version=beta";
     * request_by_other('http://facebook.cn/restServer.php',$post_string);
     */
    function request_by_other($remote_server, $post_string)
    {
    	$context = array(
    			'http' => array(
    					'method' => 'POST',
    					'header' => 'Content-type: application/x-www-form-urlencoded' .
    					'\r\n'.'User-Agent : Jimmy\'s POST Example beta' .
    					'\r\n'.'Content-length:' . strlen($post_string) + 8,
    					'content' => 'mypost=' . $post_string)
    	);
    	$stream_context = stream_context_create($context);
    	$data = file_get_contents($remote_server, false, $stream_context);
    	return $data;
    }

    /**
     * Goofy 2011-11-30
     * getDir()去文件夹列表，getFile()去对应文件夹下面的文件列表,二者的区别在于判断有没有“.”后缀的文件，其他都一样
     */
    
    //获取文件目录列表,该方法返回数组
    function getDir($dir) {
    	$dirArray[]=NULL;
    	if (false != ($handle = opendir ( $dir ))) {
    		$i=0;
    		while ( false !== ($file = readdir ( $handle )) ) {
    			//去掉"“.”、“..”以及带“.xxx”后缀的文件
    			if ($file != "." && $file != ".." && !strpos($file,".") && $file != '.DS_Store') {
    				$dirArray[$i]=$file;
    				$i++;
    			}
    		}
    		//关闭句柄
    		closedir ( $handle );
    	}
    	return $dirArray;
    }

    //获取文件列表
    function getFile($dir) {
    	$fileArray[]=NULL;
    	if (false != ($handle = opendir ( $dir ))) {
    		$i=0;
    		while ( false !== ($file = readdir ( $handle )) ) {
    			//去掉"“.”、“..”以及带“.xxx”后缀的文件
    			if ($file != "." && $file != ".."&&strpos($file,".")) {
    				$fileArray[$i]="./imageroot/current/".$file;
    				if($i==100){
    					break;
    				}
    				$i++;
    			}
    		}
    		//关闭句柄
    		closedir ( $handle );
    	}
    	return $fileArray;
    }

    //调用方法getDir("./dir")……
    function displayDir($str) {
    	if (! is_dir ( $str ))
    		die ( '不是一个目录！' );
    	$files = array ();
    	if ($hd = opendir ( $str )) {
    		while ( $file = readdir ( $hd ) ) {
    			if ($file != '.' && $file != '..') {
    				if (is_dir ( $str . '/' . $file )) {
    					$files [$file] = displayDir ( $str . '/' . $file );
    				} else {
    					$files [] = $file;
    				}
    			}
    		}
    	}
    	return $files;
    }

    /**
     *数组打印测试函数
     */
    function p($arr){
        echo '<pre>' ;
        print_r($arr) ;
        echo '</pre>';
    }
    
    /**
     * Warning提示信息
     * @param string $type 提示类型 默认支持success, error, info
     * @param string $msg 提示信息
     * @param string $url 跳转的URL地址
     * @return void
     */
    function alert($type='info', $msg='', $url='') {
        //多行URL地址支持
        $url        = str_replace(array("\n", "\r"), '', $url);
    	$alert = unserialize(stripslashes(cookie('alert')));
        if (!empty($msg)) {
            $alert[$type][] = $msg;
    		cookie('alert', serialize($alert));
    	}
        if (!empty($url)) {
    		if (!headers_sent()) {
    			// redirect
    			header('Location: ' . $url);
    			exit();
    		} else {
    			$str    = "<meta http-equiv='Refresh' content='0;URL={$url}'>";
    			exit($str);
    		}
    	}
    
    	return $alert;
    }
	
	/**
	*获取时间(1今天,2昨天,3三天内,4本周,5上周,6本月,7三年内,8半年内,9一年内,10三年内)
	*/
	function getBeforeDaystime($qdays){
		$text = '';
		$now = time(); 
		$q=$qdays;
		if ($q === 1) {// 今天  
			$text = '今天';  
			$beginTime = date('Y-m-d 00:00:00', $now);  
			$endTime = date('Y-m-d 23:59:59', $now);  
		} elseif ($q === 2) {// 昨天  
			$text = '昨天';  
			$time = strtotime('-1 day', $now);  
			$beginTime = date('Y-m-d 00:00:00', $time);  
			$endTime = date('Y-m-d 23:59:59', $now);  
		} elseif ($q === 3) {// 三天内  
			$text = '三天内';  
			$time = strtotime('-2 day', $now);  
			$beginTime = date('Y-m-d 00:00:00', $time);  
			$endTime = date('Y-m-d 23:59:59', $now);  
		} elseif ($q === 4) {// 本周  
			$text = '本周';  
			$time = '1' == date('w') ? strtotime('Monday', $now) : strtotime('last Monday', $now);  
			$beginTime = date('Y-m-d 00:00:00', $time);  
			$endTime = date('Y-m-d 23:59:59', strtotime('Sunday', $now));  
		} elseif ($q === 5) {// 上周  
			$text = '上周';  
			// 本周一  
			$thisMonday = '1' == date('w') ? strtotime('Monday', $now) : strtotime('last Monday', $now);  
			// 上周一  
			$lastMonday = strtotime('-7 days', $thisMonday);  
			$beginTime = date('Y-m-d 00:00:00', $lastMonday);  
			$endTime = date('Y-m-d 23:59:59', strtotime('last sunday', $now));  
		} elseif ($q === 6) {// 本月  
			$text = '本月';  
			$beginTime = date('Y-m-d 00:00:00', mktime(0, 0, 0, date('m', $now), '1', date('Y', $now)));  
			$endTime = date('Y-m-d 23:39:59', mktime(0, 0, 0, date('m', $now), date('t', $now), date('Y', $now)));  
		} elseif ($q === 7) {// 三月内  
			$text = '三月内';  
			$time = strtotime('-2 month', $now);  
			$beginTime = date('Y-m-d 00:00:00', mktime(0, 0,0, date('m', $time), 1, date('Y', $time)));  
			$endTime = date('Y-m-d 23:39:59', mktime(0, 0, 0, date('m', $now), date('t', $now), date('Y', $now)));  
		} elseif ($q === 8) {// 半年内  
			$text = '半年内';  
			$time = strtotime('-5 month', $now);  
			$beginTime = date('Y-m-d 00:00:00', mktime(0, 0,0, date('m', $time), 1, date('Y', $time)));  
			$endTime = date('Y-m-d 23:39:59', mktime(0, 0, 0, date('m', $now), date('t', $now), date('Y', $now)));  
		}  elseif ($q === 9) {// 一年内  
			$text = '一年内';  
			$beginTime = date('Y-m-d 00:00:00', mktime(0, 0,0, 1, 1, date('Y', $now)));  
			$endTime = date('Y-m-d 23:39:59', mktime(0, 0, 0, 12, 31, date('Y', $now)));  
		} elseif ($q === 10) {// 三年内  
			$text = '三年内';  
			$time = strtotime('-2 year', $now);  
			$beginTime = date('Y-m-d 00:00:00', mktime(0, 0, 0, 1, 1, date('Y', $time)));  
			$endTime = date('Y-m-d 23:39:59', mktime(0, 0, 0, 12, 31, date('Y')));  
		} elseif ($q > 10) {// 三年内  
			$text = $q . '天内'; 
			$tmp = '-' . $q . ' days';
			$time = strtotime($tmp, $now);  
			$beginTime = date('Y-m-d 00:00:00', $time);  
			$endTime = date('Y-m-d 23:39:59', $now);  
		} 
		$bfdayinfo = array(
			'text' => $text,
			'beginTime' => $beginTime,
			'endTime' => $endTime
		);
		return $bfdayinfo;
	}
	
	/**
     * 模板截取中文字符串函
     *$str:要截取的字符串 
     *$start=0：开始位置，默认从0开始 
     *$length：截取长度
     * $charset=”utf-8″：字符编码，默认UTF－8
     * $suffix=true：是否在截取后的字符后面显示省略号，默认true显示，false为不显示
     * return string
     * 模版使用：{$vo.title|msubstr=0,5,'utf-8',false}
     */
    function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {  
        if(function_exists("mb_substr")){  
            if($suffix)  
                return mb_substr($str, $start, $length, $charset)."...";  
            else
                return mb_substr($str, $start, $length, $charset);  
        }elseif(function_exists('iconv_substr')) {  
            if($suffix)  
                return iconv_substr($str,$start,$length,$charset)."...";  
             else
                return iconv_substr($str,$start,$length,$charset); 
         }  
         $re['utf-8']   = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef]
                  [x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";  

         $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";  

         $re['gbk']    = "/[x01-x7f]|[x81-xfe][x40-xfe]/";  

         $re['big5']   = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";  

         preg_match_all($re[$charset], $str, $match);  
         $slice = join("",array_slice($match[0], $start, $length));  
         if($suffix) return $slice."…";  
         return $slice;

    }
	
	//递归重组节点信息为多维数组
	function node_merge($node, $access = null, $pid = 0){
		$arr = array();
		foreach($node as $v){
			if(is_array($access)){
				$v['access'] = in_array($v['id'], $access) ? 1 : 0;
			}
			if($v['pid'] == $pid){
				$v['child'] = node_merge($node, $access, $v['id']);
				$arr[] =$v;
			}
		}
		return $arr;
	}
?>