<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class ProjectUpdate extends Controller {

    public function index() {

        Authentication::guard();

        $project_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$project = db()->where('project_id', $project_id)->where('user_id', $this->user->user_id)->getOne('projects')) {
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

                /* Database query */
                db()->where('project_id', $project->project_id)->update('projects', [
                    'name' => $_POST['name'],
                    'color' => $_POST['color'],
                    'last_datetime' => \Altum\Date::$date,
                ]);

                /* Set a nice success message */
                Alerts::add_success(language()->project_update->success_message);

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItem('projects?user_id=' . $this->user->user_id);

                redirect('projects');
            }
        }

        /* Delete Modal */
        $view = new \Altum\Views\View('projects/project_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the View */
        $data = [
            'project' => $project
        ];

        $view = new \Altum\Views\View('project-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
