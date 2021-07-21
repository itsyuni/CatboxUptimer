<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;

class AccountLogs extends Controller {

    public function index() {

        Authentication::guard();

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `users_logs` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, 25, $_GET['page'] ?? 1, url('account-logs?page=%d')));

        /* Get the logs list for the user */
        $logs = [];
        $logs_result = database()->query("SELECT * FROM `users_logs` WHERE `user_id` = {$this->user->user_id} ORDER BY `id` DESC {$paginator->get_sql_limit()}");
        while($row = $logs_result->fetch_object()) $logs[] = $row;

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Establish the account sub menu view */
        $menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $menu->run());

        /* Prepare the View */
        $data = [
            'logs' => $logs,
            'pagination' => $pagination
        ];

        $view = new \Altum\Views\View('account-logs/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }


}
