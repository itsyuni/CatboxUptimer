<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class Projects extends Controller {

    public function index() {

        Authentication::guard();

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `projects` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, 25, $_GET['page'] ?? 1, url('projects?page=%d')));

        /* Get the projects list for the user */
        $projects = [];
        $projects_result = database()->query("SELECT * FROM `projects` WHERE `user_id` = {$this->user->user_id} {$paginator->get_sql_limit()}");
        while($row = $projects_result->fetch_object()) $projects[] = $row;

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Delete Modal */
        $view = new \Altum\Views\View('projects/project_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the View */
        $data = [
            'projects' => $projects,
            'total_projects' => $total_rows,
            'pagination' => $pagination
        ];

        $view = new \Altum\Views\View('projects/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        Authentication::guard();

        if(empty($_POST)) {
            die();
        }

        $project_id = (int) Database::clean_string($_POST['project_id']);

        if(!Csrf::check()) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            redirect('projects');
        }

        if(!$project = db()->where('project_id', $project_id)->where('user_id', $this->user->user_id)->getOne('projects')) {
            redirect('projects');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the project */
            db()->where('project_id', $project_id)->delete('projects');

            /* Set a nice success message */
            Alerts::add_success(language()->project_delete_modal->success_message);

            redirect('projects');
        }

        redirect('projects');
    }
}
