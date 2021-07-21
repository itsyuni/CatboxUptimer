<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class HeartbeatCreate extends Controller {

    public function index() {

        Authentication::guard();

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `heartbeats` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->heartbeats_limit != -1 && $total_rows >= $this->user->plan_settings->heartbeats_limit) {
            Alerts::add_info(language()->heartbeat->error_message->heartbeats_limit);
            redirect('heartbeats');
        }

        /* Get available projects servers */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        if(!empty($_POST)) {
            $_POST['name'] = trim(Database::clean_string($_POST['name']));
            $_POST['run_interval'] = (int) $_POST['run_interval'];
            $_POST['run_interval_type'] = in_array($_POST['run_interval_type'], ['seconds', 'minutes', 'hours', 'days']) ? $_POST['run_interval_type'] : 'seconds';
            $_POST['run_interval_grace'] = (int) $_POST['run_interval_grace'];
            $_POST['run_interval_grace_type'] = in_array($_POST['run_interval_grace_type'], ['seconds', 'minutes', 'hours', 'days']) ? $_POST['run_interval_grace_type'] : 'seconds';
            $_POST['project_id'] = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null;
            $_POST['email_notifications_is_enabled'] = (int) (bool) $_POST['email_notifications_is_enabled'];
            $_POST['email_reports_is_enabled'] = (int) (bool) $_POST['email_reports_is_enabled'];

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

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $code = md5(time() . $_POST['name'] . $this->user->user_id . microtime());
                $next_run_datetime = (new \DateTime())->modify('+5 years')->format('Y-m-d H:i:s');
                $settings = json_encode([
                    'run_interval' => $_POST['run_interval'],
                    'run_interval_type' => $_POST['run_interval_type'],
                    'run_interval_grace' => $_POST['run_interval_grace'],
                    'run_interval_grace_type' => $_POST['run_interval_grace_type'],
                ]);

                $notifications = json_encode([
                    'email_is_enabled' => $_POST['email_notifications_is_enabled'],
                    'webhook' => Database::clean_string($_POST['webhook_notifications']),
                    'slack' => Database::clean_string($_POST['slack_notifications']),
                    'twilio' => Database::clean_string($_POST['twilio_notifications']) ?? null,
                ]);

                /* Prepare the statement and execute query */
                $heartbeat_id = db()->insert('heartbeats', [
                    'project_id' => $_POST['project_id'],
                    'user_id' => $this->user->user_id,
                    'name' => $_POST['name'],
                    'code' => $code,
                    'settings' => $settings,
                    'notifications' => $notifications,
                    'email_reports_is_enabled' => $_POST['email_reports_is_enabled'],
                    'email_reports_last_datetime' => \Altum\Date::$date,
                    'next_run_datetime' => $next_run_datetime,
                    'datetime' => \Altum\Date::$date,
                ]);

                /* Set a nice success message */
                Alerts::add_success(language()->heartbeat_create->success_message);

                redirect('heartbeat/' . $heartbeat_id);
            }

        }

        /* Set default values */
        $values = [
            'name' => $_POST['name'] ?? '',
            'run_interval' => $_POST['run_interval'] ?? 1,
            'run_interval_type' => $_POST['run_interval_type'] ?? 'hours',
            'run_interval_grace' => $_POST['run_interval_grace'] ?? 5,
            'run_interval_grace_type' => $_POST['run_interval_grace_type'] ?? 'minutes',
            'email_notifications_is_enabled' => $_POST['email_notifications_is_enabled'] ?? 1,
            'email_reports_is_enabled' => $_POST['email_reports_is_enabled'] ?? 1,
            'webhook_notifications' => $_POST['webhook_notifications'] ?? null,
            'slack_notifications' => $_POST['slack_notifications'] ?? null,
            'twilio_notifications' => $_POST['twilio_notifications'] ?? null,
            'project_id' => $_POST['project_id'] ?? '',
        ];

        /* Prepare the View */
        $data = [
            'projects' => $projects,
            'values' => $values
        ];

        $view = new \Altum\Views\View('heartbeat-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
