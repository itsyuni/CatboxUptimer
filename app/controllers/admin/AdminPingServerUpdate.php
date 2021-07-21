<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Csrf;

class AdminPingServerUpdate extends Controller {

    public function index() {

        $ping_server_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Check if user exists */
        if(!$ping_server = db()->where('ping_server_id', $ping_server_id)->getOne('ping_servers')) {
            redirect('admin/ping-servers');
        }

        if(!empty($_POST)) {
            /* Clean some posted variables */
            $_POST['name'] = trim(Database::clean_string($_POST['name']));
            $_POST['url'] = trim(Database::clean_string($_POST['url']));
            $_POST['country_code'] = array_key_exists($_POST['country_code'], get_countries_array()) ? Database::clean_string($_POST['country_code']) : 'US';
            $_POST['city_name'] = trim(Database::clean_string($_POST['city_name']));
            $_POST['is_enabled'] = $ping_server->ping_server_id == 1 ? 1 : (int) (bool) $_POST['is_enabled'];

            /* Check for any errors */
            $required_fields = ['name', 'city_name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]))) {
                    Alerts::add_field_error($field, language()->global->error_message->empty_field);
                }
            }

            if(empty($_POST['url']) && $ping_server->ping_server_id != 1) {
                Alerts::add_field_error('url', language()->global->error_message->empty_field);
            }

            if(!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Update the row of the database */
                db()->where('ping_server_id', $ping_server->ping_server_id)->update('ping_servers', [
                    'name' => $_POST['name'],
                    'url' => $_POST['url'],
                    'country_code' => $_POST['country_code'],
                    'city_name' => $_POST['city_name'],
                    'is_enabled' => $_POST['is_enabled'],
                    'last_datetime' => \Altum\Date::$date,
                ]);

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItem('ping_servers');

                /* Set a nice success message */
                Alerts::add_success(language()->global->success_message->basic);

                redirect('admin/ping-server-update/' . $ping_server->ping_server_id);
            }

        }

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/ping-servers/ping_server_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
            'ping_server' => $ping_server
        ];

        $view = new \Altum\Views\View('admin/ping-server-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
