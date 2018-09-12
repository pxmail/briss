<?php
use fly\Action;
require_once 'ext/Workerman/Autoloader.php';
use \GatewayWorker\Lib\Gateway;


/**
 * 创建训练状态评估表
 * @author yangz
 *
 */
class create extends Action {
	public function getFilter() {
		$required = [
			'trainee_id' => FILTER_SANITIZE_NUMBER_INT,
			'self_rating' => FILTER_SANITIZE_NUMBER_INT,
			'desire' => FILTER_SANITIZE_NUMBER_INT,
			'sleep' => FILTER_SANITIZE_NUMBER_INT,
			'appetite' => FILTER_SANITIZE_NUMBER_INT,					
			'pain' => FILTER_DEFAULT
		];
		
		$optional = [
			'omega_wave' => FILTER_SANITIZE_NUMBER_INT,
			'rpe_before' => FILTER_SANITIZE_NUMBER_INT,
			'hrv_before' => FILTER_SANITIZE_NUMBER_INT,
			'morning_pulse' => FILTER_SANITIZE_NUMBER_INT
		];
		
		return [INPUT_POST, $required, $optional];
	}

	public function exec(array $params = null) {	
		$trainee = new trainee();
		$trainee->get($params['trainee_id']);
		
		$today = date('Y-m-d');
		if ($trainee->last_train_date === $today) {
			return 2020;
		}
		
		$evaluation = new evaluation();
		$evaluation->create_date = date('Y-m-d', time());
		$evaluation->create_time = date('H:i:s', time());
		$evaluation->map($params);
		$evaluation->create();
		
		$trainee->last_train_date = $today;
		$trainee->last_status = trainee::TRAINING_STATUS_STARTED;
		$trainee->day = $trainee->day + 1;
		$trainee->update($params['trainee_id']);
		
		unset($trainee->password);
		
		// 向所有用户广播消息
		$data = array(
			'trainee' => $trainee,
			'type' => 'trainee_in'
		);
		$msg = json_encode($data);
		Gateway::$registerAddress = "127.0.0.1:1237";
		Gateway::sendToAll($msg);
		
		return ['success' => true];
	}

	public function getPrivilege() {
		return NULL;
	}
}