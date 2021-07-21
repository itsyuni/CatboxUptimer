<?php

namespace Altum\Controllers;

use Altum\Database\Database;

class Sitemap extends Controller {

    public function index() {

        /* Set the header as xml so the browser can read it properly */
        header('Content-Type: text/xml');

        /* How many external users per sitemap page */
        $pagination = 5000;

        $page = isset($this->params[0]) ? $this->params[0] : null;

        /* Different answers for different parts */
        switch($page) {

            /* Sitemap index */
            case null:

                /* Get the total amount of status_pages */
                $total_status_pages = database()->query("
                    SELECT 
                        COUNT(`status_pages`.`status_page_id`) AS `total` 
                    FROM 
                        `status_pages`
                    LEFT JOIN
                        `users` ON `status_pages`.`user_id` = `users`.`user_id`
                    WHERE
                        `users`.`active` = 1
                        AND `status_pages`.`is_enabled` = 1
                        AND `status_pages`.`is_se_visible` = 1
                  ")->fetch_object()->total ?? 0;

                /* Calculate the needed sitemaps */
                $total_sitemaps = 1 + ceil((int) $total_status_pages / $pagination);

                /* Main View */
                $data = [
                    'total_sitemaps' => $total_sitemaps
                ];

                $view = new \Altum\Views\View('sitemap/sitemap_index', (array) $this);

                break;

            /* Output base pages like the homepage, register..etc*/
            case 1:

                /* Get all custom pages from the database */
                $pages_result = database()->query("SELECT `url` FROM `pages` WHERE `type` = 'internal'");

                /* Main View */
                $data = [
                    'pages_result' => $pages_result
                ];

                $view = new \Altum\Views\View('sitemap/sitemap_1', (array) $this);

                break;

            /* Output only indexed external users */
            default:

                $limit_start = ($page - 2) * $pagination;

                /* Get the external users list */
                $status_pages_result = database()->query("
                    SELECT
                        `status_pages`.`url`,
                        `status_pages`.`datetime`
                    FROM 
                        `status_pages`
                    LEFT JOIN
                        `users` ON `status_pages`.`user_id` = `users`.`user_id`
                    WHERE
                        `users`.`active` = 1
                        AND `status_pages`.`is_enabled` = 1
                        AND `status_pages`.`is_se_visible` = 1
                    LIMIT 
                        {$limit_start}, {$pagination}
                ");

                /* Main View */
                $data = [
                    'status_pages_result' => $status_pages_result
                ];

                $view = new \Altum\Views\View('sitemap/sitemap_x', (array) $this);

                break;

        }


        echo $view->run($data);

        die();
    }

}
