<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Csrf;

class AdminPingServerCreate extends Controller {

    public function index() {

        if(!empty($_POST)) {
            /* Clean some posted variables */
            $_POST['name'] = trim(Database::clean_string($_POST['name']));
            $_POST['url'] = trim(Database::clean_string($_POST['url']));
            $_POST['country_code'] = array_key_exists($_POST['country_code'], get_countries_array()) ? Database::clean_string($_POST['country_code']) : 'US';
            $_POST['city_name'] = trim(Database::clean_string($_POST['city_name']));
            $_POST['is_enabled'] = (int) (bool) $_POST['is_enabled'];

            /* Check for any errors */
            $required_fields = ['name', 'url', 'city_name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]))) {
                    Alerts::add_field_error($field, language()->global->error_message->empty_field);
                }
            }

            if(!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Update the row of the database */
                db()->insert('ping_servers', [
                    'name' => $_POST['name'],
                    'url' => $_POST['url'],
                    'country_code' => $_POST['country_code'],
                    'city_name' => $_POST['city_name'],
                    'is_enabled' => $_POST['is_enabled'],
                    'datetime' => \Altum\Date::$date,
                ]);

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItem('ping_servers');

                /* Set a nice success message */
                Alerts::add_success(language()->global->success_message->basic);

                redirect('admin/ping-servers');
            }

        }

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/ping-servers/ping_server_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [];

        $view = new \Altum\Views\View('admin/ping-server-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
