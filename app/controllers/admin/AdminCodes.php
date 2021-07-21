<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class AdminCodes extends Controller {

    public function index() {

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/codes/code_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        $codes_result = database()->query("
            SELECT `codes`.*, `plans`.`name` AS `plan_name`
            FROM `codes`
            LEFT JOIN `plans` ON `codes`.`plan_id` = `plans`.`plan_id`
        ");

        /* Main View */
        $data = [
            'codes_result' => $codes_result
        ];

        $view = new \Altum\Views\View('admin/codes/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        $code_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the code */
            db()->where('code_id', $code_id)->delete('codes');

            /* Success message */
            Alerts::add_success(language()->admin_code_delete_modal->success_message);

        }

        redirect('admin/codes');
    }

}
