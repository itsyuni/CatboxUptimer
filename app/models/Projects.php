<?php

namespace Altum\Models;

use Altum\Database\Database;

class Projects extends Model {

    public function get_projects_by_user_id($user_id) {

        /* Get the user projects */
        $projects = [];

        /* Try to check if the user posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('projects?user_id=' . $user_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $projects_result = database()->query("SELECT * FROM `projects` WHERE `user_id` = {$user_id}");
            while($row = $projects_result->fetch_object()) $projects[$row->project_id] = $row;

            \Altum\Cache::$adapter->save(
                $cache_instance->set($projects)->expiresAfter(86400)->addTag('user_id=' . $user_id)
            );

        } else {

            /* Get cache */
            $projects = $cache_instance->get();

        }

        return $projects;

    }

}
