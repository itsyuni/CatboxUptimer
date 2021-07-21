<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Models\Domain;

class Domains extends Controller {

    public function index() {

        Authentication::guard();

        if(!settings()->status_pages->domains_is_enabled) {
            redirect('dashboard');
        }

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `domains` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, 25, $_GET['page'] ?? 1, url('domains?page=%d')));

        /* Get the domains list for the user */
        $domains = [];
        $domains_result = database()->query("SELECT * FROM `domains` WHERE `user_id` = {$this->user->user_id} {$paginator->get_sql_limit()}");
        while($row = $domains_result->fetch_object()) $domains[] = $row;

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Establish the account sub menu view */
        $menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $menu->run());

        /* Delete Modal */
        $view = new \Altum\Views\View('domains/domain_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the View */
        $data = [
            'domains' => $domains,
            'total_domains' => $total_rows,
            'pagination' => $pagination
        ];

        $view = new \Altum\Views\View('domains/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        Authentication::guard();

        if(empty($_POST)) {
            die();
        }

        $domain_id = (int) Database::clean_string($_POST['domain_id']);

        if(!Csrf::check()) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            redirect('domains');
        }

        if(!$domain = db()->where('domain_id', $domain_id)->where('user_id', $this->user->user_id)->getOne('domains', ['domain_id'])) {
            redirect('domains');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            (new Domain())->delete($domain->domain_id);

            /* Set a nice success message */
            Alerts::add_success(language()->domain_delete_modal->success_message);

            redirect('domains');
        }

        redirect('domains');
    }
}
