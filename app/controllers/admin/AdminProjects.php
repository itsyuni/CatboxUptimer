<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Middlewares\Csrf;

class AdminProjects extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id'], ['name'], ['datetime', 'name']));

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `projects` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/projects?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $projects = [];
        $projects_result = database()->query("
            SELECT
                `projects`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `projects`
            LEFT JOIN
                `users` ON `projects`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('projects')}
                {$filters->get_sql_order_by('projects')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $projects_result->fetch_object()) {
            $projects[] = $row;
        }

        /* Export handler */
        process_export_csv($projects, 'include', ['project_id', 'user_id', 'name', 'color', 'last_datetime', 'datetime'], sprintf(language()->admin_projects->title));
        process_export_json($projects, 'include', ['project_id', 'user_id', 'name', 'color', 'last_datetime', 'datetime'], sprintf(language()->admin_projects->title));

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/projects/project_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
            'projects' => $projects,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\Views\View('admin/projects/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        $project_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
        }

        if(!$project = db()->where('project_id', $project_id)->getOne('projects', ['project_id'])) {
            redirect('admin/projects');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the project */
            db()->where('project_id', $project->project_id)->delete('projects');

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('project_id=' . $project->project_id);

            /* Set a nice success message */
            Alerts::add_success(language()->admin_project_delete_modal->success_message);

        }

        redirect('admin/projects');
    }

}
