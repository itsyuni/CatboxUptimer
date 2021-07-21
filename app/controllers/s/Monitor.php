<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Meta;
use Altum\Title;

class Monitor extends Controller {
    public $status_page;
    public $status_page_user = null;

    public $monitor;

    public function index() {

        /* Parse & control the status_page */
        require_once APP_PATH . 'controllers/s/StatusPage.php';
        $status_page_controller = new \Altum\Controllers\StatusPage((array) $this);

        $status_page_controller->init();

        /* Check if the user has access */
        if(!$status_page_controller->has_access) {
            header('Location: ' . $status_page_controller->status_page->full_url); die();
        }

        /* Set the needed variables for the wrapper */
        $this->status_page_user = $status_page_controller->status_page_user;
        $this->status_page = $status_page_controller->status_page;

        /* Monitor init */
        $this->init();

        /* Prepare date selector stuff */
        $start_date = isset($_GET['start_date']) ? Database::clean_string($_GET['start_date']) : Date::get('', 4);
        $end_date = isset($_GET['end_date']) ? Database::clean_string($_GET['end_date']) : Date::get('', 4);
        $date = \Altum\Date::get_start_end_dates($start_date, $end_date);

        /* Get the required statistics */
        $monitor_logs = (new \Altum\Models\MonitorsLogs())->get_monitor_logs_by_monitor_id_and_start_datetime_and_end_datetime($this->monitor->monitor_id, $date->start_date_query, $date->end_date_query);
        $monitor_logs_chart = [];

        $total_ok_checks = 0;
        $total_not_ok_checks = 0;
        $total_response_time = 0;

        /* Get monitor logs to calculate data and display charts */
        foreach($monitor_logs as $monitor_log) {
            $monitor_logs[] = $monitor_log;

            $label = $start_date == $end_date ? \Altum\Date::get($monitor_log->datetime, 3) : \Altum\Date::get($monitor_log->datetime, 1);

            $monitor_logs_chart[$label] = [
                'response_time' => $monitor_log->response_time,
            ];

            $total_ok_checks = $monitor_log->is_ok ? $total_ok_checks + 1 : $total_ok_checks;
            $total_not_ok_checks = !$monitor_log->is_ok ? $total_not_ok_checks + 1 : $total_not_ok_checks;
            $total_response_time += $monitor_log->response_time;
        }

        $monitor_logs_chart = get_chart_data($monitor_logs_chart);

        /* calculate some data */
        $total_monitor_logs = count($monitor_logs);
        $uptime = $total_ok_checks > 0 ? $total_ok_checks / ($total_ok_checks + $total_not_ok_checks) * 100 : 0;
        $downtime = 100 - $uptime;
        $average_response_time = $total_monitor_logs > 0 ? $total_response_time / $total_monitor_logs : 0;

        /* Get potential incidents */
        $monitor_incidents = (new \Altum\Models\MonitorsIncidents())->get_monitor_incidents_by_monitor_id_and_start_datetime_and_end_datetime($this->monitor->monitor_id, $date->start_date_query, $date->end_date_query);

        /* Add statistics */
        $status_page_controller->create_statistics($this->status_page->status_page_id);

        /* Set a custom title */
        Title::set(sprintf(language()->s_monitor->title, $this->monitor->name, $this->status_page->name));

        /* Set the meta tags */
        Meta::set_social_url($this->status_page->full_url . $this->monitor->monitor_id);
        Meta::set_social_title(sprintf(language()->s_monitor->title, $this->monitor->name, $this->status_page->name));

        /* Prepare the header */
        $view = new \Altum\Views\View('s/partials/header', (array) $this);
        $this->add_view_content('header', $view->run(['status_page' => $this->status_page]));

        /* Main View */
        $data = [
            'status_page' => $this->status_page,
            'status_page_user' => $this->status_page_user,

            'monitor' => $this->monitor ,
            'monitor_logs_chart' => $monitor_logs_chart,
            'monitor_logs' => $monitor_logs,
            'total_monitor_logs' => $total_monitor_logs,
            'monitor_logs_data' => [
                'uptime' => $uptime,
                'downtime' => $downtime,
                'average_response_time' => $average_response_time,
                'total_ok_checks' => $total_ok_checks,
                'total_not_ok_checks' => $total_not_ok_checks
            ],

            'monitor_incidents' => $monitor_incidents,

            'date' => $date
        ];

        $view = new \Altum\Views\View('s/monitor/' . $this->status_page->theme . '/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function init() {
        /* Get the  details */
        $monitor_id = isset($this->params[1]) ? (int) $this->params[1] : null;

        $monitor = $this->monitor = (new \Altum\Models\Monitors())->get_monitor_by_monitor_id($monitor_id);

        if(!$monitor || ($monitor && !$monitor->is_enabled)) {
            redirect();
        }

    }

}
