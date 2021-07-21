<?php

namespace Altum\Controllers;

class AdminIndex extends Controller {

    public function index() {

        $monitors = db()->getValue('monitors', 'count(`monitor_id`)');
        $heartbeats = db()->getValue('heartbeats', 'count(`heartbeat_id`)');
        $status_pages = db()->getValue('status_pages', 'count(`status_page_id`)');
        $projects = db()->getValue('projects', 'count(`project_id`)');
        $domains = db()->getValue('domains', 'count(`domain_id`)');
        $users = db()->getValue('users', 'count(`user_id`)');

        if(in_array(settings()->license->type, ['Extended License', 'extended'])) {
            $payments = db()->getValue('payments', 'count(`id`)');
            $payments_total_amount = db()->getValue('payments', 'sum(`total_amount`)');
        } else {
            $payments = $payments_total_amount = 0;
        }

        /* Main View */
        $data = [
            'monitors' => $monitors,
            'heartbeats' => $heartbeats,
            'status_pages' => $status_pages,
            'projects' => $projects,
            'domains' => $domains,
            'users' => $users,
            'payments' => $payments,
            'payments_total_amount' => $payments_total_amount,
        ];

        $view = new \Altum\Views\View('admin/index/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
