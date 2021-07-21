<?php

namespace Altum\Controllers;

use Altum\Response;
use Altum\Traits\Apiable;

class ApiStatusPages extends Controller {
    use Apiable;

    public function index() {

        $this->verify_request();

        /* Decide what to continue with */
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':

                /* Detect if we only need an object, or the whole list */
                if(isset($this->params[0])) {
                    $this->get();
                } else {
                    $this->get_all();
                }

            break;
        }

        $this->return_404();
    }

    private function get_all() {

        /* Get available custom domains */
        $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($this->api_user, false);

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], [], []));

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `status_pages` WHERE `user_id` = {$this->api_user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('api/payments?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $data = [];
        $data_result = database()->query("
            SELECT
                *
            FROM
                `status_pages`
            WHERE
                `user_id` = {$this->api_user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $data_result->fetch_object()) {

            /* Genereate the status page full URL base */
            $row->full_url = (new \Altum\Models\StatusPage())->get_status_page_full_url($row, $this->api_user, $domains);

            /* Prepare the data */
            $row = [
                'id' => (int) $row->status_page_id,
                'domain_id' => (int) $row->domain_id,
                'monitors_ids' => json_decode($row->monitors_ids),
                'project_id' => (int) $row->project_id,
                'url' => $row->url,
                'full_url' => $row->full_url,
                'name' => $row->name,
                'description' => $row->description,
                'socials' => json_decode($row->socials),
                'logo_url' => UPLOADS_FULL_URL . 'status_pages_logos/' . $row->logo,
                'favicon_url' => UPLOADS_FULL_URL . 'status_pages_favicons/' . $row->favicon,
                'password' => (bool) $row->password,
                'timezone' => $row->timezone,
                'custom_js' => $row->custom_js,
                'custom_css' => $row->custom_css,
                'pageviews' => (int) $row->pageviews,
                'is_se_visible' => (bool) $row->is_se_visible,
                'is_removed_branding' => (bool) $row->is_removed_branding,
                'is_enabled' => (bool) $row->is_enabled,
                'datetime' => $row->datetime
            ];

            $data[] = $row;
        }

        /* Prepare the data */
        $meta = [
            'page' => $_GET['page'] ?? 1,
            'total_pages' => $paginator->getNumPages(),
            'results_per_page' => $filters->get_results_per_page(),
            'total_results' => (int) $total_rows,
        ];

        /* Prepare the pagination links */
        $others = ['links' => [
            'first' => $paginator->getPageUrl(1),
            'last' => $paginator->getNumPages() ? $paginator->getPageUrl($paginator->getNumPages()) : null,
            'next' => $paginator->getNextUrl(),
            'prev' => $paginator->getPrevUrl(),
            'self' => $paginator->getPageUrl($_GET['page'] ?? 1)
        ]];

        Response::jsonapi_success($data, $meta, 200, $others);
    }

    private function get() {

        $status_page_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $status_page = db()->where('status_page_id', $status_page_id)->where('user_id', $this->api_user->user_id)->getOne('status_pages');

        /* We haven't found the resource */
        if(!$status_page) {
            Response::jsonapi_error([[
                'title' => language()->api->error_message->not_found,
                'status' => '404'
            ]], null, 404);
        }

        /* Genereate the status page full URL base */
        $status_page->full_url = (new \Altum\Models\StatusPage())->get_status_page_full_url($status_page, $this->api_user);

        /* Prepare the data */
        $data = [
            'id' => (int) $status_page->status_page_id,
            'domain_id' => (int) $status_page->domain_id,
            'monitors_ids' => json_decode($status_page->monitors_ids),
            'project_id' => (int) $status_page->project_id,
            'url' => $status_page->url,
            'full_url' => $status_page->full_url,
            'name' => $status_page->name,
            'description' => $status_page->description,
            'socials' => json_decode($status_page->socials),
            'logo_url' => $status_page->logo ? UPLOADS_FULL_URL . 'status_pages_logos/' . $status_page->logo : '',
            'favicon_url' => $status_page->favicon ? UPLOADS_FULL_URL . 'status_pages_favicons/' . $status_page->favicon : '',
            'password' => (bool) $status_page->password,
            'timezone' => $status_page->timezone,
            'custom_js' => $status_page->custom_js,
            'custom_css' => $status_page->custom_css,
            'pageviews' => (int) $status_page->pageviews,
            'is_se_visible' => (bool) $status_page->is_se_visible,
            'is_removed_branding' => (bool) $status_page->is_removed_branding,
            'is_enabled' => (bool) $status_page->is_enabled,
            'datetime' => $status_page->datetime
        ];

        Response::jsonapi_success($data);

    }

}
