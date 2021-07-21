<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Csrf;

class AdminPlugins extends Controller {

    public function index() {

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/plugins/plugin_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Uninstall Modal */
        $view = new \Altum\Views\View('admin/plugins/plugin_uninstall_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $view = new \Altum\Views\View('admin/plugins/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }


    public function install() {

        $plugin_id = isset($this->params[0]) ? Database::clean_string($this->params[0]) : null;

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
        }

        if(!\Altum\Plugin::is_uninstalled($plugin_id)) {
            redirect('admin/plugins');
        }

        if(!is_writable(\Altum\Plugin::get($plugin_id)->path . 'config.json')) {
            Alerts::add_error(sprintf(language()->global->error_message->file_not_writable, \Altum\Plugin::get($plugin_id)->path . 'config.json'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            $class = '\Altum\Plugin\\' . $plugin_id;
            $class::install();

            /* Success message */
            Alerts::add_success(language()->global->success_message->basic);

        }

        redirect('admin/plugins');
    }

    public function uninstall() {

        $plugin_id = isset($this->params[0]) ? Database::clean_string($this->params[0]) : null;

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
        }

        if(!\Altum\Plugin::is_installed($plugin_id)) {
            redirect('admin/plugins');
        }

        if(!is_writable(\Altum\Plugin::get($plugin_id)->path . 'config.json')) {
            Alerts::add_error(sprintf(language()->global->error_message->file_not_writable, \Altum\Plugin::get($plugin_id)->path . 'config.json'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            $class = '\Altum\Plugin\\' . $plugin_id;
            $class::uninstall();

            /* Success message */
            Alerts::add_success(language()->global->success_message->basic);

        }

        redirect('admin/plugins');
    }

    public function activate() {

        $plugin_id = isset($this->params[0]) ? Database::clean_string($this->params[0]) : null;

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
        }

        if(!\Altum\Plugin::is_installed($plugin_id)) {
            redirect('admin/plugins');
        }

        if(!is_writable(\Altum\Plugin::get($plugin_id)->path . 'config.json')) {
            Alerts::add_error(sprintf(language()->global->error_message->file_not_writable, \Altum\Plugin::get($plugin_id)->path . 'config.json'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            $class = '\Altum\Plugin\\' . $plugin_id;
            $class::activate();

            /* Success message */
            Alerts::add_success(language()->global->success_message->basic);

        }

        redirect('admin/plugins');
    }

    public function disable() {

        $plugin_id = isset($this->params[0]) ? Database::clean_string($this->params[0]) : null;

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
        }

        if(!\Altum\Plugin::is_active($plugin_id)) {
            redirect('admin/plugins');
        }

        if(!is_writable(\Altum\Plugin::get($plugin_id)->path . 'config.json')) {
            Alerts::add_error(sprintf(language()->global->error_message->file_not_writable, \Altum\Plugin::get($plugin_id)->path . 'config.json'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            $class = '\Altum\Plugin\\' . $plugin_id;
            $class::disable();

            /* Success message */
            Alerts::add_success(language()->global->success_message->basic);

        }

        redirect('admin/plugins');
    }

}
