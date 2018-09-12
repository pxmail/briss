<?php
use fly\Model;

/**
 * 角色表
 * @author yangz
 *
 */
class role extends Model {
	var $id, $name, $privilege;
	
	// role_id预设值
	const TOUCH_CLIENT = '1'; // 触屏端用户
	const SUPER_COACH = '2'; // 超级教练员，带添加教练员、审核学员功能
	const COMMON_COACH = '3'; // 普通教练员
	const SPORT_CLIENT = '4'; // 运动员用户
	
	public function create() {
		$this->_insert();
		return self::$pdo->lastInsertId();
	}
	
	public function get($id) {
		$stmt = self::$pdo->prepare('SELECT * FROM role WHERE id = ?');
		$stmt->bindValue(1, $id);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_INTO, $this);
		return $stmt->fetch();
	}
	
}