<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class AdminTaxUpdate extends Controller {

    public function index() {

        $tax_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$tax = db()->where('tax_id', $tax_id)->getOne('taxes')) {
            redirect('admin/taxes');
        }

        $tax->countries = json_decode($tax->countries);

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['internal_name'] = Database::clean_string($_POST['internal_name']);
            $_POST['name'] = Database::clean_string($_POST['name']);
            $_POST['description'] = Database::clean_string($_POST['description']);

            if(!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->where('tax_id', $tax_id)->update('taxes', [
                    'internal_name' => $_POST['internal_name'],
                    'name' => $_POST['name'],
                    'description' => $_POST['description']
                ]);

                /* Set a nice success message */
                Alerts::add_success(language()->global->success_message->basic);

                /* Refresh the page */
                redirect('admin/tax-update/' . $tax_id);

            }

        }

        /* Main View */
        $data = [
            'tax_id'       => $tax_id,
            'tax'          => $tax,
        ];

        $view = new \Altum\Views\View('admin/tax-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
