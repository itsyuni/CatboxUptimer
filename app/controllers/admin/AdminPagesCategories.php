<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Middlewares\Csrf;

class AdminPagesCategories extends Controller {

    public function index() {

       redirect('pages');

    }

    public function delete() {

        $pages_category_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the page */
            db()->where('pages_category_id', $pages_category_id)->delete('pages_categories');

            /* Set a nice success message */
            Alerts::add_success(language()->admin_pages_category_delete_modal->success_message);

        }

        redirect('admin/pages');
    }

}
