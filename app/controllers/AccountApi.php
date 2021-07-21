<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class AccountApi extends Controller {

    public function index() {

        Authentication::guard();

        if(!empty($_POST)) {

            /* Clean some posted variables */
            $api_key = md5($_POST['email'] . microtime() . microtime());

            /* Check for any errors */
            if(!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->where('user_id', $this->user->user_id)->update('users', ['api_key' => $api_key]);

                /* Set a nice success message */
                Alerts::add_success(language()->account_api->success_message);

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('user_id=' . $this->user->user_id);

                redirect('account-api');
            }

        }

        /* Establish the account sub menu view */
        $menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $menu->run());

        /* Prepare the View */
        $view = new \Altum\Views\View('account-api/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }

}
