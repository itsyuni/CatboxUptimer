<?php

namespace Altum\Controllers;

use Altum\Models\Page;
use Altum\Routing\Router;
use Altum\Traits\Paramsable;

class Controller {
    use Paramsable;

    public $views = [];

    public function __construct(Array $params = []) {

        $this->add_params($params);

    }

    public function add_view_content($name, $data) {

        $this->views[$name] = $data;

    }

    public function run() {

        /* Do we need to show something? */
        if(!Router::$controller_settings['has_view']) {
            return;
        }

        if(Router::$path == 's') {

            /* Establish the footer view */
            $footer = new \Altum\Views\View('s/partials/footer', (array) $this);
            $this->add_view_content('footer', $footer->run());

            $wrapper = new \Altum\Views\View('s/wrapper', (array) $this);
        }

        if(Router::$path == '') {

            /* Get the top menu custom pages */
            $top_pages = (new Page())->get_pages('top');

            /* Get the footer pages */
            $bottom_pages = (new Page())->get_pages('bottom');

            /* Normal warpper ( not logged in ) */
            if(Router::$controller_settings['wrapper'] == 'wrapper') {

                /* Establish the menu view */
                $menu = new \Altum\Views\View('partials/menu', (array) $this);
                $this->add_view_content('menu', $menu->run(['pages' => $top_pages]));

                /* Establish the footer view */
                $footer = new \Altum\Views\View('partials/footer', (array) $this);
                $this->add_view_content('footer', $footer->run(['pages' => $bottom_pages]));
            } else

            /* App wrapper logged in users */
            if(Router::$controller_settings['wrapper'] == 'app_wrapper') {

                $sidebar = new \Altum\Views\View('partials/app_sidebar', (array) $this);
                $this->add_view_content('app_sidebar', $sidebar->run(['pages' => $top_pages]));

                $menu = new \Altum\Views\View('partials/app_menu', (array) $this);
                $this->add_view_content('app_menu', $menu->run(['pages' => $top_pages]));

                /* Establish the footer view */
                $footer = new \Altum\Views\View('partials/footer', (array) $this);
                $this->add_view_content('footer', $footer->run(['pages' => $bottom_pages]));

            }

            $wrapper = new \Altum\Views\View(Router::$controller_settings['wrapper'], (array) $this);
        }

        if(Router::$path == 'admin') {
            /* Establish the side menu view */
            $sidebar = new \Altum\Views\View('admin/partials/admin_sidebar', (array) $this);
            $this->add_view_content('admin_sidebar', $sidebar->run());

            /* Establish the top menu view */
            $menu = new \Altum\Views\View('admin/partials/admin_menu', (array) $this);
            $this->add_view_content('admin_menu', $menu->run());

            /* Establish the footer view */
            $footer = new \Altum\Views\View('admin/partials/footer', (array) $this);
            $this->add_view_content('footer', $footer->run());

            $wrapper = new \Altum\Views\View('admin/wrapper', (array) $this);
        }

        echo $wrapper->run();
    }


}
