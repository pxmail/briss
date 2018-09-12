<?php
use fly\Action;

/**
 * 获取运动员详细资料
 * @author yangz
 *
 */
class get extends Action {
	public function getFilter() {
		$required = [
			'id' => FILTER_SANITIZE_NUMBER_INT
		];
		
		return [INPUT_GET, $required, null];
	}

	public function exec(array $params = null) {
		$trainee = new trainee();
		$trainee->get($params['id']);
		unset($trainee->password);
		
		if (empty($trainee->id)) {
			return 2010;
		}
		
		return ['trainee' => $trainee];
	}

	public function getPrivilege() {
		return 0;
	}
}