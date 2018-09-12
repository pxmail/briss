<?php
use fly\Action;
require_once 'ext/Workerman/Autoloader.php';
use \GatewayWorker\Lib\Gateway;

/**
 * 完成训练状态评估表
 * @author yangz
 *
 */
class finish extends Action {
	public function getFilter() {
		$optional = array(
			'trainee_id' => FILTER_SANITIZE_NUMBER_INT,
			'rpe_after' => FILTER_SANITIZE_NUMBER_INT,
			'self_rating' => FILTER_SANITIZE_NUMBER_INT,
			'desire' => FILTER_SANITIZE_NUMBER_INT,
			'sleep' => FILTER_SANITIZE_NUMBER_INT,
			'appetite' => FILTER_SANITIZE_NUMBER_INT,
			'hrv_after' => FILTER_SANITIZE_NUMBER_INT,
			'hrv_cold' => FILTER_SANITIZE_NUMBER_INT,
			'morning_pulse' => FILTER_SANITIZE_NUMBER_INT,
			'attitude' => FILTER_SANITIZE_NUMBER_INT,
			'quality' => FILTER_SANITIZE_NUMBER_INT,
			'omega_wave' => FILTER_SANITIZE_NUMBER_INT,
			'comment' => FILTER_SANITIZE_STRING,
			'rpe_before' => FILTER_SANITIZE_NUMBER_INT,
			'hrv_before' => FILTER_SANITIZE_NUMBER_INT,
			'pain' => FILTER_DEFAULT
		);
		
		return [INPUT_POST, null, $optional];
	}

	public function exec(array $params = null) {
		$trainee = new trainee();
		$trainee->get($params['trainee_id']);
		
		$today = date('Y-m-d');
		if($trainee->last_train_date != $today) {
			return 2030;
		}
		
		// 更新评估表
		$evaluation = new evaluation();
		$evaluation->map($params);
		$evaluation->update($params['trainee_id'], $today);
		
		// 更新学员训练状态
		$trainee->last_status = trainee::TRAINING_STATUS_COMPLETED;
		$trainee->update($params['trainee_id']);
		
		// 向所有用户广播消息
		$data = array(
			'trainee_id' => $params['trainee_id'],
			'type' => 'trainee_out'
		);
		$msg = json_encode($data);
		Gateway::$registerAddress = "127.0.0.1:1237";
		Gateway::sendToAll($msg);
		
		return ['success' => true];
	}

	public function getPrivilege() {
		return 0;
	}
}