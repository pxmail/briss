<?php
use fly\Action;

/**
 * 删除基地
 * @author yangz
 *
 */
class delete extends Action {
	public function getFilter() {
		$required = [
			'base_id' => FILTER_SANITIZE_NUMBER_INT
		];
		
		return [INPUT_POST, $required, null];
	}
	
	public function exec(array $params = null) {
		$trainee = new trainee();
		$result = $trainee->getByBase($params['base_id']);
		
		if (!empty($result)) {
			return 3020;
		}
		$base = new base();
		$base->delete($params['base_id']);
		
		return ['success' => true];
	}
	
	public function getPrivilege() {
		return 10;
	}
}