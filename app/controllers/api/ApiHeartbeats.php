<?php

namespace Altum\Controllers;

use Altum\Response;
use Altum\Traits\Apiable;

class ApiHeartbeats extends Controller {
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
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `heartbeats` WHERE `user_id` = {$this->api_user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('api/payments?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $data = [];
        $data_result = database()->query("
            SELECT
                *
            FROM
                `heartbeats`
            WHERE
                `user_id` = {$this->api_user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $data_result->fetch_object()) {

            /* Prepare the data */
            $row = [
                'id' => (int) $row->heartbeat_id,
                'project_id' => (int) $row->project_id,
                'name' => $row->name,
                'code' => $row->code,
                'settings' => json_decode($row->settings),
                'is_ok' => (int) $row->is_ok,
                'uptime' => (float) $row->uptime,
                'downtime' => (float) $row->downtime,
                'total_runs' => (int) $row->total_runs,
                'total_missed_runs' => (int) $row->total_missed_runs,
                'last_run_datetime' => $row->last_run_datetime,
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

        $heartbeat_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $heartbeat = db()->where('heartbeat_id', $heartbeat_id)->where('user_id', $this->api_user->user_id)->getOne('heartbeats');

        /* We haven't found the resource */
        if(!$heartbeat) {
            Response::jsonapi_error([[
                'title' => language()->api->error_message->not_found,
                'status' => '404'
            ]], null, 404);
        }

        /* Prepare the data */
        $data = [
            'id' => (int) $heartbeat->heartbeat_id,
            'project_id' => (int) $heartbeat->project_id,
            'name' => $heartbeat->name,
            'code' => $heartbeat->code,
            'settings' => json_decode($heartbeat->settings),
            'is_ok' => (int) $heartbeat->is_ok,
            'uptime' => (float) $heartbeat->uptime,
            'downtime' => (float) $heartbeat->downtime,
            'total_runs' => (int) $heartbeat->total_runs,
            'total_missed_runs' => (int) $heartbeat->total_missed_runs,
            'last_run_datetime' => $heartbeat->last_run_datetime,
            'notifications' => json_decode($heartbeat->notifications),
            'is_enabled' => (bool) $heartbeat->is_enabled,
            'datetime' => $heartbeat->datetime
        ];

        Response::jsonapi_success($data);

    }

}
