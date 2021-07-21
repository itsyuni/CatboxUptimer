<?php

namespace Altum\Controllers;

use Altum\Response;
use Altum\Traits\Apiable;

class ApiMonitors extends Controller {
    use Apiable;

    public function index() {

        $this->verify_request();

        /* Decide what to continue with */
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':

                /* Detect if we only need an object, or the whole list */
                if(isset($this->params[0])) {
                    $this->get();
                } else {
                    $this->get_all();
                }

            break;
        }

        $this->return_404();
    }

    private function get_all() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], [], []));

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `monitors` WHERE `user_id` = {$this->api_user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('api/payments?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $data = [];
        $data_result = database()->query("
            SELECT
                *
            FROM
                `monitors`
            WHERE
                `user_id` = {$this->api_user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $data_result->fetch_object()) {

            /* Prepare the data */
            $row = [
                'id' => (int) $row->monitor_id,
                'project_id' => (int) $row->project_id,
                'name' => $row->name,
                'type' => $row->type,
                'target' => $row->target,
                'port' => (int) $row->port,
                'settings' => json_decode($row->settings),
                'ping_servers_ids' => json_decode($row->ping_servers_ids),
                'is_ok' => (int) $row->is_ok,
                'uptime' => (float) $row->uptime,
                'downtime' => (float) $row->downtime,
                'average_response_time' => (float) $row->average_response_time,
                'total_checks' => (int) $row->total_checks,
                'total_ok_checks' => (int) $row->total_ok_checks,
                'total_not_ok_checks' => (int) $row->total_not_ok_checks,
                'last_check_datetime' => $row->last_check_datetime,
                'notifications' => json_decode($row->notifications),
                'is_enabled' => (bool) $row->is_enabled,
                'datetime' => $row->datetime
            ];

            $data[] = $row;
        }

        /* Prepare the data */
        $meta = [
            'page' => $_GET['page'] ?? 1,
            'total_pages' => $paginator->getNumPages(),
            'results_per_page' => $filters->get_results_per_page(),
            'total_results' => (int) $total_rows,
        ];

        /* Prepare the pagination links */
        $others = ['links' => [
            'first' => $paginator->getPageUrl(1),
            'last' => $paginator->getNumPages() ? $paginator->getPageUrl($paginator->getNumPages()) : null,
            'next' => $paginator->getNextUrl(),
            'prev' => $paginator->getPrevUrl(),
            'self' => $paginator->getPageUrl($_GET['page'] ?? 1)
        ]];

        Response::jsonapi_success($data, $meta, 200, $others);
    }

    private function get() {

        $monitor_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $monitor = db()->where('monitor_id', $monitor_id)->where('user_id', $this->api_user->user_id)->getOne('monitors');

        /* We haven't found the resource */
        if(!$monitor) {
            Response::jsonapi_error([[
                'title' => language()->api->error_message->not_found,
                'status' => '404'
            ]], null, 404);
        }

        /* Prepare the data */
        $data = [
            'id' => (int) $monitor->monitor_id,
            'project_id' => (int) $monitor->project_id,
            'name' => $monitor->name,
            'type' => $monitor->type,
            'target' => $monitor->target,
            'port' => (int) $monitor->port,
            'settings' => json_decode($monitor->settings),
            'ping_servers_ids' => json_decode($monitor->ping_servers_ids),
            'is_ok' => (int) $monitor->is_ok,
            'uptime' => (float) $monitor->uptime,
            'downtime' => (float) $monitor->downtime,
            'average_response_time' => (float) $monitor->average_response_time,
            'total_checks' => (int) $monitor->total_checks,
            'total_ok_checks' => (int) $monitor->total_ok_checks,
            'total_not_ok_checks' => (int) $monitor->total_not_ok_checks,
            'last_check_datetime' => $monitor->last_check_datetime,
            'notifications' => json_decode($monitor->notifications),
            'is_enabled' => (bool) $monitor->is_enabled,
            'datetime' => $monitor->datetime
        ];

        Response::jsonapi_success($data);

    }

}
