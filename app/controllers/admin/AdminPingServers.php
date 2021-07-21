<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Middlewares\Csrf;

class AdminPingServers extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled', 'country_code'], ['name'], ['datetime', 'name']));

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `ping_servers` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/ping-servers?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $ping_servers = [];
        $ping_servers_result = database()->query("
            SELECT
                *
            FROM
                `ping_servers`
            WHERE
                1 = 1
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
            
            {$paginator->get_sql_limit()}
        ");
        while($row = $ping_servers_result->fetch_object()) {
            $ping_servers[] = $row;
        }

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/ping-servers/ping_server_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
            'ping_servers' => $ping_servers,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\Views\View('admin/ping-servers/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }


    public function delete() {

        $ping_server_id = (isset($this->params[0])) ? (int) $this->params[0] : null;

        if($ping_server_id == 1) {
            redirect('admin/ping-servers');
        }

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
        }

        if(!$ping_server = db()->where('ping_server_id', $ping_server_id)->getOne('ping_servers', 'ping_server_id')) {
            redirect('admin/ping-servers');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the ping server */
            db()->where('ping_server_id', $ping_server_id)->delete('ping_servers');

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItem('ping_servers');

            /* Set a nice success message */
            Alerts::add_success(language()->admin_ping_server_delete_modal->success_message);

        }

        redirect('admin/ping-servers');
    }

}
