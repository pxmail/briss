<?php
require_once 'fly/privilege.php';
use fly\Action;
use fly\Privilege;

/**
 * 获取运动员入场状态
 *
 */
class is_active extends Action {
	public function getFilter() {	
		
	}
	
	public function exec(array $params = null ) {
		$trainee = new trainee();
		$trainee->get($this->user->uid);
		
		$today = date('Y-m-d');
		if ($today === $trainee->last_train_date && $trainee->last_status === trainee::TRAINING_STATUS_STARTED) {
			$is_active = true;
		} else {
			$is_active = false;
		}
		
		return ['is_active' => $is_active];
	}
	
	public function getPrivilege() {
		return 0;
	}
}