<?php
use fly\Action;

/**
 * 超级教练员重置其它教练员密码
 * @author yangz
 *
 */
class reset_password extends Action {
	public function getFilter() {
		$required = [
			'coach_id' => FILTER_SANITIZE_NUMBER_INT
		];
		
		return [INPUT_POST, $required, null];
	}

	public function exec(array $params = null) {
		if ($params['coach_id'] === $this->user->uid) {
			return 2060;
		}
		
		$coach = new coach();
		$coach->password = password_hash ( coach::DEFAULT_PASSWORD, PASSWORD_DEFAULT );
		$coach->update($params['coach_id']);
		
		return ['success' => true];
	}

	public function getPrivilege() {
		return 10;
	}
}