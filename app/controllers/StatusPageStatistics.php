<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class StatusPageStatistics extends Controller {

    public function index() {

        Authentication::guard();

        if(!$this->user->plan_settings->analytics_is_enabled) {
            redirect('status-pages');
        }

        $status_page_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$status_page = db()->where('status_page_id', $status_page_id)->where('user_id', $this->user->user_id)->getOne('status_pages')) {
            redirect('status-pages');
        }

        /* Genereate the status_page full URL base */
        $status_page->full_url = (new \Altum\Models\StatusPage())->get_status_page_full_url($status_page, $this->user);

        /* Statistics related variables */
        $type = isset($_GET['type']) && in_array($_GET['type'], ['overview', 'referrer_host', 'referrer_path', 'country', 'city_name', 'os', 'browser', 'device', 'language', 'utm_source', 'utm_medium', 'utm_campaign']) ? Database::clean_string($_GET['type']) : 'overview';

        $datetime = \Altum\Date::get_start_end_dates_new();

        /* Get the required statistics */
        $pageviews = [];
        $pageviews_chart = [];

        $pageviews_result = database()->query("
            SELECT
                COUNT(`id`) AS `pageviews`,
                SUM(`is_unique`) AS `visitors`,
                DATE_FORMAT(`datetime`, '%Y-%m-%d') AS `formatted_date`
            FROM
                 `statistics`
            WHERE
                `status_page_id` = {$status_page->status_page_id}
                AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");

        /* Generate the raw chart data and save pageviews for later usage */
        while($row = $pageviews_result->fetch_object()) {
            $pageviews[] = $row;

            $row->formatted_date = $datetime['process']($row->formatted_date);

            $pageviews_chart[$row->formatted_date] = [
                'pageviews' => $row->pageviews,
                'visitors' => $row->visitors
            ];
        }

        $pageviews_chart = get_chart_data($pageviews_chart);

        /* Get data based on what statistics are needed */
        switch($type) {
            case 'overview':

                $result = database()->query("
                    SELECT
                        *
                    FROM
                        `statistics`
                    WHERE
                        `status_page_id` = {$status_page->status_page_id}
                        AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                    ORDER BY
                        `datetime` DESC
                    LIMIT 25
                ");

                break;

            case 'referrer_host':
            case 'country':
            case 'os':
            case 'browser':
            case 'device':
            case 'language':

                $columns = [
                    'referrer_host' => 'referrer_host',
                    'referrer_path' => 'referrer_path',
                    'country' => 'country_code',
                    'city_name' => 'city_name',
                    'os' => 'os_name',
                    'browser' => 'browser_name',
                    'device' => 'device_type',
                    'language' => 'browser_language'
                ];

                $result = database()->query("
                    SELECT
                        `{$columns[$type]}`,
                        COUNT(*) AS `total`
                    FROM
                         `statistics`
                    WHERE
                        `status_page_id` = {$status_page->status_page_id}
                        AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                    GROUP BY
                        `{$columns[$type]}`
                    ORDER BY
                        `total` DESC
                    LIMIT 250
                ");

                break;

            case 'referrer_path':

                $referrer_host = trim(Database::clean_string($_GET['referrer_host']));

                $result = database()->query("
                    SELECT
                        `referrer_path`,
                        COUNT(*) AS `total`
                    FROM
                         `statistics`
                    WHERE
                        `status_page_id` = {$status_page->status_page_id}
                        AND `referrer_host` = '{$referrer_host}'
                        AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                    GROUP BY
                        `referrer_path`
                    ORDER BY
                        `total` DESC
                    LIMIT 250
                ");

                break;

            case 'city_name':

                $country_code = trim(Database::clean_string($_GET['country_code']));

                $result = database()->query("
                    SELECT
                        `city_name`,
                        COUNT(*) AS `total`
                    FROM
                         `statistics`
                    WHERE
                        `status_page_id` = {$status_page->status_page_id}
                        AND `country_code` = '{$country_code}'
                        AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                    GROUP BY
                        `city_name`
                    ORDER BY
                        `total` DESC
                    LIMIT 250
                ");

                break;

            case 'utm_source':

                $result = database()->query("
                    SELECT
                        `utm_source`,
                        COUNT(*) AS `total`
                    FROM
                         `statistics`
                    WHERE
                        `status_page_id` = {$status_page->status_page_id}
                        AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                        AND `utm_source` IS NOT NULL
                    GROUP BY
                        `utm_source`
                    ORDER BY
                        `total` DESC
                    LIMIT 250
                ");

                break;

            case 'utm_medium':

                $utm_source = trim(Database::clean_string($_GET['utm_source']));

                $result = database()->query("
                    SELECT
                        `utm_medium`,
                        COUNT(*) AS `total`
                    FROM
                         `statistics`
                    WHERE
                        `status_page_id` = {$status_page->status_page_id}
                        AND `utm_source` = '{$utm_source}'
                        AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                    GROUP BY
                        `utm_medium`
                    ORDER BY
                        `total` DESC
                    LIMIT 250
                ");

                break;

            case 'utm_campaign':

                $utm_source = trim(Database::clean_string($_GET['utm_source']));
                $utm_medium = trim(Database::clean_string($_GET['utm_medium']));

                $result = database()->query("
                    SELECT
                        `utm_campaign`,
                        COUNT(*) AS `total`
                    FROM
                         `statistics`
                    WHERE
                        `status_page_id` = {$status_page->status_page_id}
                        AND `utm_source` = '{$utm_source}'
                        AND `utm_medium` = '{$utm_medium}'
                        AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                    GROUP BY
                        `utm_campaign`
                    ORDER BY
                        `total` DESC
                    LIMIT 250
                ");

                break;
        }

        switch($type) {
            case 'overview':

                $statistics_keys = [
                    'country_code',
                    'referrer_host',
                    'device_type',
                    'os_name',
                    'browser_name',
                    'browser_language'
                ];

                $statistics = [];
                foreach($statistics_keys as $key) {
                    $statistics[$key] = [];
                    $statistics[$key . '_total_sum'] = 0;
                }

                /* Start processing the rows from the database */
                while($row = $result->fetch_object()) {
                    foreach($statistics_keys as $key) {

                        $statistics[$key][$row->{$key}] = isset($statistics[$key][$row->{$key}]) ? $statistics[$key][$row->{$key}] + 1 : 1;

                        $statistics[$key . '_total_sum']++;

                    }
                }

                foreach($statistics_keys as $key) {
                    arsort($statistics[$key]);
                }

                /* Prepare the statistics method View */
                $data = [
                    'statistics' => $statistics,
                    'status_page' => $status_page,
                    'datetime' => $datetime,
                ];

                break;

            case 'referrer_host':
            case 'country':
            case 'city_name':
            case 'os':
            case 'browser':
            case 'device':
            case 'language':
            case 'referrer_path':
            case 'utm_source':
            case 'utm_medium':
            case 'utm_campaign':

                /* Store all the results from the database */
                $statistics = [];
                $statistics_total_sum = 0;

                while($row = $result->fetch_object()) {
                    $statistics[] = $row;

                    $statistics_total_sum += $row->total;
                }

                /* Prepare the statistics method View */
                $data = [
                    'rows' => $statistics,
                    'total_sum' => $statistics_total_sum,
                    'status_page' => $status_page,
                    'datetime' => $datetime,

                    'referrer_host' => $referrer_host ?? null,
                    'country_code' => $country_code ?? null,
                    'utm_source' => $utm_source ?? null,
                    'utm_medium' => $utm_medium ?? null,
                ];

            break;
        }

        $view = new \Altum\Views\View('status-page-statistics/statistics_' . $type, (array) $this);
        $this->add_view_content('statistics', $view->run($data));

        /* Delete Modal */
        $view = new \Altum\Views\View('status-page/status_page_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Set a custom title */
        Title::set(sprintf(language()->status_page_statistics->title, $status_page->name));

        /* Prepare the View */
        $data = [
            'status_page' => $status_page,
            'type' => $type,
            'datetime' => $datetime,
            'pageviews' => $pageviews,
            'pageviews_chart' => $pageviews_chart
        ];

        $view = new \Altum\Views\View('status-page-statistics/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
