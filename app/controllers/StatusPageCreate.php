<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class StatusPageCreate extends Controller {

    public function index() {

        Authentication::guard();

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `status_pages` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->status_pages_limit != -1 && $total_rows >= $this->user->plan_settings->status_pages_limit) {
            Alerts::add_info(language()->status_page->error_message->status_pages_limit);
            redirect('status-pages');
        }

        /* Get available custom domains */
        $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($this->user);

        /* Get all the available monitors */
        $monitors = (new \Altum\Models\Monitors())->get_monitors_by_user_id($this->user->user_id);

        if(!empty($_POST)) {
            $_POST['url'] = !empty($_POST['url']) && $this->user->plan_settings->custom_url_is_enabled ? get_slug(Database::clean_string($_POST['url'])) : false;
            $_POST['name'] = trim(Database::clean_string($_POST['name']));
            $_POST['description'] = trim(Database::clean_string($_POST['description']));

            $_POST['domain_id'] = isset($_POST['domain_id']) && isset($domains[$_POST['domain_id']]) ? (!empty($_POST['domain_id']) ? (int) $_POST['domain_id'] : null) : null;
            $_POST['is_main_status_page'] = (bool) isset($_POST['is_main_status_page']) && isset($domains[$_POST['domain_id']]) && $domains[$_POST['domain_id']]->type == 0;
            $_POST['monitors_ids'] = empty($_POST['monitors_ids']) ? [] : array_map(
                function($monitor_id) {
                    return (int) $monitor_id;
                },
                array_filter($_POST['monitors_ids'], function($monitor_id) use($monitors) {
                    return array_key_exists($monitor_id, $monitors);
                })
            );

            /* Check for any errors */
            $required_fields = ['name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]))) {
                    Alerts::add_field_error($field, language()->global->error_message->empty_field);
                }
            }

            if(!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }

            /* Check for duplicate url if needed */
            if($_POST['url']) {

                $domain_id_where = $_POST['domain_id'] ? "AND `domain_id` = {$_POST['domain_id']}" : "AND `domain_id` IS NULL";
                $is_existing_status_page = database()->query("SELECT `status_page_id` FROM `status_pages` WHERE `url` = '{$_POST['url']}' {$domain_id_where}")->num_rows;

                if($is_existing_status_page) {
                   Alerts::add_field_error('url', language()->status_page->error_message->url_exists);
                }

            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $timezone = $this->user->timezone;
                $theme = 'new-york';
                $monitors_ids = json_encode($_POST['monitors_ids']);
                $socials = [];
                foreach(require APP_PATH . 'includes/s/socials.php' as $key => $value) {
                    $socials[$key] = '';
                }
                $socials = json_encode($socials);

                if(!$_POST['url']) {
                    $is_existing_status_page = true;

                    /* Generate random url if not specified */
                    while($is_existing_status_page) {
                        $_POST['url'] = mb_strtolower(string_generate(10));

                        $domain_id_where = $_POST['domain_id'] ? "AND `domain_id` = {$_POST['domain_id']}" : "AND `domain_id` IS NULL";
                        $is_existing_status_page = database()->query("SELECT `status_page_id` FROM `status_pages` WHERE `url` = '{$_POST['url']}' {$domain_id_where}")->num_rows;
                    }

                }

                /* Prepare the statement and execute query */
                $status_page_id = db()->insert('status_pages', [
                    'user_id' => $this->user->user_id,
                    'domain_id' => $_POST['domain_id'],
                    'monitors_ids' => $monitors_ids,
                    'url' => $_POST['url'],
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'timezone' => $timezone,
                    'socials' => $socials,
                    'theme' => $theme,
                    'datetime' => \Altum\Date::$date,
                ]);

                /* Update custom domain if needed */
                if($_POST['is_main_status_page']) {

                    /* Database query */
                    db()->where('domain_id', $_POST['domain_id'])->update('domains', ['status_page_id' => $status_page_id, 'last_datetime' => \Altum\Date::$date]);

                }

                /* Set a nice success message */
                Alerts::add_success(language()->status_page_create->success_message);

                redirect('status-pages');
            }

        }

        /* Set default values */
        $values = [
            'url' => $_POST['url'] ?? '',
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'domain_id' => $_POST['domain_id'] ?? '',
            'is_main_status_page' => $_POST['is_main_status_page'] ?? '',
            'monitors_ids' => $_POST['monitors_ids'] ?? []
        ];

        /* Prepare the View */
        $data = [
            'monitors' => $monitors,
            'domains' => $domains,
            'values' => $values
        ];

        $view = new \Altum\Views\View('status-page-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
