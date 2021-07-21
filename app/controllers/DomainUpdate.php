<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class DomainUpdate extends Controller {

    public function index() {

        Authentication::guard();

        $domain_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$domain = db()->where('domain_id', $domain_id)->where('user_id', $this->user->user_id)->getOne('domains')) {
            redirect('domains');
        }

        if(!empty($_POST)) {
            $_POST['scheme'] = isset($_POST['scheme']) && in_array($_POST['scheme'], ['http://', 'https://']) ? Database::clean_string($_POST['scheme']) : 'https://';
            $_POST['host'] = mb_strtolower(trim($_POST['host']));
            $_POST['custom_index_url'] = trim(Database::clean_string($_POST['custom_index_url']));
            $_POST['custom_not_found_url'] = trim(Database::clean_string($_POST['custom_not_found_url']));
            $type = 0;
            $is_enabled = $domain->is_enabled;

            /* Set the domain to pending if domain has changed */
            if($domain->host != $_POST['host']) {
                $is_enabled = 0;
            }

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

                /* Database query */
                db()->where('domain_id', $domain->domain_id)->update('domains', [
                    'scheme' => $_POST['scheme'],
                    'host' => $_POST['host'],
                    'custom_index_url' => $_POST['custom_index_url'],
                    'custom_not_found_url' => $_POST['custom_not_found_url'],
                    'is_enabled' => $is_enabled,
                    'last_datetime' => \Altum\Date::$date,
                ]);

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('domain_id=' . $domain->domain_id);

                /* Send notification to admin if needed */
                if(!$is_enabled && settings()->email_notifications->new_domain && !empty(settings()->email_notifications->emails)) {

                    /* Prepare the email */
                    $email_template = get_email_template(
                        [],
                        language()->global->emails->admin_new_domain_notification->subject,
                        [
                            '{{ADMIN_DOMAIN_UPDATE_LINK}}' => url('admin/domain-update/' . $domain->domain_id),
                            '{{DOMAIN_HOST}}' => $_POST['host'],
                            '{{NAME}}' => $this->user->name,
                            '{{EMAIL}}' => $this->user->email,
                        ],
                        language()->global->emails->admin_new_domain_notification->body
                    );

                    send_mail(explode(',', settings()->email_notifications->emails), $email_template->subject, $email_template->body);

                }

                /* Set a nice success message */
                Alerts::add_success(language()->domain_update->success_message);

                redirect('domains');
            }
        }

        /* Delete Modal */
        $view = new \Altum\Views\View('domains/domain_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the View */
        $data = [
            'domain' => $domain
        ];

        $view = new \Altum\Views\View('domain-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
