<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class HeartbeatUpdate extends Controller {

    public function index() {

        Authentication::guard();

        $heartbeat_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$heartbeat = db()->where('heartbeat_id', $heartbeat_id)->where('user_id', $this->user->user_id)->getOne('heartbeats')) {
            redirect('heartbeats');
        }
        $heartbeat->settings = json_decode($heartbeat->settings);
        $heartbeat->notifications = json_decode($heartbeat->notifications);

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
            $_POST['is_enabled'] = (int) (bool) $_POST['is_enabled'];

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
                db()->where('heartbeat_id', $heartbeat->heartbeat_id)->update('heartbeats', [
                    'project_id' => $_POST['project_id'],
                    'name' => $_POST['name'],
                    'settings' => $settings,
                    'notifications' => $notifications,
                    'email_reports_is_enabled' => $_POST['email_reports_is_enabled'],
                    'is_enabled' => $_POST['is_enabled'],
                    'last_datetime' => \Altum\Date::$date,
                ]);

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('heartbeat_id=' . $heartbeat_id);

                /* Set a nice success message */
                Alerts::add_success(language()->heartbeat_update->success_message);

                redirect('heartbeat-update/' . $heartbeat_id);
            }

        }

        /* Delete Modal */
        $view = new \Altum\Views\View('heartbeat/heartbeat_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Set a custom title */
        Title::set(sprintf(language()->heartbeat_update->title, $heartbeat->name));

        /* Prepare the View */
        $data = [
            'projects' => $projects,
            'heartbeat' => $heartbeat
        ];

        $view = new \Altum\Views\View('heartbeat-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
