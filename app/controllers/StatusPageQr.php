<?php

namespace Altum\Controllers;

use Altum\Middlewares\Authentication;
use Altum\Title;

class StatusPageQr extends Controller {

    public function index() {

        Authentication::guard();

        $status_page_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$status_page = db()->where('status_page_id', $status_page_id)->where('user_id', $this->user->user_id)->getOne('status_pages')) {
            redirect('status-pages');
        }

        /* Genereate the status_page full URL base */
        $status_page->full_url = (new \Altum\Models\StatusPage())->get_status_page_full_url($status_page, $this->user);

        /* Delete Modal */
        $view = new \Altum\Views\View('status-page/status_page_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Set a custom title */
        Title::set(sprintf(language()->status_page_qr->title, $status_page->name));

        /* Prepare the View */
        $data = [
            'status_page' => $status_page
        ];

        $view = new \Altum\Views\View('status-page-qr/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
