<?php

namespace Altum\Controllers;

use Altum\Response;
use Altum\Traits\Apiable;

class ApiPayments extends Controller {
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
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `payments` WHERE `user_id` = {$this->api_user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('api/payments?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $data = [];
        $data_result = database()->query("
            SELECT
                *
            FROM
                `payments`
            WHERE
                `user_id` = {$this->api_user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $data_result->fetch_object()) {

            /* Prepare the data */
            $row = [
                'id' => (int) $row->id,
                'plan_id' => (int) $row->plan_id,
                'processor' => $row->processor,
                'type' => $row->type,
                'frequency' => $row->frequency,
                'email' => $row->email,
                'name' => $row->name,
                'total_amount' => $row->total_amount,
                'currency' => $row->currency,
                'status' => (bool) (int) $row->status,
                'date' => $row->date,
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

        $id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $payment = db()->where('id', $id)->where('user_id', $this->api_user->user_id)->getOne('payments');

        /* We haven't found the resource */
        if(!$payment) {
            Response::jsonapi_error([[
                'title' => language()->api->error_message->not_found,
                'status' => '404'
            ]], null, 404);
        }

        /* Prepare the data */
        $data = [
            'id' => (int) $payment->id,
            'plan_id' => (int) $payment->plan_id,
            'processor' => $payment->processor,
            'type' => $payment->type,
            'frequency' => $payment->frequency,
            'email' => $payment->email,
            'name' => $payment->name,
            'total_amount' => $payment->total_amount,
            'currency' => $payment->currency,
            'status' => (bool) (int) $payment->status,
            'date' => $payment->date,
        ];

        Response::jsonapi_success($data);

    }

}
