<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class StatusPage extends Controller {

    public function index() {
        Authentication::guard();

        redirect('status-pages');
    }

    public function delete() {

        Authentication::guard();

        if(empty($_POST)) {
            die();
        }

        $status_page_id = (int) Database::clean_string($_POST['status_page_id']);

        if(!Csrf::check()) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            redirect('status-pages');
        }

        /* Make sure the status_page id is created by the logged in user */
        if(!$status_page = db()->where('status_page_id', $status_page_id)->where('user_id', $this->user->user_id)->getOne('status_pages', ['status_page_id', 'logo', 'favicon'])) {
            redirect('status-pages');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            (new \Altum\Models\StatusPage())->delete_uploads($status_page->favicon, $status_page->logo);
            (new \Altum\Models\StatusPage())->delete($status_page->status_page_id);

            /* Set a nice success message */
            Alerts::add_success(language()->status_page_delete_modal->success_message);

            redirect('status-pages');

        }

        redirect('status-pages');
    }
}
