<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Middlewares\Csrf;

class AdminStatusPages extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled', 'user_id', 'domain_id', 'project_id'], ['name'], ['datetime', 'name', 'pageviews']));

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `status_pages` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/status_pages?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $status_pages = [];
        $status_pages_result = database()->query("
            SELECT
                `status_pages`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `status_pages`
            LEFT JOIN
                `users` ON `status_pages`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('status_pages')}
                {$filters->get_sql_order_by('status_pages')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $status_pages_result->fetch_object()) {
            $status_pages[] = $row;
        }

        /* Export handler */
        process_export_csv($status_pages, 'include', ['status_page_id', 'user_id', 'domain_id', 'project_id', 'url', 'name', 'description', 'pageviews', 'is_se_visible', 'is_removed_branding', 'is_enabled', 'datetime'], sprintf(language()->status_pages->title));
        process_export_json($status_pages, 'include', ['status_page_id', 'user_id', 'domain_id', 'project_id', 'url', 'name', 'description', 'pageviews', 'is_se_visible', 'is_removed_branding', 'is_enabled', 'datetime'], sprintf(language()->status_pages->title));

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/status-pages/status_page_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
            'status_pages' => $status_pages,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\Views\View('admin/status-pages/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        $status_page_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
        }

        if(!$status_page = db()->where('status_page_id', $status_page_id)->getOne('status_pages', ['status_page_id', 'logo', 'favicon'])) {
            redirect('admin/status-pages');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            (new \Altum\Models\StatusPage())->delete_uploads($status_page->favicon, $status_page->logo);
            (new \Altum\Models\StatusPage())->delete($status_page->status_page_id);

            /* Set a nice success message */
            Alerts::add_success(language()->admin_status_page_delete_modal->success_message);

        }

        redirect('admin/status-pages');
    }

}
