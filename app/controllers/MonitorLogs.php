<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Authentication;
use Altum\Title;

class MonitorLogs extends Controller {

    public function index() {

        Authentication::guard();

        $monitor_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$monitor = db()->where('monitor_id', $monitor_id)->where('user_id', $this->user->user_id)->getOne('monitors')) {
            redirect('monitors');
        }
        $monitor->details = json_decode($monitor->details);
        $monitor->settings = json_decode($monitor->settings);

        $start_date = isset($_GET['start_date']) ? Database::clean_string($_GET['start_date']) : Date::get('', 4);
        $end_date = isset($_GET['end_date']) ? Database::clean_string($_GET['end_date']) : Date::get('', 4);
        $date = \Altum\Date::get_start_end_dates($start_date, $end_date);

        /* Get available ping servers */
        $ping_servers = (new \Altum\Models\PingServers())->get_ping_servers();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_ok', 'ping_server_id'], ['response_status_code'], ['response_time', 'datetime']));

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `monitors_logs` WHERE `monitor_id` = {$monitor->monitor_id} AND (`datetime` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}') {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('monitor-logs/' . $monitor->monitor_id . '?' . $filters->get_get() . '&page=%d')));

        /* Get the required logs */
        $monitor_logs = [];
        $monitor_logs_result = database()->query("
            SELECT
                `ping_server_id`,
                `is_ok`,
                `response_time`,
                `response_status_code`,
                `error`,
                `datetime`
            FROM
                 `monitors_logs`
            WHERE
                `monitor_id` = {$monitor->monitor_id}
                AND (`datetime` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");

        /* Get monitor logs to calculate data and display charts */
        while($monitor_log = $monitor_logs_result->fetch_object()) {
            $monitor_logs[] = $monitor_log;
        }

        /* Export handler */
        process_export_csv($monitor_logs, 'include', ['is_ok', 'response_time', 'response_status_code', 'datetime'], sprintf(language()->monitor_logs->title, $monitor->name));
        process_export_json($monitor_logs, 'include', ['is_ok', 'response_time', 'response_status_code', 'datetime'], sprintf(language()->monitor_logs->title, $monitor->name));

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Delete Modal */
        $view = new \Altum\Views\View('monitor/monitor_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Set a custom title */
        Title::set(sprintf(language()->monitor_logs->title, $monitor->name));

        /* Prepare the View */
        $data = [
            'monitor' => $monitor,
            'monitor_logs' => $monitor_logs,
            'date' => $date,
            'ping_servers' => $ping_servers,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \Altum\Views\View('monitor-logs/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }
}
