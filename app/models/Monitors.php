<?php

namespace Altum\Models;

class Monitors extends Model {

    public function get_monitor_by_monitor_id($monitor_id) {

        /* Get the monitor */
        $monitor = null;

        /* Try to check if the status_page posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('s_monitor?monitor_id=' . $monitor_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $monitor = database()->query("SELECT * FROM `monitors` WHERE `monitor_id` = {$monitor_id}")->fetch_object() ?? null;

            \Altum\Cache::$adapter->save(
                $cache_instance->set($monitor)->expiresAfter(86400)->addTag('monitor_id=' . $monitor_id)
            );

        } else {

            /* Get cache */
            $monitor = $cache_instance->get();

        }

        return $monitor;

    }

    public function get_monitors_by_monitors_ids($monitors_ids) {

        if(empty($monitors_ids)) return [];

        $monitors_ids_plain = implode(',', $monitors_ids);

        /* Get the status_page posts */
        $monitors = [];

        /* Try to check if the status_page posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('s_monitors?monitors_ids=' . $monitors_ids_plain);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $monitors_result = database()->query("
                SELECT 
                    *
                FROM 
                    `monitors` 
                WHERE 
                    `monitor_id` IN ({$monitors_ids_plain})
                    AND `is_enabled` = 1
            ");
            while($row = $monitors_result->fetch_object()) $monitors[$row->monitor_id] = $row;

            /* Properly tag the cache */
            $cache_instance->set($monitors)->expiresAfter(86400);

            foreach($monitors_ids as $monitor_id) {
                $cache_instance->addTag('monitor_id=' . $monitor_id);
            }

            if(count($monitors)) {
                \Altum\Cache::$adapter->save($cache_instance);
            }

        } else {

            /* Get cache */
            $monitors = $cache_instance->get();

        }

        return $monitors;

    }

    public function get_monitors_by_user_id($user_id) {

        /* Get the status_page posts */
        $monitors = [];

        /* Try to check if the status_page posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('s_monitors?user_id=' . $user_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $monitors_result = database()->query("
                SELECT 
                    *
                FROM 
                    `monitors` 
                WHERE 
                    `user_id` = {$user_id}
                    AND `is_enabled` = 1
            ");
            while($row = $monitors_result->fetch_object()) $monitors[$row->monitor_id] = $row;

            /* Properly tag the cache */
            $cache_instance->set($monitors)->expiresAfter(86400);

            foreach($monitors as $monitor) {
                $cache_instance->addTag('monitor_id=' . $monitor->monitor_id);
            }

            if(count($monitors)) {
                \Altum\Cache::$adapter->save($cache_instance);
            }

        } else {

            /* Get cache */
            $monitors = $cache_instance->get();

        }

        return $monitors;

    }

}
