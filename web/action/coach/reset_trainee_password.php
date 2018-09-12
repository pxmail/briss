<?php
use fly\Action;

/**
 * 重置运动员密码
 * @author yangz
 *
 */
class reset_trainee_password extends Action {
	public function getFilter() {
		$required = array(
			'trainee_id' => FILTER_SANITIZE_NUMBER_INT
		);
		
		return [INPUT_POST, $required, null];
	}
	
	public function exec(array $params = null) {
		$trainee = new trainee();
		$trainee->password = password_hash ( trainee::DEFAULT_PASSWORD, PASSWORD_DEFAULT );
		$trainee->update($params['trainee_id']);
		
		return ['success' => true];
	}
	
	public function getPrivilege() {
		return 20;
	}
}