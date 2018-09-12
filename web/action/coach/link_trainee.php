<?php
use fly\Action;
/**
 * 教练添加自己的运动员
 * @author yangz
 *
 */
class link_trainee extends Action {
	public function getFilter() {
		$optional = array(
			'sport_id' => FILTER_SANITIZE_NUMBER_INT,
			'trainee_id' => FILTER_SANITIZE_NUMBER_INT
		);
		
		return [INPUT_POST, null, $optional];
	}
	
	public function exec(array $params = null) {
		$coach_sport = new coach_sport();
		$coach_trainee = new coach_trainee();
		$trainee = new trainee();
		$sport = new sport();
		
		//如果传递sport_id参数，则将该运动项目分类下的所有运动员与当前教练关联
		if (!empty($params['sport_id'])) {
			$sport->get($params['sport_id']);
			if (empty($sport->id)) {
				return 3042;
			}
			
			$trainee_ids = $trainee->getBySport($params['sport_id']);
			
			//判断该运动项目下是否有运动员,如没有则不建立关联关系
			if (count($trainee_ids)) {
				//创建教练与运动员之间的关联关系
				$coach_trainee->createBatch($this->user->uid, $trainee_ids);
					
				//创建教练与运动项目之间的关联关系
				$coach_sport->coach_id = $this->user->uid;
				$coach_sport->sport_id = $params['sport_id'];
				$coach_sport->create();
			}
		}
		
		if (!empty($params['trainee_id'])) {
			$trainee->get($params['trainee_id']);
			if (empty($trainee->id)) {
				return 2080;
			}
			
			//创建教练与该运动员之间的关联关系
			$coach_sport->coach_id = $this->user->uid;
			$coach_sport->sport_id = $trainee->sport_id;
			$coach_sport->create();
			//创建教练与该运动员运动项目的关联关系
			$coach_trainee->coach_id = $this->user->uid;
			$coach_trainee->trainee_id = $params['trainee_id'];
			$coach_trainee->create();
		}
		
		return ['success' => true];
	}
	
	public function getPrivilege() {
		return 20;
	}
}