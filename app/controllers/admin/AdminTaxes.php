<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class AdminTaxes extends Controller {

    public function index() {

        $taxes = db()->get('taxes');

        /* Main View */
        $data = [
            'taxes' => $taxes
        ];

        $view = new \Altum\Views\View('admin/taxes/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        $tax_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the tax */
            db()->where('tax_id', $tax_id)->delete('taxes');

            /* Set a nice success message */
            Alerts::add_success(language()->admin_tax_delete_modal->success_message);

        }

        redirect('admin/taxes');
    }

}
