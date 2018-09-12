<?php
use fly\Action;

/**
 * 创建基地
 * @author yangz
 *
 */
class create extends Action {
	public function getFilter() {
		$required = [
			'name' => FILTER_SANITIZE_STRING
		];
	
		return [INPUT_POST, $required, null];
	}
	
	public function exec(array $params = null) {
		$base = new base();
		$result = $base->getByName($params['name']);
		
		if (!empty($result)) {
			return 3010;
		}
		$base->name = $params['name'];
		$base->trainee_num = 0;
		$id = $base->create();
		
		return ['base_id' => $id, 'base' => $base];
	}
	
	public function getPrivilege() {
		return 10;
	}
}