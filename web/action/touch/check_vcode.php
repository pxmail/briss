<?php
use fly\Action;
class check_vcode extends Action {
	public function getFilter() {
		$required = [
			'code' => FILTER_SANITIZE_NUMBER_INT
		];
		
		return [INPUT_GET, $required, null];
	}
	
	public function exec(array $params = null) {
		// 从code.txt文件中读取content
		$content = file_get_contents (TOUCH_CODE_FILE);
		$expire = substr($content, 7);//过期时间
		$code = substr($content, 0, 6);//6位验证码
		
		$success = false;
		// 检查code.txt文件内容是否为空，为空则该验证码使用后已被删除
		if (!empty($content) && $expire > time() && $code === $params['code']) {
			$success = true;
			// 登录校验通过后删除code.txt文件中的验证码
			file_put_contents(TOUCH_CODE_FILE, "");
		} 
		
		return ['success' => $success];
	}
	
	public function getPrivilege() {
		return 0;
	}
}