<?php

namespace Altum\Controllers;

class Affiliate extends Controller {

    public function index() {

        if(!settings()->affiliate->is_enabled) {
            redirect();
        }

        /* Prepare the View */
        $view = new \Altum\Views\View('affiliate/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }

}


