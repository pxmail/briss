<?php
use fly\Model;

/**
 * 运动员表
 * @author yangz
 *
 */
class trainee extends Model {
	var $id, $name, $password, $role_id, $gender, $dob, $height, $weight,
	$mobile, $nationality, $sport, $sport_id, $team, $base_name, $base_id, $dot, $grade, $last_train_date,
	$last_status, $avatar, $status, $native, $day;
	// 上次训练状态
	const TRAINING_STATUS_SAVED = '0'; //保留
	const TRAINING_STATUS_STARTED = '1'; //训练已开始
	const TRAINING_STATUS_COMPLETED = '2'; //训练已完成
	
	//用户状态
	const STATUS_NOT_REVIEWED = '0';
	const STATUS_NORMAL = '1';
	const STATUS_LOCKED = '2';
	const STATUS_DELETED = '3';
	const STATUS_REJECTED = '4';
	
	//默认密码
	const DEFAULT_PASSWORD = 'ezsport';
	
	public function create() {
		$this->_insert();
		return self::$pdo->lastInsertId();
	}
	
	public function get($id) {
		$stmt = self::$pdo->prepare('SELECT * FROM trainee WHERE id = ?');
		$stmt->bindValue(1, $id);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_INTO, $this);
		return $stmt->fetch();
	}
	
	public function getByMobile($mobile) {
		$stmt = self::$pdo->prepare('SELECT * FROM trainee WHERE mobile = ?');
		$stmt->bindValue(1, $mobile);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_INTO, $this);
		return $stmt->fetch();
	}
	
	public function getBySport($sport_id) {
		$stmt = self::$pdo->prepare('SELECT id FROM trainee WHERE sport_id = ?');
		$stmt->bindValue(1, $sport_id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}
	
	public function getByName($name) {
		$stmt = self::$pdo->prepare('SELECT * FROM trainee WHERE name = ?');
		$stmt->bindValue(1, $name);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getByBase($base_id) {
		$stmt = self::$pdo->prepare('SELECT name, base_id, base_name FROM trainee WHERE base_id = ?');
		$stmt->bindValue(1, $base_id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function listByMobile($mobile) {
		$stmt = self::$pdo->prepare('SELECT id, name, dob, mobile, gender, team, sport, avatar, native, base_name, last_train_date, last_status, (last_train_date = current_date AND last_status = 1) AS is_active FROM trainee WHERE mobile LIKE ?');
		$stmt->bindValue(1, $mobile.'%');
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function listMineByMobile($mobile, $coach_id) {
		$stmt = self::$pdo->prepare('SELECT a.id, a.name, a.dob, a.mobile, a.gender, a.team, a.sport, a.avatar, a.native, a.base_name, a.last_train_date, a.last_status,
				(a.last_train_date = current_date AND a.last_status = 1) AS is_active FROM trainee a LEFT JOIN coach_trainee b ON a.id = b.trainee_id WHERE a.mobile LIKE ? AND b.coach_id = ?');
		$stmt->bindValue(1, $mobile.'%');
		$stmt->bindValue(2, $coach_id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * 查询所有已经入场的运动员
	 * @param unknown $last_train_date
	 * @param unknown $last_status
	 */
	public function listByDate($last_train_date, $last_status) {
		$stmt = self::$pdo->prepare('SELECT id, name, dob, mobile, gender, team, sport, avatar, native, day, base_name FROM trainee WHERE last_train_date = ? AND last_status = ? AND status = 1');
		$stmt->bindValue(1, $last_train_date);
		$stmt->bindValue(2, $last_status);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * 查询属于当前教练的所有入场运动员
	 */
	public function listMyActiveTrainee($coach_id, $last_train_date, $last_status) {
		$sql = 'SELECT id, name, dob, mobile, gender, team, sport, avatar, native, day, base_name FROM trainee 
				WHERE last_train_date = ? AND last_status = ? AND status = 1 AND id IN (SELECT trainee_id FROM coach_trainee WHERE coach_id = ?)';
		$stmt = self::$pdo->prepare($sql);
		$stmt->bindValue(1, $last_train_date);
		$stmt->bindValue(2, $last_status);
		$stmt->bindValue(3, $coach_id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function lists($keyword) {
		$stmt = self::$pdo->prepare('SELECT id, name, dob, mobile, gender, team, sport, avatar, native, base_name, day, last_train_date, last_status, (last_train_date = current_date AND last_status = 1) AS is_active FROM trainee 
				WHERE name Like :keyword OR base_name LIKE :keyword OR sport LIKE :keyword OR team LIKE :keyword');
		$stmt->bindValue('keyword', '%'.$keyword.'%');
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function listMine($keyword, $coach_id) {
		$stmt = self::$pdo->prepare('SELECT a.id, a.name, a.dob, a.mobile, a.gender, a.team, a.sport, a.avatar, a.native, a.base_name, a.day, a.last_train_date, a.last_status, (a.last_train_date = current_date AND a.last_status = 1) AS is_active FROM trainee a
				LEFT JOIN coach_trainee b ON a.id = b.trainee_id WHERE b.coach_id = :coach_id AND (a.name Like :keyword OR a.base_name LIKE :keyword OR a.sport LIKE :keyword OR a.team LIKE :keyword)');
		$stmt->bindValue('keyword', '%'.$keyword.'%');
		$stmt->bindValue('coach_id', $coach_id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function listCount($status) {
		$stmt = self::$pdo->prepare('SELECT count(1) FROM trainee WHERE status = ?');
		$stmt->bindValue(1, $status);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	
	/**
	 * 查询所有的运动员信息
	 */
	public function listAll() {
		$stmt = self::$pdo->prepare('SELECT id, name, dob, mobile, team, sport_id, sport, avatar, status, day FROM trainee WHERE status = 1 ORDER BY id DESC');
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * 查询属于该教练的所有运动员
	 * @param unknown $coach_id
	 */
	public function listMyTrainee($coach_id) {
		$sql = "SELECT id, name, dob, mobile, team, sport_id, sport, avatar, status, day FROM trainee WHERE id IN (SELECT trainee_id FROM coach_trainee WHERE coach_id = ?) ORDER BY id DESC";
		$stmt = self::$pdo->prepare($sql);
		$stmt->bindValue(1, $coach_id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * 查询待审核的所有运动员
	 */
	public function listPending() {
		$sql = "SELECT id, name, dob, mobile, team, sport_id, sport, avatar, status, day FROM trainee WHERE status = 0 ORDER BY id DESC";
		$stmt = self::$pdo->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function update($id) {
		return $this->_update(['id' => $id]);
	}
	
	public function delete($id) {
		return $this->_delete(['id' => $id]);
	}
        
	/**
	 * 列出近期活跃的用户，当天已经入场（包括训练完成）的运动员不在内
	 */
    public function listRecent() {
        $stmt = self::$pdo->prepare('SELECT id, name, team, sport, avatar, base_name, day, last_train_date, last_status FROM trainee '
              . 'WHERE last_train_date != CURRENT_DATE ORDER BY last_train_date DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * 列出属于当前教练的近期活跃的用户，当天已经入场（包括训练完成）的运动员不在内
     */
    public function listMyRecent($coach_id) {
    	$stmt = self::$pdo->prepare('SELECT id, name, team, sport, avatar, base_name, day, last_train_date, last_status FROM trainee '
    			. 'WHERE last_train_date != CURRENT_DATE AND id IN (SELECT trainee_id FROM coach_trainee WHERE coach_id = ?) ORDER BY last_train_date DESC');
    	$stmt->bindValue(1, $coach_id);
    	$stmt->execute();
    	return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
