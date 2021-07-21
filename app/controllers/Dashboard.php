<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;

class Dashboard extends Controller {

    public function index() {

        Authentication::guard();

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Get available custom domains */
        $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($this->user, false);

        /* Get the monitors */
        $monitors = [];
        $monitors_result = database()->query("
            SELECT
                *
            FROM
                `monitors`
            WHERE
                `user_id` = {$this->user->user_id}
            LIMIT
                5
        ");
        while($row = $monitors_result->fetch_object()) {
            $monitors[] = $row;
        }

        /* Get the heartbeats */
        $heartbeats = [];
        $heartbeats_result = database()->query("
            SELECT
                *
            FROM
                `heartbeats`
            WHERE
                `user_id` = {$this->user->user_id}
            LIMIT
                5
        ");
        while($row = $heartbeats_result->fetch_object()) {
            $heartbeats[] = $row;
        }

        /* Get the status_pages */
        $status_pages = [];
        $status_pages_result = database()->query("
            SELECT
                *
            FROM
                `status_pages`
            WHERE
                `user_id` = {$this->user->user_id}
            LIMIT
                5
        ");
        while($row = $status_pages_result->fetch_object()) {

            /* Genereate the status page full URL base */
            $row->full_url = (new \Altum\Models\StatusPage())->get_status_page_full_url($row, $this->user, $domains);

            $status_pages[] = $row;
        }

        /* Delete Modal */
        $view = new \Altum\Views\View('monitor/monitor_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Modal */
        $view = new \Altum\Views\View('heartbeat/heartbeat_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Modal */
        $view = new \Altum\Views\View('status-page/status_page_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the View */
        $data = [
            'monitors' => $monitors,
            'heartbeats' => $heartbeats,
            'status_pages' => $status_pages,
            'projects' => $projects
        ];

        $view = new \Altum\Views\View('dashboard/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
