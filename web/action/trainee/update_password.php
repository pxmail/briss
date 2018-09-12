<?php
use fly\Action;

/**
 * 
 * 更新当前学员密码
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
		$trainee =  new trainee();
		$trainee->get($this->user->uid);
		$verify = password_verify($params['old_password'], trim($trainee->password));
		if ($verify === false) {
			return 2040;
		}
		if (strlen($params['new_password']) < 6) {
			return 2041;
		}
		
		$new_password = password_hash ( $params ['new_password'], PASSWORD_DEFAULT );
		$trainee->password = $new_password;
		$result = false;
		$result = $trainee->update($this->user->uid);
		if ($result) {
			$result = true;
		}
		return ['success' => $result];
	}

	public function getPrivilege() {
		return 30;
	}
}