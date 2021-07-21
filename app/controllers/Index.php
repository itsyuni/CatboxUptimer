<?php

namespace Altum\Controllers;

use Altum\Meta;

class Index extends Controller {

    public function index() {

        /* Custom index redirect if set */
        if(!empty(settings()->index_url)) {
            header('Location: ' . settings()->index_url);
            die();
        }

        /* Get some stats */
        $total_monitors = database()->query("SELECT MAX(`monitor_id`) AS `total` FROM `monitors`")->fetch_object()->total ?? 0;
        $total_status_pages = database()->query("SELECT MAX(`status_page_id`) AS `total` FROM `status_pages`")->fetch_object()->total ?? 0;
        $total_monitors_logs = database()->query("SELECT MAX(`monitor_log_id`) AS `total` FROM `monitors_logs`")->fetch_object()->total ?? 0;

        /* Plans View */
        $view = new \Altum\Views\View('partials/plans', (array) $this);
        $this->add_view_content('plans', $view->run());

        /* Opengraph image */
        if(settings()->opengraph) {
            Meta::set_social_url(SITE_URL);
            Meta::set_social_description(language()->index->meta_description);
            Meta::set_social_image(UPLOADS_FULL_URL . 'opengraph/' .settings()->opengraph);
        }

        /* Main View */
        $data = [
            'total_monitors' => $total_monitors,
            'total_status_pages' => $total_status_pages,
            'total_monitors_logs' => $total_monitors_logs,
        ];

        $view = new \Altum\Views\View('index/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
