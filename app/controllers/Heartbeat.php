<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class Heartbeat extends Controller {

    public function index() {

        Authentication::guard();

        $heartbeat_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$heartbeat = db()->where('heartbeat_id', $heartbeat_id)->where('user_id', $this->user->user_id)->getOne('heartbeats')) {
            redirect('heartbeats');
        }
        $heartbeat->settings = json_decode($heartbeat->settings);

        $start_date = isset($_GET['start_date']) ? Database::clean_string($_GET['start_date']) : Date::get('', 4);
        $end_date = isset($_GET['end_date']) ? Database::clean_string($_GET['end_date']) : Date::get('', 4);
        $date = \Altum\Date::get_start_end_dates($start_date, $end_date);

        /* Get the required statistics */
        $heartbeat_logs = [];
        $heartbeat_logs_chart = [];

        $heartbeat_logs_result = database()->query("
            SELECT
                `is_ok`,
                `datetime`
            FROM
                `heartbeats_logs`
            WHERE
                `heartbeat_id` = {$heartbeat->heartbeat_id}
                AND (`datetime` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
        ");

        $total_runs = 0;
        $total_missed_runs = 0;

        /* Get heartbeat logs to calculate data and display charts */
        while($heartbeat_log = $heartbeat_logs_result->fetch_object()) {

            $heartbeat_logs[] = $heartbeat_log;

            $label = $start_date == $end_date ? \Altum\Date::get($heartbeat_log->datetime, 3) : \Altum\Date::get($heartbeat_log->datetime, 1);

            $heartbeat_logs_chart[$label] = [
                'is_ok' => $heartbeat_log->is_ok,
            ];

            $total_runs = $heartbeat_log->is_ok ? $total_runs + 1 : $total_runs;
            $total_missed_runs = !$heartbeat_log->is_ok ? $total_missed_runs + 1 : $total_missed_runs;
        }

        /* Export handler */
        process_export_csv($heartbeat_logs, 'include', ['is_ok', 'datetime'], sprintf(language()->heartbeat->title, $heartbeat->name));
        process_export_json($heartbeat_logs, 'include', ['is_ok', 'datetime'], sprintf(language()->heartbeat->title, $heartbeat->name));

        $heartbeat_logs_chart = get_chart_data($heartbeat_logs_chart);

        /* Get the available incidents */
        $heartbeat_incidents = [];

        $heartbeat_incidents_result = database()->query("
            SELECT
                `start_datetime`,
                `end_datetime`
            FROM
                 `incidents`
            WHERE
                `heartbeat_id` = {$heartbeat->heartbeat_id}
                AND `start_datetime` >= '{$date->start_date_query}' 
                AND (`end_datetime` <= '{$date->end_date_query}' OR `end_datetime` IS NULL)
        ");

        while($row = $heartbeat_incidents_result->fetch_object()) {
            $heartbeat_incidents[] = $row;
        }

        /* calculate some data */
        $total_heartbeat_logs = count($heartbeat_logs);
        $uptime = $total_runs > 0 ? $total_runs / ($total_runs + $total_missed_runs) * 100 : 0;
        $downtime = 100 - $uptime;

        /* Delete Modal */
        $view = new \Altum\Views\View('heartbeat/heartbeat_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Set a custom title */
        Title::set(sprintf(language()->heartbeat->title, $heartbeat->name));

        /* Prepare the View */
        $data = [
            'heartbeat' => $heartbeat,
            'heartbeat_logs_chart' => $heartbeat_logs_chart,
            'heartbeat_logs' => $heartbeat_logs,
            'total_heartbeat_logs' => $total_heartbeat_logs,
            'heartbeat_logs_data' => [
                'uptime' => $uptime,
                'downtime' => $downtime,
                'total_runs' => $total_runs,
                'total_missed_runs' => $total_missed_runs
            ],
            'date' => $date,
            'heartbeat_incidents' => $heartbeat_incidents,
        ];

        $view = new \Altum\Views\View('heartbeat/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        Authentication::guard();

        if(empty($_POST)) {
            die();
        }

        $heartbeat_id = (int) Database::clean_string($_POST['heartbeat_id']);

        if(!Csrf::check()) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            redirect('heartbeats');
        }

        /* Make sure the heartbeat id is created by the logged in user */
        if(!$heartbeat = db()->where('heartbeat_id', $heartbeat_id)->where('user_id', $this->user->user_id)->getOne('heartbeats')) {
            redirect('heartbeats');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the heartbeat */
            db()->where('heartbeat_id', $heartbeat->heartbeat_id)->delete('heartbeats');

            /* Clear cache */
            \Altum\Cache::$adapter->deleteItemsByTag('heartbeat_id=' . $heartbeat->heartbeat_id);

            /* Set a nice success message */
            Alerts::add_success(language()->heartbeat_delete_modal->success_message);

            redirect('heartbeats');

        }

        redirect('heartbeats');
    }
}
