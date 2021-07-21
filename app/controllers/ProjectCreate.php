<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class ProjectCreate extends Controller {

    public function index() {

        Authentication::guard();

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `projects` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->projects_limit != -1 && $total_rows >= $this->user->plan_settings->projects_limit) {
            Alerts::add_info(language()->projects->error_message->projects_limit);
            redirect('projects');
        }

        if(!empty($_POST)) {
            $_POST['name'] = trim(Database::clean_string($_POST['name']));
            $_POST['color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['color']) ? '#000' : $_POST['color'];

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

                /* Prepare the statement and execute query */
                db()->insert('projects', [
                    'user_id' => $this->user->user_id,
                    'name' => $_POST['name'],
                    'color' => $_POST['color'],
                    'datetime' => \Altum\Date::$date,
                ]);

                /* Set a nice success message */
                Alerts::add_success(language()->project_create->success_message);

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItem('projects?user_id=' . $this->user->user_id);

                redirect('projects');
            }
        }

        /* Delete Modal */
        $view = new \Altum\Views\View('projects/project_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the View */
        $data = [];

        $view = new \Altum\Views\View('project-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
