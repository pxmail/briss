<?php
use fly\Action;

/**
 * 运动员注册
 * @author yangz
 *
 */
class register extends Action {
	public function getFilter() {
		$required = [
			'name' => FILTER_SANITIZE_STRING,
			'password' => FILTER_SANITIZE_STRING,
			'gender' => FILTER_SANITIZE_STRING,
			'mobile' => FILTER_SANITIZE_STRING,
			'sport_id' => FILTER_SANITIZE_NUMBER_INT,
			'grade' => FILTER_SANITIZE_STRING,
			'base_name' => FILTER_SANITIZE_STRING,
			'native' => FILTER_SANITIZE_STRING
		];
		
		$optional = [
			'team' => FILTER_SANITIZE_STRING,
			'dob' => FILTER_SANITIZE_STRING,
			'height' => FILTER_SANITIZE_NUMBER_INT,
			'weight' => FILTER_SANITIZE_NUMBER_INT,
			'dot' => FILTER_SANITIZE_STRING, 
			'nationality' => FILTER_SANITIZE_STRING,
			'avatar' => FILTER_SANITIZE_STRING
		];
		
		return [INPUT_POST, $required, $optional];
	}

	public function exec(array $params = null) {
		// 检查手机号是否合法
		if (!preg_match("/^1[34578]\d{9}$/", $params['mobile'])) {
			return 2090;
		}
		// 检查手机号是否被注册
		$trainee = new trainee();
		$trainee->getByMobile($params['mobile']);
		
		if ($trainee->id) {
			return 2091;
		}
		
		// 检查基地名是否存在
		$base = new base();
		$base->getByName($params['base_name']);
		if (empty($base->id)) {
			return 2092;
		}
		
		$sport = new sport();
		$sport->get($params['sport_id']);
		
		$params['sport'] = $sport->name;
		$params['base_id'] = $base->id;
		$params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
		
		$trainee->status = trainee::STATUS_NOT_REVIEWED;
		$trainee->map($params);
		$trainee->create();
		return ['success' => true];

	}

	public function getPrivilege() {

	}
}