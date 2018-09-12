<?php
use fly\Model;
/**
 * 教练--运动项目关联表
 * @author yangz
 *
 */
class coach_sport extends Model {
	var $sport_id, $coach_id;
	
	public function create() {
		$this->_insert();
		return self::$pdo->lastInsertId();
	}
	
	public function delete($sport_id, $coach_id) {
		return $this->_delete(['sport_id' => $sport_id, 'coach_id' => $coach_id]);
	}
}