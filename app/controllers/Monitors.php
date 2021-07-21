<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;

class Monitors extends Controller {

    public function index() {

        Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled', 'type', 'project_id'], ['name', 'target'], ['datetime', 'last_check_datetime', 'name', 'uptime', 'average_response_time']));

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `monitors` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('monitors?' . $filters->get_get() . '&page=%d')));

        /* Get the monitors */
        $monitors = [];
        $monitors_result = database()->query("
            SELECT
                *
            FROM
                `monitors`
            WHERE
                `user_id` = {$this->user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}

            {$paginator->get_sql_limit()}
        ");
        while($row = $monitors_result->fetch_object()) {
            $monitors[] = $row;
        }

        /* Export handler */
        process_export_csv($monitors, 'include', ['monitor_id', 'project_id', 'name', 'type', 'target', 'port', 'ping_servers_ids', 'is_ok', 'uptime', 'downtime', 'average_response_time', 'total_checks', 'total_ok_checks', 'total_not_ok_checks', 'last_check_datetime', 'is_enabled', 'datetime'], sprintf(language()->monitors->title));
        process_export_json($monitors, 'include', ['monitor_id', 'project_id', 'name', 'type', 'target', 'port', 'settings', 'ping_servers_ids', 'is_ok', 'uptime', 'downtime', 'average_response_time', 'total_checks', 'total_ok_checks', 'total_not_ok_checks', 'last_check_datetime', 'notifications', 'is_enabled', 'datetime'], sprintf(language()->monitors->title));

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Delete Modal */
        $view = new \Altum\Views\View('monitor/monitor_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Prepare the View */
        $data = [
            'projects' => $projects,
            'monitors' => $monitors,
            'total_monitors' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \Altum\Views\View('monitors/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
