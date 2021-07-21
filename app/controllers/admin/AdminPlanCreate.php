<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Csrf;

class AdminPlanCreate extends Controller {

    public function index() {

        if(in_array(settings()->license->type, ['Extended License', 'extended'])) {
            /* Get the available taxes from the system */
            $taxes = db()->get('taxes', null, ['tax_id', 'internal_name', 'name', 'description']);
        }

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name'] = Database::clean_string($_POST['name']);
            $_POST['monthly_price'] = (float) $_POST['monthly_price'];
            $_POST['annual_price'] = (float) $_POST['annual_price'];
            $_POST['lifetime_price'] = (float) $_POST['lifetime_price'];

            $_POST['settings'] = json_encode([
                'monitors_limit'                    => (int) $_POST['monitors_limit'],
                'heartbeats_limit'                  => (int) $_POST['heartbeats_limit'],
                'status_pages_limit'                => (int) $_POST['status_pages_limit'],
                'projects_limit'                    => (int) $_POST['projects_limit'],
                'domains_limit'                     => (int) $_POST['domains_limit'],
                'logs_retention'                    => (int) $_POST['logs_retention'],

                'additional_domains_is_enabled'     => (bool) isset($_POST['additional_domains_is_enabled']),
                'analytics_is_enabled'              => (bool) isset($_POST['analytics_is_enabled']),
                'removable_branding_is_enabled'     => (bool) isset($_POST['removable_branding_is_enabled']),
                'custom_url_is_enabled'             => (bool) isset($_POST['custom_url_is_enabled']),
                'password_protection_is_enabled'    => (bool) isset($_POST['password_protection_is_enabled']),
                'search_engine_block_is_enabled'    => (bool) isset($_POST['search_engine_block_is_enabled']),
                'custom_css_is_enabled'             => (bool) isset($_POST['custom_css_is_enabled']),
                'custom_js_is_enabled'              => (bool) isset($_POST['custom_js_is_enabled']),
                'email_reports_is_enabled'          => (bool) isset($_POST['email_reports_is_enabled']),
                'email_notifications_is_enabled'    => (bool) isset($_POST['email_notifications_is_enabled']),
                'twilio_notifications_is_enabled'   => (bool) isset($_POST['twilio_notifications_is_enabled']),
                'api_is_enabled'                    => (bool) isset($_POST['api_is_enabled']),
                'no_ads'                            => (bool) isset($_POST['no_ads'])
            ]);

            $_POST['status'] = (int) $_POST['status'];
            $_POST['order'] = (int) $_POST['order'];
            $_POST['taxes_ids'] = json_encode(array_keys($_POST['taxes_ids'] ?? []));

            if(!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->insert('plans', [
                    'name' => $_POST['name'],
                    'monthly_price' => $_POST['monthly_price'],
                    'annual_price' => $_POST['annual_price'],
                    'lifetime_price' => $_POST['lifetime_price'],
                    'settings' => $_POST['settings'],
                    'taxes_ids' => $_POST['taxes_ids'],
                    'status' => $_POST['status'],
                    'order' => $_POST['order'],
                    'date' => \Altum\Date::$date,
                ]);

                /* Set a nice success message */
                Alerts::add_success(language()->global->success_message->basic);

                redirect('admin/plans');
            }
        }


        /* Main View */
        $data = [
            'taxes' => $taxes ?? null
        ];

        $view = new \Altum\Views\View('admin/plan-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
