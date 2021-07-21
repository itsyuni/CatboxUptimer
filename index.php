<?php

/* Enabling debug mode is only for debugging / development purposes. */
define('DEBUG', 0);

/* Enabling mysql debug mode is only for debugging / development purposes. */
define('MYSQL_DEBUG', 0);

require_once realpath(__DIR__) . '/app/init.php';

$App = new Altum\App();
