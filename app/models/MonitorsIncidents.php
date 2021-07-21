<?php

namespace Altum\Models;

use Altum\Database\Database;

class MonitorsIncidents extends Model {

    public function get_monitor_incidents_by_monitor_id_and_start_datetime_and_end_datetime($monitor_id, $start_datetime, $end_datetime) {

        /* Get all available monitor logs */
        $monitor_incidents = [];

        /* Try to check if the status_page posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('s_monitor_incidents?monitor_id=' . $monitor_id . '&start_datetime=' . md5($start_datetime) . '&end_datetime=' . md5($end_datetime));

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $monitor_incidents_result = database()->query("SELECT * FROM `incidents` WHERE `monitor_id` = {$monitor_id} AND `start_datetime` >= '{$start_datetime}' AND `end_datetime` <= '{$end_datetime}'");

            while($row = $monitor_incidents_result->fetch_object()) $monitor_incidents[] = $row;

            \Altum\Cache::$adapter->save(
                $cache_instance->set($monitor_incidents)->expiresAfter(86400)->addTag('monitor_id=' . $monitor_id)
            );

        } else {

            /* Get cache */
            $monitor_incidents = $cache_instance->get();

        }

        return $monitor_incidents;

    }

}
