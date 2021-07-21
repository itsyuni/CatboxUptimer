<?php

namespace Altum;

class Settings {
    public static $settings = null;

    public static function initialize($settings) {

        self::$settings = $settings;

    }

    public static function get() {
        return self::$settings;
    }
}
