<?php
/**
 * HHause 日志处理函数
 *
 * -日期--------修改者----------- 内容 ----------------------
 * 2015-07-28  xie            first edition
 */
namespace fly;
class Log {
	
	public static function debug($msg, $module = null, $method = null) {
		self::ez_log(DEBUG_LOG, $module, $method, $msg);	
	}
	
	public static function biz($msg, $module = null, $method = null) {
		self::ez_log(BIZ_LOG, $module, $method, $msg);
	}
	
	public static function error($msg, $module = null, $method = null) {
		self::ez_log(ERROR_LOG, $module, $method, $msg);
	}
	
	public static function ez_log($file, $module, $method, $msg) {
		$log = fopen($file, "a");
		list($usec, $sec) = explode(" ", microtime());
		$time = date("Y/m/d H:i:s.") . floor($usec * 1000);
		
		$type = gettype($msg);
		if ($type != 'string') {
			$msg = print_r($msg, true);
			$line = "$time\t$module\t$method\t<$type>\n$msg\n";
		} else {
			$line = "$time\t$module\t$method\t$msg\n";
		}
	
		fwrite($log, $line);
		fclose($log);
	}
}