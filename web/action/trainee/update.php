<?php
require_once 'fly/privilege.php';
use fly\Action;
use fly\Privilege;

/**
 *
 * 修改运动员信息
 *
 */
class update extends Action {
	public function getFilter() {
		$required = array(
			'id' => FILTER_SANITIZE_NUMBER_INT,
			'name' => FILTER_SANITIZE_STRING,
			'mobile' => FILTER_SANITIZE_STRING,
			'gender' => FILTER_SANITIZE_STRING,
			'native' => FILTER_SANITIZE_STRING,
			'base_name' => FILTER_SANITIZE_STRING,
			'sport_id' => FILTER_SANITIZE_NUMBER_INT,
			'grade' => FILTER_SANITIZE_STRING
		);
		
		$optional = array(
			'team' => FILTER_SANITIZE_STRING,
			'dob' => FILTER_SANITIZE_STRING,
			'height' => FILTER_SANITIZE_NUMBER_INT,
			'weight' => FILTER_SANITIZE_NUMBER_INT,
			'nationality' => FILTER_SANITIZE_STRING,
			'dot' => FILTER_SANITIZE_STRING,
			'avatar' => FILTER_SANITIZE_STRING
		);
		
		return [INPUT_POST, $required, $optional];
	}	
	
	public function exec(array $params = null) {
		$trainee = new trainee();
		$trainee->get($params['id']);
		
		if (empty($trainee->id)) {
			return 2010;
		}
		
		// 检查修改的手机号是否与 数据库中的其它数据重复
		if (isset($params['mobile'])) {
			if ($params['mobile'] !== $trainee->mobile) {
				$obj = new trainee();
				$obj->getByMobile($params['mobile']);
				if ($obj->id) {
					return 2091;
				}
			}
		}
		
		//如果基地信息修改了，则修改基地相应运动员数量信息
		if ($params['base_name'] !== $trainee->base_name) {
			$base = new base();
			$base->getByName($params['base_name']);
			$params['base_id'] = $base->id;
			//新基地运动员数目加1
			$base->trainee_num = $base->trainee_num + 1;
			$base->update($params['base_id']);
			
			//原基地运动员数目减1
			$base->get($trainee->base_id);
			$base->trainee_num = $base->trainee_num - 1;
			$base->update($base->id);
		}
		
		$sport = new sport();
		$sport->get($params['sport_id']);
		$trainee->sport = $sport->name;
		
		//如果运动项目修改了，则修改新旧运动项目拥有的运动员数目
		if ($params['sport_id'] !== $trainee->sport_id) {
			//新运动项目人数加1
			$sport->trainee_num = $sport->trainee_num + 1;
			$sport->update($params['sport_id']);
			
			//原运动项目人数减1
			$sport->get($trainee->sport_id);
			$sport->trainee_num = $sport->trainee_num - 1;
			$sport->update($trainee->sport_id);
		}
		
		$trainee->map($params);
		$trainee->update($params['id']);
		
		return ['trainee_id' => $params['id']];
	}
	
	public function getPrivilege() {
		return 40;
	}
}