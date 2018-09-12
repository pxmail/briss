<?php
use fly\Model;
/**
 * 教练--运动员关联表
 * @author yangz
 *
 */
class coach_trainee extends Model {
	var $coach_id, $trainee_id;
	
	public function create() {
		$this->_insert();
		return self::$pdo->lastInsertId();
	}
	
	public function createBatch($coach_id, $trainee_ids) {
		$q = array_fill(0, count($trainee_ids), "($coach_id,?)");
		$values = join(',', $q);
		$sql = 'INSERT INTO coach_trainee VALUES ' . $values;
		$stmt = self::$pdo->prepare($sql);
		$stmt->execute($trainee_ids);
		
		return $stmt->rowCount();
	}
	
	public function getTrainees($coach_id) {
		$stmt = self::$pdo->prepare('SELECT trainee_id FROM coach_trainee WHERE coach_id = ?');
		$stmt->bindValue(1, $coach_id);
		$stmt->execute();
		
		return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}
	
	public function deleteBatch($coach_id, $sport_id) {
		$sql = "DELETE FROM coach_trainee WHERE coach_id = ? AND trainee_id IN (SELECT id FROM trainee WHERE sport_id = ?)";
		$stmt = self::$pdo->prepare($sql);
		$stmt->bindValue(1, $coach_id);
		$stmt->bindValue(2, $sport_id);
		$stmt->execute();
		
		return $stmt->rowCount();
	}
	
	public function delete($coach_id, $trainee_id) {
		return $this->_delete(['coach_id' => $coach_id, 'trainee_id' => $trainee_id]);
	}
}