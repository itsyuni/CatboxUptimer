<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Models\User;

class AccountDelete extends Controller {

    public function index() {

        Authentication::guard();

        if(!empty($_POST)) {

            /* Check for any errors */
            if(!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }

            if(!password_verify($_POST['current_password'], $this->user->password)) {
                Alerts::add_field_error('current_password', language()->account->error_message->invalid_current_password);
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Delete the user */
                (new User())->delete($this->user->user_id);

                Authentication::logout();

            }

        }

        /* Establish the account sub menu view */
        $menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $menu->run());

        /* Prepare the View */
        $view = new \Altum\Views\View('account-delete/index', (array) $this);

        $this->add_view_content('content', $view->run([]));

    }

}
