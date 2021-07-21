<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class AdminCodeUpdate extends Controller {

    public function index() {

        $code_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$code = db()->where('code_id', $code_id)->getOne('codes')) {
            redirect('admin/codes');
        }

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['type'] = in_array($_POST['type'], ['discount', 'redeemable']) ? Database::clean_string($_POST['type']) : 'discount';
            $_POST['days'] = $_POST['type'] == 'redeemable' ? (int) $_POST['days'] : null;
            $_POST['plan_id'] = empty($_POST['plan_id']) ? null : (int) $_POST['plan_id'];
            $_POST['discount'] = $_POST['type'] == 'redeemable' ? 100 : (int) $_POST['discount'];
            $_POST['quantity'] = (int) $_POST['quantity'];
            $_POST['code'] = trim(get_slug($_POST['code'], '-', false));

            if(!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->where('code_id', $code_id)->update('codes', [
                    'type' => $_POST['type'],
                    'days' => $_POST['days'],
                    'plan_id' => $_POST['plan_id'],
                    'code' => $_POST['code'],
                    'discount' => $_POST['discount'],
                    'quantity' => $_POST['quantity'],
                ]);

                /* Set a nice success message */
                Alerts::add_success(language()->global->success_message->basic);

                /* Refresh the page */
                redirect('admin/code-update/' . $code_id);

            }

        }

        /* Get all the plans available */
        $plans = db()->where('status', 0, '<>')->get('plans');

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/codes/code_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
            'code_id'       => $code_id,
            'code'          => $code,
            'plans'  => $plans
        ];

        $view = new \Altum\Views\View('admin/code-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
