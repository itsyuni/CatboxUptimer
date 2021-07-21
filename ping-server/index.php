<?php

define('ROOT_PATH', realpath(__DIR__) . '/');

/* Autoload for vendor */
require_once ROOT_PATH . 'vendor/autoload.php';

/* Potential error checks */
if(empty($_POST)) {
    die();
}

$required = [
    'type',
    'target',
    'port',
    'settings'
];

foreach($required as $required_field) {
    if(!isset($_POST[$required_field])) {
        die();
    }
}

/* Define some needed vars */
$_POST['settings'] = json_decode($_POST['settings']);

switch($_POST['type']) {

    /* Fsockopen */
    case 'port':

        $ping = new \JJG\Ping($_POST['target']);
        $ping->setTimeout($_POST['settings']->timeout_seconds);
        $ping->setPort($_POST['port']);
        $latency = $ping->ping('fsockopen');

        if($latency !== false) {

            $response_status_code = 0;
            $response_time = $latency;

            /*  :)  */
            $is_ok = 1;
        } else {

            $response_status_code = 0;
            $response_time = 0;

            /*  :)  */
            $is_ok = 0;

        }

        break;

    /* Ping check */
    case 'ping':

        $ping = new \JJG\Ping($_POST['target']);
        $ping->setTimeout($_POST['settings']->timeout_seconds);
        $latency = $ping->ping($_POST['ping_method']);

        if($latency !== false) {

            $response_status_code = 0;
            $response_time = $latency;

            /*  :)  */
            $is_ok = 1;
        } else {

            $response_status_code = 0;
            $response_time = 0;

            /*  :)  */
            $is_ok = 0;

        }

        break;

    /* Websites check */
    case 'website':

        /* Set timeout */
        \Unirest\Request::timeout($_POST['settings']->timeout_seconds);

        try {

            /* Set auth */
            \Unirest\Request::auth($_POST['settings']->request_basic_auth_username, $_POST['settings']->request_basic_auth_password);

            /* Make the request to the website */
            $method = strtolower($_POST['settings']->request_method);

            if(in_array($method, ['post', 'put', 'patch'])) {
                $response = \Unirest\Request::{$method}($_POST['target'], $_POST['settings']->request_headers, $_POST['settings']->request_body);
            } else {
                $response = \Unirest\Request::{$method}($_POST['target'], $_POST['settings']->request_headers);
            }

            /* Get info after the request */
            $info = \Unirest\Request::getInfo();

            /* Some needed variables */
            $response_status_code = $info['http_code'];
            $response_time = $info['total_time'] * 1000;

            /* Check the response to see how we interpret the results */
            $is_ok = 1;

            if($response_status_code != $_POST['settings']->response_status_code) {
                $is_ok = 0;
            }

            if($_POST['settings']->response_body && strpos($response->raw_body, $_POST['settings']->response_body) === false) {
                $is_ok = 0;
            }

            foreach($_POST['settings']->response_headers as $response_header) {
                $response_header->name = strtolower($response_header->name);

                if(!isset($response->headers[$response_header->name]) || (isset($response->headers[$response_header->name]) && $response->headers[$response_header->name] != $response_header->value)) {
                    $is_ok = 0;
                    break;
                }
            }

        } catch (\Exception $exception) {

            $response_status_code = 0;
            $response_time = 0;

            /*  :)  */
            $is_ok = 0;

        }

        break;
}

/* Prepare the answer */
$response = [
    'is_ok' => $is_ok,
    'response_time' => $response_time,
    'response_status_code' => $response_status_code
];

echo json_encode($response);

die();
