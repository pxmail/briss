<?php
require_once 'fly/privilege.php';

use fly\Action;
use fly\Privilege;

/**
 * 列出所有已经入场训练的运动员
 * @author yangz
 *
 */
class list_active extends Action {
	public function getFilter() {
		$optional = array(
			'list_mine' => FILTER_VALIDATE_BOOLEAN	
		);
		
		return [INPUT_GET, null, $optional];
	}

	public function exec(array $params = null) {
		$trainee = new trainee();
		$last_train_date = date('Y-m-d');
		$last_status = trainee::TRAINING_STATUS_STARTED;
		
		if (empty($params['list_mine'])) {
			$trainees = $trainee->listByDate($last_train_date, $last_status);
		} else {
			$trainees = $trainee->listMyActiveTrainee($this->user->uid, $last_train_date, $last_status);
		}
		
		return ['trainees' => $trainees];
	}

	public function getPrivilege() {
		return 0;
	}
}