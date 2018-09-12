<?php
require_once 'fly/privilege.php';

use fly\Action;
use fly\Privilege;

/**
 * 获取运动员的训练日历
 * @author yangz
 *
 */
class get_calendar extends Action {
	public function getFilter() {
		$required = [
			'trainee_id' => FILTER_SANITIZE_NUMBER_INT
		];
		
		$optional = [
			'year_month' => FILTER_SANITIZE_STRING
		];
		
		return [INPUT_GET, $required, $optional];
	}

	public function exec(array $params = null) {
		$evaluation = new evaluation();
		
		if (empty($params['year_month'])) {
			$calendar = $evaluation->listByTrainee($params['trainee_id']);
		} else {
			$calendar = $evaluation->listByDate($params['trainee_id'], $params['year_month']);
		}
		
		return ['calendar' => $calendar];
	}

	public function getPrivilege() {
		return 0;
	}
}