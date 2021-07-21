<?php

namespace Altum\Models;

class MonitorsLogs extends Model {

    public function get_monitor_logs_by_monitor_id_and_start_datetime_and_end_datetime($monitor_id, $start_datetime, $end_datetime) {

        /* Get all available monitor logs */
        $monitor_logs = [];

        /* Try to check if the status_page posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('s_monitor_logs?monitor_id=' . $monitor_id . '&start_datetime=' . md5($start_datetime) . '&end_datetime=' . md5($end_datetime));

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $monitor_logs_result = database()->query("SELECT * FROM `monitors_logs` WHERE `monitor_id` = {$monitor_id} AND (`datetime` BETWEEN '{$start_datetime}' AND '{$end_datetime}')");

            while($row = $monitor_logs_result->fetch_object()) $monitor_logs[] = $row;

            \Altum\Cache::$adapter->save(
                $cache_instance->set($monitor_logs)->expiresAfter(86400)->addTag('monitor_id=' . $monitor_id)
            );

        } else {

            /* Get cache */
            $monitor_logs = $cache_instance->get();

        }

        return $monitor_logs;

    }

}
