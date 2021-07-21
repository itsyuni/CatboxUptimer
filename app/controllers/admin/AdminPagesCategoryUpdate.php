<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Csrf;

class AdminPagesCategoryUpdate extends Controller {

    public function index() {

        $pages_category_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Check if user exists */
        if(!$pages_category = db()->where('pages_category_id', $pages_category_id)->getOne('pages_categories')) {
            redirect('admin/pages');
        }

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['url'] = get_slug(Database::clean_string($_POST['url']));
            $_POST['title'] = Database::clean_string($_POST['title']);
            $_POST['description'] = Database::clean_string($_POST['description']);
            $_POST['icon'] = Database::clean_string($_POST['icon']);
            $_POST['order'] = (int) $_POST['order'] ?? 0;

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
                db()->where('pages_category_id', $pages_category_id)->update('pages_categories', [
                    'url' => $_POST['url'],
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'icon' => $_POST['icon'],
                    'order' => $_POST['order'],
                ]);

                /* Set a nice success message */
                Alerts::add_success(language()->global->success_message->basic);

                redirect('admin/pages-category-update/' . $pages_category->pages_category_id);

            }
        }

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/pages/pages_category_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
            'pages_category' => $pages_category
        ];

        $view = new \Altum\Views\View('admin/pages-category-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
