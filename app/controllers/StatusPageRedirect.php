<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Models\User;
use Altum\Title;

class StatusPageRedirect extends Controller {

    public function index() {

        $status_page_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$status_page = db()->where('status_page_id', $status_page_id)->getOne('status_pages', ['status_page_id', 'domain_id', 'user_id', 'url'])) {
            redirect('status-pages');
        }

        $this->status_page_user = (new User())->get_user_by_user_id($status_page->user_id);

        /* Genereate the status_page full URL base */
        $status_page->full_url = (new \Altum\Models\StatusPage())->get_status_page_full_url($status_page, $this->status_page_user);

        header('Location: ' . $status_page->full_url);

        die();

    }
}
