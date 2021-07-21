<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Middlewares\Csrf;

class AdminHeartbeats extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled', 'type', 'user_id', 'project_id'], ['name', 'target'], ['datetime', 'name', 'uptime', 'total_checks', 'last_check_datetime', 'average_response_time']));

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `heartbeats` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/heartbeats?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $heartbeats = [];
        $heartbeats_result = database()->query("
            SELECT
                `heartbeats`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `heartbeats`
            LEFT JOIN
                `users` ON `heartbeats`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('heartbeats')}
                {$filters->get_sql_order_by('heartbeats')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $heartbeats_result->fetch_object()) {
            $heartbeats[] = $row;
        }

        /* Export handler */
        process_export_csv($heartbeats, 'include', ['heartbeat_id', 'project_id', 'name', 'code', 'is_ok', 'uptime', 'downtime', 'total_runs', 'total_missed_runs', 'last_run_datetime', 'next_run_datetime', 'is_enabled', 'datetime'], sprintf(language()->heartbeats->title));
        process_export_json($heartbeats, 'include', ['heartbeat_id', 'project_id', 'name', 'code', 'is_ok', 'uptime', 'downtime', 'total_runs', 'total_missed_runs', 'last_run_datetime', 'next_run_datetime', 'is_enabled', 'datetime'], sprintf(language()->heartbeats->title));

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/heartbeats/heartbeat_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
            'heartbeats' => $heartbeats,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\Views\View('admin/heartbeats/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        $heartbeat_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
        }

        if(!$heartbeat = db()->where('heartbeat_id', $heartbeat_id)->getOne('heartbeats')) {
            redirect('admin/heartbeats');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the heartbeat */
            db()->where('heartbeat_id', $heartbeat->heartbeat_id)->delete('heartbeats');

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('heartbeat_id=' . $heartbeat_id);

            /* Set a nice success message */
            Alerts::add_success(language()->admin_heartbeat_delete_modal->success_message);

        }

        redirect('admin/heartbeats');
    }

}
