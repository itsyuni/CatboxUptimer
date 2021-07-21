<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <small>
            <ol class="custom-breadcrumbs">
                <li>
                    <a href="<?= url('status-pages') ?>"><?= language()->status_pages->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                </li>
                <li>
                    <?= language()->status_page->breadcrumb ?><i class="fa fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page"><?= language()->status_page_statistics->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <div class="d-flex flex-column flex-lg-row justify-content-between mb-2">
        <div>
            <div class="d-flex align-items-center mb-2">
                <h1 class="h4 text-truncate mb-0 mr-2"><?= sprintf(language()->status_page_statistics->header, $data->status_page->name) ?></h1>

                <?= include_view(THEME_PATH . 'views/status-page/status_page_dropdown_button.php', ['id' => $data->status_page->status_page_id]) ?>
            </div>

            <p>
                <a href="<?= $data->status_page->full_url ?>" target="_blank">
                    <img src="https://external-content.duckduckgo.com/ip3/<?= parse_url($data->status_page->full_url)['host'] ?>.ico" class="img-fluid icon-favicon mr-1" />

                    <?= $data->status_page->full_url ?>
                </a>

                <button
                        id="url_copy"
                        type="button"
                        class="btn btn-link"
                        data-toggle="tooltip"
                        title="<?= language()->global->clipboard_copy ?>"
                        aria-label="<?= language()->global->clipboard_copy ?>"
                        data-copy="<?= language()->global->clipboard_copy ?>"
                        data-copied="<?= language()->global->clipboard_copied ?>"
                        data-clipboard-text="<?= $data->status_page->full_url ?>"
                >
                    <i class="fa fa-fw fa-sm fa-copy"></i>
                </button>
            </p>
        </div>

        <div>
            <button
                id="daterangepicker"
                type="button"
                class="btn btn-sm btn-outline-blue-500"
                data-min-date="<?= \Altum\Date::get($data->status_page->datetime, 4) ?>"
                data-max-date="<?= \Altum\Date::get('', 4) ?>"
            >
                <i class="fa fa-fw fa-calendar mr-1"></i>
                <span>
                    <?php if($data->datetime['start_date'] == $data->datetime['end_date']): ?>
                        <?= \Altum\Date::get($data->datetime['start_date'], 2, \Altum\Date::$default_timezone) ?>
                    <?php else: ?>
                        <?= \Altum\Date::get($data->datetime['start_date'], 2, \Altum\Date::$default_timezone) . ' - ' . \Altum\Date::get($data->datetime['end_date'], 2, \Altum\Date::$default_timezone) ?>
                    <?php endif ?>
                </span>
                <i class="fa fa-fw fa-caret-down ml-1"></i>
            </button>
        </div>
    </div>

    <?php if(!count($data->pageviews)): ?>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= ASSETS_FULL_URL . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-5 mb-3" alt="<?= language()->status_page_statistics->no_data ?>" />
            <h2 class="h4 text-muted mt-3"><?= language()->status_page_statistics->no_data ?></h2>
            <p class="text-muted"><?= language()->status_page_statistics->no_data_help ?></p>
        </div>

    <?php else: ?>

        <div class="card mb-5">
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="pageviews_chart"></canvas>
                </div>
            </div>
        </div>

        <ul class="nav nav-pills flex-column flex-lg-row mb-4" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?= $data->type == 'overview' ? 'active' : null ?>" href="<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=overview&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
                    <i class="fa fa-fw fa-sm fa-list mr-1"></i>
                    <?= language()->status_page_statistics->statistics->overview ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= in_array($data->type, ['country', 'city_name']) ? 'active' : null ?>" href="<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=country&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
                    <i class="fa fa-fw fa-sm fa-globe mr-1"></i>
                    <?= language()->status_page_statistics->statistics->country ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= in_array($data->type, ['referrer_host', 'referrer_path']) ? 'active' : null ?>" href="<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=referrer_host&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
                    <i class="fa fa-fw fa-sm fa-random mr-1"></i>
                    <?= language()->status_page_statistics->statistics->referrer_host ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $data->type == 'device' ? 'active' : null ?>" href="<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=device&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
                    <i class="fa fa-fw fa-sm fa-laptop mr-1"></i>
                    <?= language()->status_page_statistics->statistics->device ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $data->type == 'os' ? 'active' : null ?>" href="<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=os&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
                    <i class="fa fa-fw fa-sm fa-server mr-1"></i>
                    <?= language()->status_page_statistics->statistics->os ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $data->type == 'browser' ? 'active' : null ?>" href="<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=browser&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
                    <i class="fa fa-fw fa-sm fa-window-restore mr-1"></i>
                    <?= language()->status_page_statistics->statistics->browser ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $data->type == 'language' ? 'active' : null ?>" href="<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=language&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
                    <i class="fa fa-fw fa-sm fa-language mr-1"></i>
                    <?= language()->status_page_statistics->statistics->language ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= in_array($data->type, ['utm_source', 'utm_medium', 'utm_campaign']) ? 'active' : null ?>" href="<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=utm_source&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
                    <i class="fa fa-fw fa-sm fa-link mr-1"></i>
                    <?= language()->status_page_statistics->statistics->utms ?>
                </a>
            </li>
        </ul>

        <?= $this->views['statistics'] ?>

    <?php endif ?>

    <?php ob_start() ?>
    <link href="<?= ASSETS_FULL_URL . 'css/daterangepicker.min.css' ?>" rel="stylesheet" media="screen,print">
    <?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

    <?php ob_start() ?>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/Chart.bundle.min.js' ?>"></script>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/moment.min.js' ?>"></script>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/daterangepicker.min.js' ?>"></script>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/moment-timezone-with-data-10-year-range.min.js' ?>"></script>
    <script src="<?= ASSETS_FULL_URL . 'js/chartjs_defaults.js' ?>"></script>

    <script>
        'use strict';

        moment.tz.setDefault(<?= json_encode($this->user->timezone) ?>);

        /* Daterangepicker */
        $('#daterangepicker').daterangepicker({
            startDate: <?= json_encode($data->datetime['start_date']) ?>,
            endDate: <?= json_encode($data->datetime['end_date']) ?>,
            minDate: $('#daterangepicker').data('min-date'),
            maxDate: $('#daterangepicker').data('max-date'),
            ranges: {
                <?= json_encode(language()->global->date->today) ?>: [moment(), moment()],
                <?= json_encode(language()->global->date->yesterday) ?>: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                <?= json_encode(language()->global->date->last_7_days) ?>: [moment().subtract(6, 'days'), moment()],
                <?= json_encode(language()->global->date->last_30_days) ?>: [moment().subtract(29, 'days'), moment()],
                <?= json_encode(language()->global->date->this_month) ?>: [moment().startOf('month'), moment().endOf('month')],
                <?= json_encode(language()->global->date->last_month) ?>: [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                <?= json_encode(language()->global->date->all_time) ?>: [moment($('#daterangepicker').data('min-date')), moment()]
            },
            alwaysShowCalendars: true,
            linkedCalendars: false,
            singleCalendar: true,
            locale: <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>,
        }, (start, end, label) => {

            /* Redirect */
            redirect(`<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=' . $data->type) ?>&start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

        });

        <?php if(count($data->pageviews)): ?>
        let css = window.getComputedStyle(document.body)

        /* Pageviews chart */
        let pageviews_chart = document.getElementById('pageviews_chart').getContext('2d');

        let pageviews_color = css.getPropertyValue('--primary');
        let pageviews_gradient = pageviews_chart.createLinearGradient(0, 0, 0, 250);
        pageviews_gradient.addColorStop(0, 'rgba(16, 183, 127, .1)');
        pageviews_gradient.addColorStop(1, 'rgba(16, 183, 127, 0.025)');

        let visitors_color = css.getPropertyValue('--blue-500');
        let visitors_gradient = pageviews_chart.createLinearGradient(0, 0, 0, 250);
        visitors_gradient.addColorStop(0, 'rgba(17, 82, 212, .1)');
        visitors_gradient.addColorStop(1, 'rgba(17, 82, 212, 0.025)');

        /* Display chart */
        new Chart(pageviews_chart, {
            type: 'line',
            data: {
                labels: <?= $data->pageviews_chart['labels'] ?>,
                datasets: [
                    {
                        label: <?= json_encode(language()->status_page_statistics->pageviews) ?>,
                        data: <?= $data->pageviews_chart['pageviews'] ?? '[]' ?>,
                        backgroundColor: pageviews_gradient,
                        borderColor: pageviews_color,
                        fill: true
                    },
                    {
                        label: <?= json_encode(language()->status_page_statistics->visitors) ?>,
                        data: <?= $data->pageviews_chart['visitors'] ?? '[]' ?>,
                        backgroundColor: visitors_gradient,
                        borderColor: visitors_color,
                        fill: true
                    }
                ]
            },
            options: chart_options
        });

        <?php endif ?>
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>


