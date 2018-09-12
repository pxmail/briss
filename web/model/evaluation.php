<?php

use fly\Model;

/**
 * 训练状态评估表
 * @author yangz
 *
 */
class evaluation extends Model {

    var $id, $trainee_id, $create_date, $create_time, $self_rating, $desire,
            $sleep, $appetite, $rpe_before, $rpe_after, $hrv_before, $hrv_after, $hrv_cold,
            $omega_wave, $morning_pulse, $attitude, $quality, $comment, $pain;

    public function create() {
        $this->_insert();
        return self::$pdo->lastInsertId();
    }

    public function get($id) {
        $stmt = self::$pdo->prepare('SELECT * FROM evaluation WHERE id = ?');
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_INTO, $this);
        return $stmt->fetch();
    }

    public function getByTrainee($trainee_id, $create_date) {
        $stmt = self::$pdo->prepare('SELECT * FROM evaluation WHERE trainee_id = ? AND create_date = ?');
        $stmt->bindValue(1, $trainee_id);
        $stmt->bindValue(2, $create_date);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_INTO, $this);
        return $stmt->fetch();
    }

    public function listByTrainee($trainee_id) {
        $stmt = self::$pdo->prepare('SELECT DISTINCT left(create_date, 7) FROM evaluation WHERE trainee_id = ? ORDER BY id DESC');
        $stmt->bindValue(1, $trainee_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function listByDate($trainee_id, $year_month) {
        $stmt = self::$pdo->prepare('SELECT right(create_date, 2) FROM evaluation WHERE trainee_id = ? AND left(create_date, 7) = ? ORDER BY id DESC');
        $stmt->bindValue(1, $trainee_id);
        $stmt->bindValue(2, $year_month);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function update($trainee_id, $create_date) {
        return $this->_update([
                    'trainee_id' => $trainee_id,
                    'create_date' => $create_date
        ]);
    }

    public function listByDateRange($trainee_id, $start_date, $end_date) {
        $stmt = self::$pdo->prepare('SELECT * FROM evaluation WHERE trainee_id = ? AND (create_date BETWEEN ? AND ?) ORDER BY id DESC');
        $stmt->bindValue(1, $trainee_id);
        $stmt->bindValue(2, $start_date);
        $stmt->bindValue(3, $end_date);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getMonthVisit($firstday, $lastday) {
    	$stmt = self::$pdo->prepare('SELECT create_date AS date, COUNT(1) as counts FROM evaluation WHERE create_date BETWEEN ? AND ? GROUP BY date');
    	$stmt->bindValue(1, $firstday);
    	$stmt->bindValue(2, $lastday);
    	$stmt->execute();
    	return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getWeekVisit($monday, $sunday) {
    	$stmt = self::$pdo->prepare('SELECT create_date AS date, COUNT(1) as counts FROM evaluation WHERE create_date BETWEEN ? AND ? GROUP BY date');
    	$stmt->bindValue(1, $monday);
    	$stmt->bindValue(2, $sunday);
    	$stmt->execute();
    	return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getYearVisit($year) {
        $stmt = self::$pdo->prepare('SELECT DISTINCT(LEFT(create_date, 7)) AS date, COUNT(1) AS counts FROM evaluation GROUP BY date HAVING LEFT(date, 4) = ?');
        $stmt->bindValue(1, $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
