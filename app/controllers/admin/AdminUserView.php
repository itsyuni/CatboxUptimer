<?php

namespace Altum\Controllers;

use Altum\Models\Plan;

class AdminUserView extends Controller {

    public function index() {

        $user_id = (isset($this->params[0])) ? (int) $this->params[0] : null;

        /* Check if user exists */
        if(!$user = db()->where('user_id', $user_id)->getOne('users')) {
            redirect('admin/users');
        }

        /* Get widget stats */
        $monitors = db()->where('user_id', $user_id)->getValue('monitors', 'count(`monitor_id`)');
        $heartbeats = db()->where('user_id', $user_id)->getValue('heartbeats', 'count(`heartbeat_id`)');
        $status_pages = db()->where('user_id', $user_id)->getValue('status_pages', 'count(`status_page_id`)');
        $projects = db()->where('user_id', $user_id)->getValue('projects', 'count(`project_id`)');
        $domains = db()->where('user_id', $user_id)->getValue('domains', 'count(`domain_id`)');
        $payments = in_array(settings()->license->type, ['Extended License', 'extended']) ? db()->where('user_id', $user_id)->getValue('payments', 'count(`id`)') : 0;

        /* Get last X logs */
        $user_logs_result = database()->query("SELECT * FROM `users_logs` WHERE `user_id` = {$user_id} ORDER BY `id` DESC LIMIT 15");

        /* Get the current plan details */
        $user->plan = (new Plan())->get_plan_by_id($user->plan_id);

        /* Check if its a custom plan */
        if($user->plan_id == 'custom') {
            $user->plan->settings = $user->plan_settings;
        }

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/users/user_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Login Modal */
        $view = new \Altum\Views\View('admin/users/user_login_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
            'user' => $user,
            'monitors' => $monitors,
            'heartbeats' => $heartbeats,
            'status_pages' => $status_pages,
            'projects' => $projects,
            'domains' => $domains,
            'payments' => $payments,

            'user_logs_result' => $user_logs_result
        ];

        $view = new \Altum\Views\View('admin/user-view/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
