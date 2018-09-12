<?php
use fly\Action;
require_once 'ext/Workerman/Autoloader.php';
use \GatewayWorker\Lib\Gateway;

/**
 * 创建动作
 * @author yangz
 *
 */
class create_data extends Action {
	public function getFilter() {
		$required = [
			'trainee_id' => FILTER_SANITIZE_NUMBER_INT,
			'movement_id' => FILTER_SANITIZE_NUMBER_INT
		];
		
		$optional = [
			'number_1' => array(
					'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
					'flags' => FILTER_FLAG_ALLOW_FRACTION
			),
			'number_2' => array(
					'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
					'flags' => FILTER_FLAG_ALLOW_FRACTION
			),
			'number_3' => array(
					'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
					'flags' => FILTER_FLAG_ALLOW_FRACTION
			),
			'number_4' => array(
					'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
					'flags' => FILTER_FLAG_ALLOW_FRACTION
			),
			'number_5' => array(
					'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
					'flags' => FILTER_FLAG_ALLOW_FRACTION
			),
			'number_6' => array(
					'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
					'flags' => FILTER_FLAG_ALLOW_FRACTION
			),
			'number_7' => array(
					'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
					'flags' => FILTER_FLAG_ALLOW_FRACTION
			),
			'number_8' => array(
					'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
					'flags' => FILTER_FLAG_ALLOW_FRACTION
			),
			'string_1' => FILTER_SANITIZE_STRING,
			'string_2' => FILTER_SANITIZE_STRING,
			'group_id' => FILTER_SANITIZE_NUMBER_INT
		];
		
		return [INPUT_POST, $required, $optional];
	}

	public function exec(array $params = null) {
		$training = new training();
		$training->training_date = date('Y-m-d');
		$training->training_time = date('H:i:s');
		$training->map($params);
		$training_id = $training->create();
		
		// 向所有用户广播消息
		$data = array(
			'trainee_id' => $params['trainee_id'],
			'movement_id' => $params['movement_id'],
			'training' => $training,
			'type' => 'new_data'
		);
		$msg = json_encode($data);
		Gateway::$registerAddress = "127.0.0.1:1237";
		Gateway::sendToAll($msg);
		
		return ['id' => $training_id];
	}

	public function getPrivilege() {
		return 40;
	}
}