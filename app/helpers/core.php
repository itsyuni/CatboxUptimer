<?php

function settings() {
    return \Altum\Settings::$settings;
}

function db() {
    return \Altum\Database\Database::$db;
}

function database() {
    return \Altum\Database\Database::$database;
}

function language($language = null) {
    return \Altum\Language::get($language);
}
