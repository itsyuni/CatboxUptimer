<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Date;
use Altum\Meta;
use Altum\Middlewares\Csrf;
use Altum\Models\User;
use Altum\Routing\Router;
use Altum\Title;
use MaxMind\Db\Reader;

class StatusPage extends Controller {
    public $status_page = null;
    public $status_page_user = null;
    public $has_access = null;

    public function index() {

        $this->init();

        /* Check if the password form is submitted */
        if(!$this->has_access && !empty($_POST)) {

            /* Check for any errors */
            if(!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }

            if(!password_verify($_POST['password'], $this->status_page->password)) {
                Alerts::add_field_error('password', language()->s_status_page->password->error_message);
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Set a cookie */
                setcookie('status_page_password_' . $this->status_page->status_page_id, $this->status_page->password, time()+60*60*24*30);

                header('Location: ' . $this->status_page->full_url); die();

            }

        }

        /* Display the password form */
        if(!$this->has_access) {

            /* Set a custom title */
            Title::set(language()->s_status_page->password->title);

            /* Main View */
            $data = [
                'status_page' => $this->status_page,
            ];

            $view = new \Altum\Views\View('s/status-page/' . $this->status_page->theme . '/password', (array) $this);

        }

        /* No password or access granted */
        else {

            $this->create_statistics($this->status_page->status_page_id);

            /* Prepare date selector stuff */
            $start_date = isset($_GET['start_date']) ? Database::clean_string($_GET['start_date']) : Date::get('', 4);
            $end_date = isset($_GET['end_date']) ? Database::clean_string($_GET['end_date']) : Date::get('', 4);
            $date = \Altum\Date::get_start_end_dates($start_date, $end_date);

            /* Get all the available monitors */
            $monitors = (new \Altum\Models\Monitors())->get_monitors_by_monitors_ids($this->status_page->monitors_ids);

            /* Detect the overall status */
            $monitors_status = 1;

            /* Earliest datetime available */
            $status_page_earliest_datetime_available = (new \DateTime());

            foreach($monitors as $monitor) {

                if(!$monitor->is_ok) {
                    $monitors_status = 0;
                }

                if((new \DateTime($monitor->datetime)) < $status_page_earliest_datetime_available) {
                    $status_page_earliest_datetime_available = $monitor->datetime;
                }

                /* Get the needed logs for each monitor */
                $total_ok_checks = 0;
                $total_not_ok_checks = 0;
                $total_response_time = 0;

                /* Get logs */
                $monitor->monitor_logs = (new \Altum\Models\MonitorsLogs())->get_monitor_logs_by_monitor_id_and_start_datetime_and_end_datetime($monitor->monitor_id, $date->start_date_query, $date->end_date_query);

                $monitor->monitor_logs_chart = [];

                foreach($monitor->monitor_logs as $monitor_log) {

                    /* Stats for chart */
                    $label = $start_date == $end_date ? \Altum\Date::get($monitor_log->datetime, 3) : \Altum\Date::get($monitor_log->datetime, 1);

                    $monitor->monitor_logs_chart[$label] = [
                        'is_ok' => $monitor_log->is_ok,
                        'response_time' => $monitor_log->response_time,
                    ];

                    /* Complete stats */
                    $total_ok_checks += (int) $monitor_log->is_ok;
                    $total_not_ok_checks += (int) !$monitor_log->is_ok;
                    $total_response_time += $monitor_log->response_time;
                }

                $monitor->monitor_logs_chart = get_chart_data($monitor->monitor_logs_chart);

                /* calculate some data */
                $monitor->monitor_logs_data = [
                    'total_monitor_logs' => count($monitor->monitor_logs),
                    'uptime' => $total_ok_checks > 0 ? $total_ok_checks / ($total_ok_checks + $total_not_ok_checks) * 100 : 0,
                    'downtime' => 100 - $monitor->uptime,
                    'total_not_ok_checks' => $total_not_ok_checks
                ];
                $monitor->monitor_logs_data['average_response_time'] = $monitor->monitor_logs_data['total_monitor_logs'] > 0 ? $total_response_time / $monitor->monitor_logs_data['total_monitor_logs'] : 0;
            }

            /* Set a custom title */
            Title::set($this->status_page->name);

            /* Set the meta tags */
            Meta::set_description(string_truncate($this->status_page->description, 200));
            Meta::set_social_url($this->status_page->full_url);
            Meta::set_social_title($this->status_page->name);
            Meta::set_social_description(string_truncate($this->status_page->description, 200));

            /* Prepare the header */
            $view = new \Altum\Views\View('s/partials/header', (array) $this);
            $this->add_view_content('header', $view->run(['status_page' => $this->status_page]));

            /* Main View */
            $data = [
                'status_page' => $this->status_page,
                'status_page_user' => $this->status_page_user,
                'monitors' => $monitors,
                'monitors_status' => $monitors_status,
                'date' => $date,
                'status_page_earliest_datetime_available' => $status_page_earliest_datetime_available
            ];

            $view = new \Altum\Views\View('s/status-page/' . $this->status_page->theme . '/index', (array) $this);
        }

        $this->add_view_content('content', $view->run($data));
    }

    public function init() {

        /* Check against potential custom domains */
        if(isset(Router::$data['domain'])) {

            /* Check if custom domain has 1 status_page or multiple */
            if(Router::$data['domain']->status_page_id) {

                $status_page = $this->status_page = (new \Altum\Models\StatusPage())->get_status_page_by_status_page_id(Router::$data['domain']->status_page_id);

                /* Determine the status_page base url */
                $status_page->full_url = Router::$data['domain']->scheme . Router::$data['domain']->host . '/';

            } else {
                /* Get the Status page details */
                $url = isset($this->params[0]) ? Database::clean_string($this->params[0]) : null;

                $status_page = $this->status_page = (new \Altum\Models\StatusPage())->get_status_page_by_url_and_domain_id($url, Router::$data['domain']->domain_id);

                if($status_page) {
                    /* Determine the status_page base url */
                    $status_page->full_url = Router::$data['domain']->scheme . Router::$data['domain']->host . '/' . $status_page->url . '/';
                }
            }

            /* Redirect if the status page doesn't exit or is not active */
            if(!$status_page || ($status_page && !$status_page->is_enabled)) {

                /* Redirect by custom not found page if possible */
                if(Router::$data['domain']->custom_not_found_url) {
                    header('Location: ' . Router::$data['domain']->custom_not_found_url);
                    die();
                }

                /* Redirect to the main homepage */
                else {
                    redirect();
                }
            }
        }

        /* Check the status_page via url */
        else {

            /* Get the Status page details */
            $url = isset($this->params[0]) ? Database::clean_string($this->params[0]) : null;

            $status_page = $this->status_page = (new \Altum\Models\StatusPage())->get_status_page_by_url($url);

            if(!$status_page || ($status_page && (!$status_page->is_enabled || $status_page->domain_id))) {
                redirect();
            }

            $status_page->full_url = url('s/' . $status_page->url . '/');
        }

        $this->status_page_user = (new User())->get_user_by_user_id($this->status_page->user_id);

        /* Make sure to check if the user is active */
        if($this->status_page_user->active != 1) {
            redirect();
        }

        /* Check if the user has access to the status_page */
        $has_access = !$status_page->password || ($status_page->password && isset($_COOKIE['status_page_password_' . $this->status_page->status_page_id]) && $_COOKIE['status_page_password_' . $this->status_page->status_page_id] == $status_page->password);

        /* Do not let the user have password protection if the plan doesnt allow it */
        if(!$this->status_page_user->plan_settings->password_protection_is_enabled) {
            $has_access = true;
        }

        $this->has_access = $has_access;

        /* Parse some details */
        foreach(['monitors_ids', 'socials'] as $key) {
            $status_page->{$key} = json_decode($status_page->{$key});
        }

        /* Set the default language of the user, including the status page timezone */
        \Altum\Language::set_by_name($this->status_page_user->language, false);
        \Altum\Date::$timezone = $this->status_page->timezone;
    }

    /* Insert statistics log */
    public function create_statistics($status_page_id = null) {

        $cookie_name = 's_statistics_' . $status_page_id;

        if(isset($_COOKIE[$cookie_name]) && (int) $_COOKIE[$cookie_name] >= 3) {
            return;
        }

        if(!$this->status_page_user->plan_settings->analytics_is_enabled) {
            return;
        }

        /* Detect extra details about the user */
        $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);

        /* Do not track bots */
        if($whichbrowser->device->type == 'bot') {
            return;
        }

        /* Detect extra details about the user */
        $browser_name = $whichbrowser->browser->name ?? null;
        $os_name = $whichbrowser->os->name ?? null;
        $browser_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
        $device_type = get_device_type($_SERVER['HTTP_USER_AGENT']);
        $is_unique = isset($_COOKIE[$cookie_name]) ? 0 : 1;

        /* Detect the location */
        try {
            $maxmind = (new Reader(APP_PATH . 'includes/GeoLite2-City.mmdb'))->get(get_ip());
        } catch(\Exception $exception) {
            /* :) */
        }
        $country_code = isset($maxmind) && isset($maxmind['country']) ? $maxmind['country']['iso_code'] : null;
        $city_name = isset($maxmind) && isset($maxmind['city']) ? $maxmind['city']['names']['en'] : null;

        /* Process referrer */
        $referrer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : null;

        if(!isset($referrer)) {
            $referrer = [
                'host' => null,
                'path' => null
            ];
        }

        /* Check if the referrer comes from the same location */
        if(isset($referrer) && isset($referrer['host']) && $referrer['host'] == parse_url($this->status_page->full_url)['host']) {
            $is_unique = 0;

            $referrer = [
                'host' => null,
                'path' => null
            ];
        }

        /* Check if referrer actually comes from the QR code */
        if(isset($_GET['referrer']) && $_GET['referrer'] == 'qr') {
            $referrer = [
                'host' => 'qr',
                'path' => null
            ];
        }

        $utm_source = $_GET['utm_source'] ?? null;
        $utm_medium = $_GET['utm_medium'] ?? null;
        $utm_campaign = $_GET['utm_campaign'] ?? null;

        /* Insert the log */
        db()->insert('statistics', [
            'status_page_id' => $status_page_id,
            'country_code' => $country_code,
            'city_name' => $city_name,
            'os_name' => $os_name,
            'browser_name' => $browser_name,
            'referrer_host' => $referrer['host'],
            'referrer_path' => $referrer['path'],
            'device_type' => $device_type,
            'browser_language' => $browser_language,
            'utm_source' => $utm_source,
            'utm_medium' => $utm_medium,
            'utm_campaign' => $utm_campaign,
            'is_unique' => $is_unique,
            'datetime' => Date::$date,

        ]);

        /* Add the unique hit to the status_page table as well */
        db()->where('status_page_id', $status_page_id)->update('status_pages', ['pageviews' => db()->inc()]);

        /* Set cookie to try and avoid multiple entrances */
        $cookie_new_value = isset($_COOKIE[$cookie_name]) ? (int) $_COOKIE[$cookie_name] + 1 : 0;
        setcookie($cookie_name, (int) $cookie_new_value, time()+60*60*24*1);
    }

}
