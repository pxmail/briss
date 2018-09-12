<?php

use fly\Action;

/**
 * 获取某天的训练评估数据
 * @author yangz
 *
 */
class get_by_date extends Action {

    public function getFilter() {
        $optional = [
            'date' => FILTER_SANITIZE_STRING,
            'trainee_id' => FILTER_SANITIZE_NUMBER_INT
        ];

        return [INPUT_GET, null, $optional];
    }

    public function exec(array $params = null) {
        $evaluation = new evaluation();

        if (empty($params['trainee_id'])) {
            $params['trainee_id'] = $this->user->uid;
        }
        if (empty($params['date'])) {
            $params['date'] = date('Y-m-d');
        }
        $result = $evaluation->getByTrainee($params['trainee_id'], $params['date']);

        if (empty($result)) {
//			return 2031;
            $result = false;
        }
        return ['evaluation' => $result];
    }

    public function getPrivilege() {
        return 0;
    }

}
