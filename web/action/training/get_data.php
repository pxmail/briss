<?php
require_once 'fly/privilege.php';

use fly\Action;
use fly\Privilege;

/**
 * 获取指定时间段内的指定动作数据
 * @author yangz
 *
 */
class get_data extends Action {
	public function getFilter() {
		$required = [
			'movement_id' => FILTER_SANITIZE_NUMBER_INT,
			'trainee_id' => FILTER_SANITIZE_NUMBER_INT,
			'start_date' => FILTER_SANITIZE_STRING,
			'end_date' => FILTER_SANITIZE_STRING
		];
		
		return [INPUT_GET, $required, null];
	}

	public function exec(array $params = null) {
		$training = new training();
		$result = $training->lists($params['trainee_id'], $params['movement_id'],  
				$params['start_date'], $params['end_date']);
		
		return ['data' => $result];
	}

	public function getPrivilege() {
		return NULL;
	}
}