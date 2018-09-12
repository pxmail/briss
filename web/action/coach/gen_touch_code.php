<?php
use fly\Action;

/**
 * 生成触屏端登录用的验证码
 * @author yangz
 *
 */
class gen_touch_code extends Action {
	public function getFilter() {
		return [INPUT_GET, null, null];
	}

	public function exec(array $params = null) {
		// 生成6位随机验证码
		$code = rand(100000, 999999);
		$now = time();
		
		// 验证码过期时间设为5分钟
		$expire = $now + 300;
		$content = $code.'-'.$expire;
		
		$file = fopen(TOUCH_CODE_FILE, "w") or die("Unable to open file!");
		fwrite($file, $content);
		fclose($file);
		return ['code' => $code];
	}

	public function getPrivilege() {
		return 20;
	}
}