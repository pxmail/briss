<?php

/*
 * (C) EZ Sport Ltd 2016
 */

/**
 * Description of lists
 *
 * @author xieyiming
 */
class lists extends fly\Action {

    public function exec(array $params = null) {
        $evaluation = new \evaluation();
        
        $result = $evaluation->listByDateRange($params['trainee_id'], $params['date_start'], $params['date_end']);
        
        return ['evaluation' => $result];
    }

    public function getFilter() {
        $required = [
            'trainee_id' => FILTER_SANITIZE_NUMBER_INT,
            'date_start' => FILTER_SANITIZE_STRING,
            'date_end' => FILTER_SANITIZE_STRING
        ];

        return [INPUT_GET, $required, NULL];
    }

    public function getPrivilege() {
        return 0;
    }

}
