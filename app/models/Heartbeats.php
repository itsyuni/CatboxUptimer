<?php

namespace Altum\Models;

use Altum\Database\Database;

class Heartbeats extends Model {

    public function get_heartbeat_by_code($code) {

        /* Get the heartbeat */
        $heartbeat = null;

        /* Try to check if the status_page posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('heartbeat?code=' . $code);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $heartbeat = database()->query("SELECT * FROM `heartbeats` WHERE `code` = '{$code}'")->fetch_object() ?? null;

            if($heartbeat) {
                \Altum\Cache::$adapter->save(
                    $cache_instance->set($heartbeat)->expiresAfter(86400)->addTag('heartbeat_id=' . $heartbeat->heartbeat_id)
                );
            }

        } else {

            /* Get cache */
            $heartbeat = $cache_instance->get();

        }

        return $heartbeat;

    }


}
