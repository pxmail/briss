<?php
use fly\Action;
use fly\JWT;

/**
 * 当前教练员更新密码
 * @author yangz
 *
 */
class update_password extends Action {
	public function getFilter() {
		$required = [
			'old_password' => FILTER_SANITIZE_STRING,
			'new_password' => FILTER_SANITIZE_STRING
		];
		
		return [INPUT_POST, $required, null];
	}

	public function exec(array $params = null) {
		$coach =  new coach();
		$coach->get($this->user->uid);
		$verify = password_verify($params['old_password'], trim($coach->password));
		if ($verify === false) {
			return 2040;
		}
		if (strlen($params['new_password']) < 6) {
			return 2041;
		}
		
		$new_password = password_hash ( $params ['new_password'], PASSWORD_DEFAULT );
		$coach->password = $new_password;
		$coach->update($this->user->uid);
		
		return ['success' => true];
	}

	public function getPrivilege() {
		return 20;
	}
}