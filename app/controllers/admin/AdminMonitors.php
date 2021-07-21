<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Middlewares\Csrf;

class AdminMonitors extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled', 'type', 'user_id', 'project_id'], ['name', 'target'], ['datetime', 'name', 'uptime', 'total_checks', 'last_check_datetime', 'average_response_time']));

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `monitors` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/monitors?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $monitors = [];
        $monitors_result = database()->query("
            SELECT
                `monitors`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `monitors`
            LEFT JOIN
                `users` ON `monitors`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('monitors')}
                {$filters->get_sql_order_by('monitors')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $monitors_result->fetch_object()) {
            $monitors[] = $row;
        }

        /* Export handler */
        process_export_csv($monitors, 'include', ['monitor_id', 'user_id', 'project_id', 'name', 'type', 'target', 'port', 'ping_servers_ids', 'is_ok', 'uptime', 'downtime', 'average_response_time', 'total_checks', 'total_ok_checks', 'total_not_ok_checks', 'last_check_datetime', 'is_enabled', 'datetime'], sprintf(language()->monitors->title));
        process_export_json($monitors, 'include', ['monitor_id', 'user_id', 'project_id', 'name', 'type', 'target', 'port', 'settings', 'ping_servers_ids', 'is_ok', 'uptime', 'downtime', 'average_response_time', 'total_checks', 'total_ok_checks', 'total_not_ok_checks', 'last_check_datetime', 'is_enabled', 'datetime'], sprintf(language()->monitors->title));

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/monitors/monitor_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
            'monitors' => $monitors,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\Views\View('admin/monitors/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        $monitor_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
        }

        if(!$monitor = db()->where('monitor_id', $monitor_id)->getOne('monitors', ['monitor_id'])) {
            redirect('admin/monitors');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the monitor */
            db()->where('monitor_id', $monitor_id)->delete('monitors');

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('monitor_id=' . $monitor_id);

            /* Set a nice success message */
            Alerts::add_success(language()->admin_monitor_delete_modal->success_message);

        }

        redirect('admin/monitors');
    }

}
