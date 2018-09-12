<?php
require_once 'fly/jwt.php';

use fly\Action;
use fly\Privilege;
use fly\JWT;

/**
 * 触屏端登录
 * @author yangz
 *
 */
class login extends Action {
	public function getFilter() {
		$required = [
			'code' => FILTER_SANITIZE_NUMBER_INT
		];
		
		return [INPUT_POST, $required, null];
	}

	public function exec(array $params = null) {
		// 从code.txt文件中读取content
		$content = file_get_contents (TOUCH_CODE_FILE);
		$expire = substr($content, 7);//过期时间
		$code = substr($content, 0, 6);//6位验证码

		// 检查code.txt文件内容是否为空，为空则该验证码使用后已被删除
		if (empty($content)) {
			return 3001;
		}
		
		// 检查验证码是否已过期
		if ($expire < time()) {
			return 3002;
		}
		
		// 检查验证码是否匹配
		if ($code != $params['code']) {
			return 3000;
		}
		
		// 登录校验通过后删除code.txt文件中的验证码
		file_put_contents(TOUCH_CODE_FILE, "");
		
		$role = new role();
		$role->get(role::TOUCH_CLIENT);
		
		$obj = new stdClass ();
		$obj->uid = null;
		$obj->role_id = role::TOUCH_CLIENT;
		$obj->privileges = explode(',', $role->privilege);

		$access_token = JWT::encode($obj);
		
		return ['access_token' => $access_token];
	}

	public function getPrivilege() {

	}
}