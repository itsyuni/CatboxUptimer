<?php defined('ALTUMCODE') || die() ?>

<?= $this->views['header'] ?>

<?php require THEME_PATH . 'views/s/partials/ads_header.php' ?>

<div class="container mt-4">
    <?php if(count($data->monitors)): ?>
        <div class="card bg-blue-900 border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <?php if($data->monitors_status): ?>
                        <div class="svg-head-status text-primary-400 d-inline-block"><?= include_view(ASSETS_PATH . '/images/s/check-circle.svg') ?></div>
                        <div class="ml-3">
                            <span class="text-white h3"><?= language()->s_status_page->monitors_status_ok ?></span>
                        </div>
                    <?php else: ?>
                        <div class="svg-head-status text-danger d-inline-block"><?= include_view(ASSETS_PATH . '/images/s/x-circle.svg') ?></div>
                        <div class="ml-3">
                            <span class="text-white h3"><?= language()->s_status_page->monitors_status_not_ok ?></span>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <div class="my-4 d-flex justify-content-end">
            <button
                    id="daterangepicker"
                    type="button"
                    class="btn btn-sm btn-outline-secondary"
                    data-min-date="<?= \Altum\Date::get($data->status_page_earliest_datetime_available, 4) ?>"
                    data-max-date="<?= \Altum\Date::get('', 4) ?>"
            >
                <i class="fa fa-fw fa-calendar mr-1"></i>
                <span>
                    <?php if($data->date->start_date == $data->date->end_date): ?>
                        <?= \Altum\Date::get($data->date->start_date, 2, \Altum\Date::$default_timezone) ?>
                    <?php else: ?>
                        <?= \Altum\Date::get($data->date->start_date, 2, \Altum\Date::$default_timezone) . ' - ' . \Altum\Date::get($data->date->end_date, 2, \Altum\Date::$default_timezone) ?>
                    <?php endif ?>
                </span>
                <i class="fa fa-fw fa-caret-down ml-1"></i>
            </button>
        </div>

        <div class="mt-4">
            <?php foreach($data->monitors as $monitor): ?>
                <div class="card my-4">
                    <div class="card-body">

                        <?php if(count($monitor->monitor_logs)): ?>
                            <div class="d-flex flex-column flex-lg-row justify-content-between">
                                <div class="d-flex align-items-center mb-2 mb-lg-0">
                                    <?php if($monitor->is_ok): ?>
                                        <div class="svg-lg text-primary d-inline-block mr-2"><?= include_view(ASSETS_PATH . '/images/s/check-circle.svg') ?></div>
                                    <?php else: ?>
                                        <div class="svg-lg text-danger d-inline-block mr-2"><?= include_view(ASSETS_PATH . '/images/s/x-circle.svg') ?></div>
                                    <?php endif ?>

                                    <a href="<?= $data->status_page->full_url . $monitor->monitor_id ?>" class="font-weight-bold text-dark"><?= $monitor->name ?></a>
                                </div>

                                <div class="d-flex flex-column">
                                    <div>
                                        <span class="text-muted mr-3"><?= nr($monitor->monitor_logs_data['uptime'], 3) . '%' ?></span>
                                        <span class="text-muted mr-3"><?= display_response_time($monitor->monitor_logs_data['average_response_time']) ?></span>
                                        <span class="text-muted"><?= sprintf(language()->s_status_page->total_not_ok_checks, nr($monitor->monitor_logs_data['total_not_ok_checks'])) ?></span>
                                    </div>
                                    <div>
                                        <small class="text-muted"><?= sprintf(language()->s_monitor->total_monitor_logs, nr($monitor->monitor_logs_data['total_monitor_logs'])) ?></small>
                                    </div>
                                </div>
                            </div>

                            <div class="chart-container mt-2" style="height: 175px;">
                                <canvas id="monitor_logs_chart_<?= $monitor->monitor_id ?>"></canvas>
                            </div>

                        <?php else: ?>
                            <div class="d-flex flex-column flex-lg-row justify-content-between">
                                <div class="d-flex align-items-center mb-2 mb-lg-0">
                                    <?php if($monitor->is_ok): ?>
                                        <div class="svg-lg text-primary d-inline-block mr-2"><?= include_view(ASSETS_PATH . '/images/s/check-circle.svg') ?></div>
                                    <?php else: ?>
                                        <div class="svg-lg text-danger d-inline-block mr-2"><?= include_view(ASSETS_PATH . '/images/s/x-circle.svg') ?></div>
                                    <?php endif ?>

                                    <a href="<?= $data->status_page->full_url . $monitor->monitor_id ?>" class="font-weight-bold text-dark"><?= $monitor->name ?></a>
                                </div>
                            </div>

                            <small class="text-muted"><?= language()->s_status_page->no_monitor_logs ?></small>
                        <?php endif ?>

                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <small class="text-muted"><?= sprintf(language()->s_status_page->timezone, $data->status_page->timezone) ?></small>
    <?php endif ?>
</div>


<?= include_view(THEME_PATH . 'views/s/partials/share.php', ['external_url' => $data->status_page->full_url]) ?>

<?php if(count($data->monitors)): ?>

    <?php ob_start() ?>
    <link href="<?= ASSETS_FULL_URL . 'css/daterangepicker.min.css' ?>" rel="stylesheet" media="screen,print">
    <?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

    <?php ob_start() ?>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/jquery.slim.min.js' ?>"></script>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/Chart.bundle.min.js' ?>"></script>
    <script src="<?= ASSETS_FULL_URL . 'js/chartjs_defaults.js' ?>"></script>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/moment.min.js' ?>"></script>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/daterangepicker.min.js' ?>"></script>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/moment-timezone-with-data-10-year-range.min.js' ?>"></script>

    <script>
        moment.tz.setDefault(<?= json_encode($data->status_page->timezone) ?>);

        /* Daterangepicker */
        $('#daterangepicker').daterangepicker({
            startDate: <?= json_encode($data->date->start_date) ?>,
            endDate: <?= json_encode($data->date->end_date) ?>,
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
            redirect(`<?= $data->status_page->full_url ?>&start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

        });

        let css = window.getComputedStyle(document.body);
        let response_time_color = css.getPropertyValue('--primary');
        let response_time_gradient = null;

        let monitor_logs_charts = {};

        <?php foreach($data->monitors as $monitor): ?>

        /* Response Time chart */
        monitor_logs_charts[<?= json_encode($monitor->monitor_id) ?>] = document.getElementById('monitor_logs_chart_<?= $monitor->monitor_id ?>').getContext('2d');

        response_time_gradient = monitor_logs_charts[<?= json_encode($monitor->monitor_id) ?>].createLinearGradient(0, 0, 0, 250);
        response_time_gradient.addColorStop(0, 'rgba(16, 183, 127, .1)');
        response_time_gradient.addColorStop(1, 'rgba(16, 183, 127, 0.025)');

        /* Display chart */
        new Chart(monitor_logs_charts[<?= json_encode($monitor->monitor_id) ?>], {
            type: 'line',
            data: {
                labels: <?= $monitor->monitor_logs_chart['labels'] ?>,
                datasets: [
                    {
                        label: <?= json_encode(language()->monitor->response_time_label) ?>,
                        data: <?= $monitor->monitor_logs_chart['response_time'] ?? '[]' ?>,
                        backgroundColor: response_time_gradient,
                        borderColor: response_time_color,
                        fill: true,
                    }
                ]
            },
            options: {
                ...chart_options,
                legend: {
                    display: false
                }
            }
        });

        <?php endforeach ?>
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php endif ?>
