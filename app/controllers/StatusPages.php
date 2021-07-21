<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;

class StatusPages extends Controller {

    public function index() {

        Authentication::guard();

        /* Get available custom domains */
        $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($this->user, false);

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled', 'project_id'], ['name'], ['datetime', 'name']));

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `status_pages` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('status-pages?' . $filters->get_get() . '&page=%d')));

        /* Get the status_pages */
        $status_pages = [];
        $status_pages_result = database()->query("
            SELECT
                *
            FROM
                `status_pages`
            WHERE
                `user_id` = {$this->user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}

            {$paginator->get_sql_limit()}
        ");
        while($row = $status_pages_result->fetch_object()) {

            /* Genereate the status page full URL base */
            $row->full_url = (new \Altum\Models\StatusPage())->get_status_page_full_url($row, $this->user, $domains);

            $status_pages[] = $row;
        }

        /* Export handler */
        process_export_csv($status_pages, 'include', ['status_page_id', 'domain_id', 'project_id', 'url', 'name', 'description', 'pageviews', 'is_se_visible', 'is_removed_branding', 'is_enabled', 'datetime'], sprintf(language()->status_pages->title));
        process_export_json($status_pages, 'include', ['status_page_id', 'domain_id', 'project_id', 'url', 'name', 'description', 'pageviews', 'is_se_visible', 'is_removed_branding', 'is_enabled', 'datetime'], sprintf(language()->status_pages->title));

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Delete Modal */
        $view = new \Altum\Views\View('status-page/status_page_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Prepare the View */
        $data = [
            'projects' => $projects,
            'status_pages' => $status_pages,
            'total_status_pages' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \Altum\Views\View('status-pages/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
