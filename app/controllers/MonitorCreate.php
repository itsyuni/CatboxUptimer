<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use MaxMind\Db\Reader;

class MonitorCreate extends Controller {

    public function index() {

        Authentication::guard();

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `monitors` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->monitors_limit != -1 && $total_rows >= $this->user->plan_settings->monitors_limit) {
            Alerts::add_info(language()->monitor->error_message->monitors_limit);
            redirect('monitors');
        }

        /* Get available projects servers */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Get available ping servers */
        $ping_servers = (new \Altum\Models\PingServers())->get_ping_servers();

        if(!empty($_POST)) {
            $_POST['name'] = trim(Database::clean_string($_POST['name']));
            $_POST['type'] = in_array($_POST['type'], ['website', 'ping', 'port']) ? Database::clean_string($_POST['type']) : 'website';
            $_POST['target'] = trim(Database::clean_string($_POST['target']));
            $_POST['port'] = isset($_POST['port']) ? (int) $_POST['port'] : 0;

            $_POST['check_interval_seconds'] = in_array($_POST['check_interval_seconds'], [60, 180, 300, 600, 1800, 3600]) ? (int) $_POST['check_interval_seconds'] : 600;
            $_POST['timeout_seconds'] = in_array($_POST['timeout_seconds'], [1, 2, 3, 5, 10, 30]) ? (int) $_POST['timeout_seconds'] : 5;

            $_POST['project_id'] = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null;
            $_POST['ping_servers_ids'] = array_map(
                function($ping_server_id) {
                    return (int) $ping_server_id;
                },
                array_filter($_POST['ping_servers_ids'], function($ping_server_id) use($ping_servers) {
                    return array_key_exists($ping_server_id, $ping_servers);
                })
            );
            $_POST['email_notifications_is_enabled'] = (int) (bool) $_POST['email_notifications_is_enabled'];
            $_POST['email_reports_is_enabled'] = (int) (bool) $_POST['email_reports_is_enabled'];
            $_POST['ssl_is_enabled'] = (int) (bool) $_POST['ssl_is_enabled'];
            $_POST['ssl_email_notifications_is_enabled'] = (int) $_POST['ssl_email_notifications_is_enabled'];

            /* Request */
            $_POST['request_method'] = in_array($_POST['request_method'], ['HEAD', 'GET', 'POST', 'PUT', 'PATCH']) ? Database::clean_string($_POST['request_method']) : 'HEAD';
            $_POST['request_body'] = trim(Database::clean_string($_POST['request_body']));
            $_POST['request_basic_auth_username'] = trim(Database::clean_string($_POST['request_basic_auth_username']));
            $_POST['request_basic_auth_password'] = trim(Database::clean_string($_POST['request_basic_auth_password']));

            if(!isset($_POST['request_header_name'])) {
                $_POST['request_header_name'] = [];
                $_POST['request_header_value'] = [];
            }

            $request_headers = [];
            foreach($_POST['request_header_name'] as $key => $value) {
                if(empty(trim($value))) continue;

                $request_headers[] = [
                    'name' => trim(Database::clean_string($value)),
                    'value' => trim(Database::clean_string($_POST['request_header_value'][$key])),
                ];
            }

            /* Response */
            $_POST['response_status_code'] = (int) $_POST['response_status_code'];
            $_POST['response_body'] = trim(Database::clean_string($_POST['response_body']));

            if(!isset($_POST['response_header_name'])) {
                $_POST['response_header_name'] = [];
                $_POST['response_header_value'] = [];
            }

            $response_headers = [];
            foreach($_POST['response_header_name'] as $key => $value) {
                if(empty(trim($value))) continue;

                $response_headers[] = [
                    'name' => trim(Database::clean_string($value)),
                    'value' => trim(Database::clean_string($_POST['response_header_value'][$key])),
                ];
            }

            /* Check for any errors */
            $required_fields = ['name', 'target'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]))) {
                    Alerts::add_field_error($field, language()->global->error_message->empty_field);
                }
            }

            if(!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }

            switch($_POST['type']) {
                case 'website':
                    $ip = '';

                    if(!filter_var($_POST['target'], FILTER_VALIDATE_URL)) {
                        Alerts::add_field_error('target', language()->monitor->error_message->invalid_target_url);
                    } else {
                        $host = parse_url($_POST['target'])['host'];
                        $ip = gethostbyname($host);
                    }
                break;

                case 'ping':
                    $ip = $_POST['target'];
                break;

                case 'port':
                    $ip = $_POST['target'];
                break;
            }

            /* Detect the location */
            try {
                $maxmind = (new Reader(APP_PATH . 'includes/GeoLite2-City.mmdb'))->get($ip);
            } catch(\Exception $exception) {
                if(in_array($_POST['type'], ['ping', 'port'])) {
                    Alerts::add_error($exception->getMessage());
                }
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $ping_servers_ids = json_encode($_POST['ping_servers_ids']);
                $settings = json_encode([
                    'check_interval_seconds' => $_POST['check_interval_seconds'],
                    'timeout_seconds' => $_POST['timeout_seconds'],
                    'ssl_is_enabled' => $_POST['ssl_is_enabled'],
                    'request_method' => $_POST['request_method'],
                    'request_body' => $_POST['request_body'],
                    'request_basic_auth_username' => $_POST['request_basic_auth_username'],
                    'request_basic_auth_password' => $_POST['request_basic_auth_password'],
                    'request_headers' => $request_headers,
                    'response_status_code' => $_POST['response_status_code'],
                    'response_body' => $_POST['response_body'],
                    'response_headers' => $response_headers,
                ]);

                /* Detect the location */
                $country_code = isset($maxmind) && isset($maxmind['country']) ? $maxmind['country']['iso_code'] : null;
                $city_name = isset($maxmind) && isset($maxmind['city']) ? $maxmind['city']['names']['en'] : null;
                $continent_name = isset($maxmind) && isset($maxmind['continent']) ? $maxmind['continent']['names']['en'] : null;

                $details = json_encode([
                    'country_code' => $country_code,
                    'city_name' => $city_name,
                    'continent_name' => $continent_name
                ]);

                $notifications = json_encode([
                    'email_is_enabled' => $_POST['email_notifications_is_enabled'],
                    'ssl_email_is_enabled' => $_POST['ssl_email_notifications_is_enabled'],
                    'webhook' => Database::clean_string($_POST['webhook_notifications']),
                    'slack' => Database::clean_string($_POST['slack_notifications']),
                    'twilio' => Database::clean_string($_POST['twilio_notifications']) ?? null,
                ]);

                /* Prepare the statement and execute query */
                $monitor_id = db()->insert('monitors', [
                    'project_id' => $_POST['project_id'],
                    'user_id' => $this->user->user_id,
                    'name' => $_POST['name'],
                    'type' => $_POST['type'],
                    'target' => $_POST['target'],
                    'port' => $_POST['port'],
                    'ping_servers_ids' => $ping_servers_ids,
                    'settings' => $settings,
                    'details' => $details,
                    'notifications' => $notifications,
                    'email_reports_is_enabled' => $_POST['email_reports_is_enabled'],
                    'email_reports_last_datetime' => \Altum\Date::$date,
                    'next_check_datetime' => \Altum\Date::$date,
                    'datetime' => \Altum\Date::$date,
                ]);

                /* Set a nice success message */
                Alerts::add_success(language()->monitor_create->success_message);

                redirect('monitor/' . $monitor_id);
            }

        }

        /* Set default values */
        if(!isset($request_headers)) {
            $request_headers = [];
            $request_headers[] = [
                'name' => '',
                'value' => ''
            ];
        }

        if(!isset($response_headers)) {
            $response_headers = [];
            $response_headers[] = [
                'name' => '',
                'value' => ''
            ];
        }

        $values = [
            'name' => $_POST['name'] ?? '',
            'type' => $_POST['type'] ?? '',
            'target' => $_POST['target'] ?? '',
            'port' => $_POST['port'] ?? '',
            'check_interval_seconds' => $_POST['check_interval_seconds'] ?? 600,
            'timeout_seconds' => $_POST['timeout_seconds'] ?? 5,
            'project_id' => $_POST['project_id'] ?? '',
            'ping_servers_ids' => $_POST['ping_servers_ids'] ?? array_keys($ping_servers),
            'email_notifications_is_enabled' => $_POST['email_notifications_is_enabled'] ?? 1,
            'email_reports_is_enabled' => $_POST['email_reports_is_enabled'] ?? 1,
            'webhook_notifications' => $_POST['webhook_notifications'] ?? null,
            'slack_notifications' => $_POST['slack_notifications'] ?? null,
            'twilio_notifications' => $_POST['twilio_notifications'] ?? null,

            'request_method' => $_POST['request_method'] ?? 'GET',
            'request_body' => $_POST['request_body'] ?? '',
            'request_basic_auth_username' => $_POST['request_basic_auth_username'] ?? '',
            'request_basic_auth_password' => $_POST['request_basic_auth_password'] ?? '',
            'request_headers' => json_decode(json_encode($request_headers)),
            'response_status_code' => $_POST['response_status_code'] ?? 200,
            'response_body' => $_POST['response_body'] ?? '',
            'response_headers' => json_decode(json_encode($response_headers)),
        ];

        /* Prepare the View */
        $data = [
            'ping_servers' => $ping_servers,
            'projects' => $projects,
            'values' => $values
        ];

        $view = new \Altum\Views\View('monitor-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
