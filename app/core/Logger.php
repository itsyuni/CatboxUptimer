<?php

namespace Altum;

class Logger {

    public static function users($user_id, $type, $public = 1) {

        $ip = get_ip();

        db()->insert('users_logs', [
            'user_id'   => $user_id,
            'type'      => $type,
            'date'      => \Altum\Date::$date,
            'ip'        => $ip,
            'public'    => $public
        ]);

    }

}
