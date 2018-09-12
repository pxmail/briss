<?php
use fly\Action;

/**
 * 获取所有状态正常的运动员数量
 * @author yangz
 *
 */
class list_trainee extends Action {
	public function getFilter() {
		
	}
	
	public function exec(array $params = null) {
		$trainee = new trainee();
		$amount = $trainee->listCount(trainee::STATUS_NORMAL);
		namespace\Log::debug($amount);
		return ['amount' => $amount];
	}
	
	public function getPrivilege() {
		return NULL;
	}
}