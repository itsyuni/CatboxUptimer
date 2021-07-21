<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Csrf;

class AdminDomainUpdate extends Controller {

    public function index() {

        $domain_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Check if user exists */
        if(!$domain = db()->where('domain_id', $domain_id)->getOne('domains')) {
            redirect('admin/domains');
        }

        /* Get some user details of the domain owner */
        $user = db()->where('user_id', $domain->user_id)->getOne('users', ['user_id', 'email', 'name']);

        if(!empty($_POST)) {
            /* Clean some posted variables */
            $_POST['scheme'] = isset($_POST['scheme']) && in_array($_POST['scheme'], ['http://', 'https://']) ? Database::clean_string($_POST['scheme']) : 'https://';
            $_POST['host'] = mb_strtolower(trim($_POST['host']));
            $_POST['custom_index_url'] = trim(Database::clean_string($_POST['custom_index_url']));
            $_POST['custom_not_found_url'] = trim(Database::clean_string($_POST['custom_not_found_url']));
            $_POST['is_enabled'] = (int) (bool) $_POST['is_enabled'];

            /* Check for any errors */
            $required_fields = ['host'];
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
                db()->where('domain_id', $domain->domain_id)->update('domains', [
                    'scheme' => $_POST['scheme'],
                    'host' => $_POST['host'],
                    'custom_index_url' => $_POST['custom_index_url'],
                    'custom_not_found_url' => $_POST['custom_not_found_url'],
                    'is_enabled' => $_POST['is_enabled'],
                ]);

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('domain_id=' . $domain->domain_id);

                /* Set a nice success message */
                Alerts::add_success(language()->global->success_message->basic);

                redirect('admin/domain-update/' . $domain->domain_id);
            }

        }

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/domains/domain_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
            'user' => $user,
            'domain' => $domain
        ];

        $view = new \Altum\Views\View('admin/domain-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
