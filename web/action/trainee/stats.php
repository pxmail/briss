<?php

use fly\Action;

/**
 * 统计训练人次
 * @author yangz
 *
 */
class stats extends Action {

    public function getFilter() {
        $required = array(
            'date' => FILTER_SANITIZE_STRING
        );

        return [INPUT_GET, $required, null];
    }

    public function exec(array $params = null) {
        $evaluation = new evaluation();
        $date = trim($params['date']);

        $finalResults = [];
        switch (strlen($date)) {
            case 10://日期2016-08-24所在周的每一天的训练人次
                // 填满1周时间，以免影响画图效果

                $sunday = date("Y-m-d", strtotime("$date Sunday"));
                for ($i = 6; $i > 0; $i--) {
                    $_date = date("Y-m-d", strtotime("$sunday - $i days"));
                    $finalResults[$_date] = 0;
                }
                $finalResults[$sunday] = 0;
                $result = $evaluation->getWeekVisit(array_keys($finalResults)[0], $sunday);

                break;
            case 7://日期2016-08所在月的每一天的训练人次

                $firstday = $date . "-01";
                $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));

                $finalResults[$firstday] = 0;
                for ($i = 2; $i < 10; $i++) {
                    $finalResults["$date-0{$i}"] = 0;
                }

                for ($i = 11; $i <= 31; $i++) {
                    $_date = "$date-$i";
                    if ($_date >= $lastday) {
                        break;
                    }
                    $finalResults[$_date] = 0;
                }

                $finalResults[$lastday] = 0;

                $result = $evaluation->getMonthVisit($firstday, $lastday);
                break;
            case 4://日期2016所在年的每个月的训练人次
                for ($i = 1; $i < 10; $i++) {
                    $finalResults["$date-0{$i}"] = 0;
                }
                $finalResults["$date-10"] = 0;
                $finalResults["$date-11"] = 0;
                $finalResults["$date-12"] = 0;

                $result = $evaluation->getYearVisit($date);
                break;
            default:
                $result = [];
                break;
        }

        foreach ($result as $dbRow) {
            $finalResults[$dbRow['date']] = $dbRow['counts'];
        }

        return ['stats' => $finalResults];
    }

    public function getPrivilege() {
        return 0;
    }

}
