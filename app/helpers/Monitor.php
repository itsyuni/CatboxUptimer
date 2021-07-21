<?php

namespace Altum;

class Monitor {

    public static function check($monitor, $ping_servers = []) {

        /* Select a server to do the request */
        $ping_server_id = $monitor->ping_servers_ids[array_rand($monitor->ping_servers_ids)];

        /* Use default if the ping server is not accessible for some reason */
        if(!isset($ping_servers[$ping_server_id])) {
            $ping_server_id = 1;
        }
        $ping_server = $ping_servers[$ping_server_id];

        /* Error details */
        $error = null;

        /* Local request, native server */
        if($ping_server_id == 1) {
            switch($monitor->type) {

                /* Fsockopen */
                case 'port':

                    $ping = new \JJG\Ping($monitor->target);
                    $ping->setTimeout($monitor->settings->timeout_seconds);
                    $ping->setPort($monitor->port);
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

                    $ping = new \JJG\Ping($monitor->target);
                    $ping->setTimeout($monitor->settings->timeout_seconds);
                    $latency = $ping->ping(settings()->monitors_heartbeats->monitors_ping_method);

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
                    \Unirest\Request::timeout($monitor->settings->timeout_seconds);

                    try {

                        /* Set auth */
                        \Unirest\Request::auth($monitor->settings->request_basic_auth_username, $monitor->settings->request_basic_auth_password);

                        /* Make the request to the website */
                        $method = mb_strtolower($monitor->settings->request_method);

                        if(in_array($method, ['post', 'put', 'patch'])) {
                            $response = \Unirest\Request::{$method}($monitor->target, $monitor->settings->request_headers, $monitor->settings->request_body);
                        } else {
                            $response = \Unirest\Request::{$method}($monitor->target, $monitor->settings->request_headers);
                        }

                        /* Get info after the request */
                        $info = \Unirest\Request::getInfo();

                        /* Some needed variables */
                        $response_status_code = $info['http_code'];
                        $response_time = $info['total_time'] * 1000;

                        /* Check the response to see how we interpret the results */
                        $is_ok = 1;

                        if($response_status_code != $monitor->settings->response_status_code) {
                            $is_ok = 0;
                            $error = ['type' => 'response_status_code'];
                        }

                        if($monitor->settings->response_body && mb_strpos($response->raw_body, $monitor->settings->response_body) === false) {
                            $is_ok = 0;
                            $error = ['type' => 'response_body'];
                        }

                        foreach($monitor->settings->response_headers as $response_header) {
                            $response_header->name = mb_strtolower($response_header->name);

                            if(!isset($response->headers[$response_header->name]) || (isset($response->headers[$response_header->name]) && $response->headers[$response_header->name] != $response_header->value)) {
                                $is_ok = 0;
                                $error = ['type' => 'response_header'];
                                break;
                            }
                        }

                    } catch (\Exception $exception) {
                        $response_status_code = 0;
                        $response_time = 0;
                        $error = [
                            'type' => 'exception',
                            'code' => curl_errno(\Unirest\Request::getCurlHandle()),
                            'message' => curl_error(\Unirest\Request::getCurlHandle()),
                        ];

                        /*  :)  */
                        $is_ok = 0;
                    }

                break;
            }
        }

        /* Outside request, via a random ping server */
        else {

            /* Request the data from outside source */
            $response = \Unirest\Request::post($ping_server->url, [], [
                'ping_method' => settings()->monitors_heartbeats->monitors_ping_method,
                'type' => $monitor->type,
                'target' => $monitor->target,
                'port' => $monitor->port,
                'settings' => json_encode($monitor->settings)
            ]);

            /* Make sure we got the proper result back */
            if(!isset($response->body->is_ok)) {
                $is_ok = 0;
                $response_time = 0;
                $response_status_code = 0;
            } else {
                $is_ok = $response->body->is_ok;
                $response_time = $response->body->response_time;
                $response_status_code = $response->body->response_status_code;
                $error = $response->body->error;
            }

        }

        return [
            'ping_server_id' => $ping_server_id,
            'is_ok' => $is_ok,
            'response_time' => $response_time,
            'response_status_code' => $response_status_code,
            'error' => $error
        ];

    }

    public static function vars($monitor, $check) {
        /* Assuming, based on the check interval */
        $uptime_seconds = $check['is_ok'] ? $monitor->uptime_seconds + $monitor->settings->check_interval_seconds : $monitor->uptime_seconds;
        $downtime_seconds = !$check['is_ok'] ? $monitor->downtime_seconds + $monitor->settings->check_interval_seconds : $monitor->downtime_seconds;

        /* Recalculate uptime and downtime */
        $uptime = $uptime_seconds > 0 ? $uptime_seconds / ($uptime_seconds + $downtime_seconds) * 100 : 0;
        $downtime = 100 - $uptime;

        $total_ok_checks = $check['is_ok'] ? $monitor->total_ok_checks + 1 : $monitor->total_ok_checks;
        $total_not_ok_checks = !$check['is_ok'] ? $monitor->total_not_ok_checks + 1 : $monitor->total_not_ok_checks;
        $last_check_datetime = \Altum\Date::$date;
        $next_check_datetime = (new \DateTime())->modify('+' . $monitor->settings->check_interval_seconds . ' seconds')->format('Y-m-d H:i:s');
        $main_ok_datetime = !$monitor->is_ok && $check['is_ok'] ? \Altum\Date::$date : $monitor->main_ok_datetime;
        $last_ok_datetime = $check['is_ok'] ? \Altum\Date::$date : $monitor->last_ok_datetime;
        $main_not_ok_datetime = $monitor->is_ok && !$check['is_ok'] ? \Altum\Date::$date : $monitor->main_not_ok_datetime;
        $last_not_ok_datetime = !$check['is_ok'] ? \Altum\Date::$date : $monitor->last_not_ok_datetime;

        $average_response_time = $check['is_ok'] ? ($monitor->average_response_time + $check['response_time']) / ($monitor->total_ok_checks == 0 ? 1 : 2) : $monitor->average_response_time;

        return [
            'uptime_seconds' => $uptime_seconds,
            'downtime_seconds' => $downtime_seconds,
            'uptime' => $uptime,
            'downtime' => $downtime,
            'total_ok_checks' => $total_ok_checks,
            'total_not_ok_checks' => $total_not_ok_checks,
            'last_check_datetime' => $last_check_datetime,
            'next_check_datetime' => $next_check_datetime,
            'main_ok_datetime' => $main_ok_datetime,
            'last_ok_datetime' => $last_ok_datetime,
            'main_not_ok_datetime' => $main_not_ok_datetime,
            'last_not_ok_datetime' => $last_not_ok_datetime,
            'average_response_time' => $average_response_time,
        ];
    }

}
