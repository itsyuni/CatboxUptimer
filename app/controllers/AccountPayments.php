<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;

class AccountPayments extends Controller {

    public function index() {

        Authentication::guard();

        if(!settings()->payment->is_enabled) {
            redirect('dashboard');
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['processor', 'type', 'frequency'], [], ['total_amount', 'date']));

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `payments` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('account-payments?' . $filters->get_get() . '&page=%d')));

        /* Get the payments list for the user */
        $payments = [];
        $payments_result = database()->query("SELECT `payments`.*, plans.`name` AS `plan_name` FROM `payments` LEFT JOIN plans ON `payments`.plan_id = plans.plan_id WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where('payments')} {$filters->get_sql_order_by('payments')} {$paginator->get_sql_limit()}");
        while($row = $payments_result->fetch_object()) $payments[] = $row;

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Establish the account sub menu view */
        $menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $menu->run());

        /* Prepare the View */
        $data = [
            'payments' => $payments,
            'pagination' => $pagination,
            'filters' => $filters
        ];

        $view = new \Altum\Views\View('account-payments/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }


}
