<?php
use fly\Action;
/**
 * 教练注册运动员账户
 * @author yangz
 *
 */
class register_trainee extends Action {
	public function getFilter() {
		$required = [
			'name' => FILTER_SANITIZE_STRING,
			'password' => FILTER_SANITIZE_STRING,
			'gender' => FILTER_SANITIZE_STRING,
			'mobile' => FILTER_SANITIZE_STRING,
			'sport_id' => FILTER_SANITIZE_STRING,
			'base_name' => FILTER_SANITIZE_STRING,
			'native' => FILTER_SANITIZE_STRING
		];
		
		$optional = [
			'team' => FILTER_SANITIZE_STRING,
			'dob' => FILTER_SANITIZE_STRING,
			'height' => FILTER_SANITIZE_NUMBER_INT,
			'weight' => FILTER_SANITIZE_NUMBER_INT,
			'nationality' => FILTER_SANITIZE_STRING,
			'dot' => FILTER_SANITIZE_STRING,
			'grade' => FILTER_SANITIZE_STRING,
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
		
		$params['base_id'] = $base->id;
		$params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
		
		$sport = new sport();
		$sport->get($params['sport_id']);
		
		$trainee->sport = $sport->name;
		$trainee->status = trainee::STATUS_NORMAL;
		$trainee->map($params);
		$trainee_id = $trainee->create();
		
		if ($trainee_id) {
			//更新相应基地运动员数目
			$base->trainee_num = $base->trainee_num + 1;
			$base->update($trainee->base_id);
			
			//更新相应运动项目人数
			$sport->trainee_num = $sport->trainee_num + 1;
			$sport->update($params['sport_id']);
		}
		return ['trainee_id' => $trainee_id];
	}
	
	public function getPrivilege() {
		return NULL;
	}
}