<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;

class Heartbeats extends Controller {

    public function index() {

        Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled', 'project_id'], ['name'], ['datetime', 'last_run_datetime', 'name', 'uptime']));

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `heartbeats` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('heartbeats?' . $filters->get_get() . '&page=%d')));

        /* Get the heartbeats */
        $heartbeats = [];
        $heartbeats_result = database()->query("
            SELECT
                *
            FROM
                `heartbeats`
            WHERE
                `user_id` = {$this->user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}

            {$paginator->get_sql_limit()}
        ");
        while($row = $heartbeats_result->fetch_object()) {
            $heartbeats[] = $row;
        }

        /* Export handler */
        process_export_csv($heartbeats, 'include', ['heartbeat_id', 'project_id', 'name', 'code', 'is_ok', 'uptime', 'downtime', 'total_runs', 'total_missed_runs', 'last_run_datetime', 'next_run_datetime', 'is_enabled', 'datetime'], sprintf(language()->heartbeats->title));
        process_export_json($heartbeats, 'include', ['heartbeat_id', 'project_id', 'name', 'code', 'is_ok', 'uptime', 'downtime', 'total_runs', 'total_missed_runs', 'last_run_datetime', 'next_run_datetime', 'notifications', 'is_enabled', 'datetime'], sprintf(language()->heartbeats->title));

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Delete Modal */
        $view = new \Altum\Views\View('heartbeat/heartbeat_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Prepare the View */
        $data = [
            'projects' => $projects,
            'heartbeats' => $heartbeats,
            'total_heartbeats' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \Altum\Views\View('heartbeats/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
