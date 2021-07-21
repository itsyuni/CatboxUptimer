<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Csrf;

class AdminPageUpdate extends Controller {

    public function index() {

        $page_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Check if user exists */
        if(!$page = db()->where('page_id', $page_id)->getOne('pages')) {
            redirect('admin/pages');
        }

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['title'] = Database::clean_string($_POST['title']);
            $_POST['description'] = Database::clean_string($_POST['description']);
            $_POST['type'] = in_array($_POST['type'], ['internal', 'external']) ? Database::clean_string($_POST['type']) : 'internal';
            $_POST['position'] = in_array($_POST['position'], ['hidden', 'top', 'bottom']) ? $_POST['position'] : 'top';
            $_POST['pages_category_id'] = empty($_POST['pages_category_id']) ? null : (int) $_POST['pages_category_id'];
            $_POST['order'] = (int) $_POST['order'];

            switch($_POST['type']) {
                case 'internal':
                    $_POST['url'] = get_slug(Database::clean_string($_POST['url']));
                    break;


                case 'external':
                    $_POST['url'] = Database::clean_string($_POST['url']);
                    break;
            }

            /* Check for any errors */
            $required_fields = ['title', 'url'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]))) {
                    Alerts::add_field_error($field, language()->global->error_message->empty_field);
                }
            }

            if(!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }

            /* If there are no errors, continue */
            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->where('page_id', $page->page_id)->update('pages', [
                    'pages_category_id' => $_POST['pages_category_id'],
                    'url' => $_POST['url'],
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'content' => $_POST['content'],
                    'type' => $_POST['type'],
                    'position' => $_POST['position'],
                    'order' => $_POST['order'],
                    'last_date' => \Altum\Date::$date,
                ]);

                /* Clear cache */
                \Altum\Cache::$adapter->deleteItems(['pages_hidden', 'pages_top', 'pages_bottom']);

                /* Set a nice success message */
                Alerts::add_success(language()->global->success_message->basic);

                redirect('admin/page-update/' . $page_id);
            }
        }

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/pages/page_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Get the pages categories available */
        $pages_categories = db()->get('pages_categories', null, ['pages_category_id', 'title']);

        /* Main View */
        $data = [
            'pages_categories' => $pages_categories,
            'page' => $page
        ];

        $view = new \Altum\Views\View('admin/page-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
