<?php defined('ALTUMCODE') || die() ?>

<?= $this->views['header'] ?>

<?php require THEME_PATH . 'views/s/partials/ads_header.php' ?>

<div class="container mt-4">

    <nav aria-label="breadcrumb">
        <small>
            <ol class="custom-breadcrumbs">
                <li>
                    <a href="<?= $data->status_page->full_url ?>"><?= language()->s_status_page->breadcrumb ?></a> <div class="svg-sm text-muted d-inline-block"><?= include_view(ASSETS_PATH . '/images/s/chevron-right.svg') ?></div>
                </li>
                <li class="active" aria-current="page"><?= sprintf(language()->s_monitor->breadcrumb, $data->monitor->name) ?></li>
            </ol>
        </small>
    </nav>

    <div class="card bg-blue-900 border-0">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <?php if($data->monitor->is_ok): ?>
                    <div class="svg-head-status text-primary-400 d-inline-block"><?= include_view(ASSETS_PATH . '/images/s/check-circle.svg') ?></div>
                    <div class="ml-3">
                        <span class="text-white h3"><?= sprintf(language()->s_monitor->monitor_status_ok, $data->monitor->name) ?></span>
                        <div>
                            <span class="text-gray-400"><?= sprintf(language()->s_monitor->last_check_datetime, \Altum\Date::get_timeago($data->monitor->last_check_datetime)) ?></span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="svg-head-status text-danger d-inline-block"><?= include_view(ASSETS_PATH . '/images/s/x-circle.svg') ?></div>
                    <div class="ml-3">
                        <span class="text-white h3"><?= sprintf(language()->s_monitor->monitor_status_not_ok, $data->monitor->name) ?></span>
                        <div>
                            <span class="text-gray-400"><?= sprintf(language()->s_monitor->last_check_datetime, \Altum\Date::get_timeago($data->monitor->last_check_datetime)) ?></span>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <div class="row justify-content-between my-4">
        <div class="col-12 col-xl mb-3 mb-xl-0">
            <div class="card h-100">
                <div class="card-body d-flex">

                    <div>
                        <div class="card border-0 bg-blue-50 text-blue-800 mr-3">
                            <div class="p-3 d-flex align-items-center justify-content-between">
                                <div class="svg-card-icon d-inline-block"><?= include_view(ASSETS_PATH . '/images/s/globe-alt.svg') ?></div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <span class="text-muted"><?= language()->monitor->uptime ?></span>
                        <div class="d-flex align-items-center">
                            <div class="card-title h4 m-0"><?= $data->total_monitor_logs ? nr($data->monitor_logs_data['uptime'], 3) . '%' : '?' ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl mb-3 mb-xl-0">
            <div class="card h-100">
                <div class="card-body d-flex">

                    <div>
                        <div class="card border-0 bg-blue-50 text-blue-800 mr-3">
                            <div class="p-3 d-flex align-items-center justify-content-between">
                                <div class="svg-card-icon d-inline-block"><?= include_view(ASSETS_PATH . '/images/s/lightning-bolt.svg') ?></div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <span class="text-muted"><?= language()->monitor->average_response_time ?></span>
                        <div class="d-flex align-items-center">
                            <div class="card-title h4 m-0"><?= $data->total_monitor_logs ? display_response_time($data->monitor_logs_data['average_response_time']) : '?' ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl mb-3 mb-xl-0">
            <div class="card h-100">
                <div class="card-body d-flex">

                    <div>
                        <div class="card border-0 bg-blue-50 text-blue-800 mr-3">
                            <div class="p-3 d-flex align-items-center justify-content-between">
                                <div class="svg-card-icon d-inline-block"><?= include_view(ASSETS_PATH . '/images/s/x-circle.svg') ?></div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <span class="text-muted"><?= language()->monitor->total_incidents ?></span>
                        <div class="d-flex align-items-center">
                            <div class="card-title h4 m-0"><?= $data->total_monitor_logs ? nr(count($data->monitor_incidents)) : '?' ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="my-4 d-flex justify-content-between align-items-center">
        <div>
            <?php if($data->total_monitor_logs): ?>
                <span class="text-muted"><?= sprintf(language()->s_monitor->total_monitor_logs, nr($data->total_monitor_logs)) ?></span>
            <?php else: ?>
                <span class="text-muted"><?= language()->s_monitor->monitor_logs_no_data ?></span>
            <?php endif ?>
        </div>
        <button
                id="daterangepicker"
                type="button"
                class="btn btn-sm btn-outline-secondary"
                data-min-date="<?= \Altum\Date::get($data->monitor->datetime, 4) ?>"
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

    <?php if($data->total_monitor_logs): ?>
        <div class="card my-4">
            <div class="card-body">
                <div class="chart-container" style="height: 300px;">
                    <canvas id="monitor_logs_chart"></canvas>
                </div>
            </div>
        </div>

        <?php if(count($data->monitor_incidents)): ?>
            <div class="table-responsive table-custom-container">
                <table class="table table-custom">
                    <thead>
                    <tr>
                        <th colspan="3"><?= language()->monitor->incidents->header ?></th>
                    </tr>
                    <tr>
                        <th><?= language()->monitor->incidents->start_datetime ?></th>
                        <th><?= language()->monitor->incidents->end_datetime ?></th>
                        <th><?= language()->monitor->incidents->length ?></th>
                    </tr>
                    <tbody>
                    <?php foreach($data->monitor_incidents as $monitor_incident): ?>
                        <tr>
                            <td class="text-muted">
                                <?= \Altum\Date::get($monitor_incident->start_datetime) ?>
                            </td>
                            <td class="text-muted">
                                <?= \Altum\Date::get($monitor_incident->end_datetime) ?>
                            </td>
                            <td>
                                <?= \Altum\Date::get_elapsed_time($monitor_incident->start_datetime, $monitor_incident->end_datetime) ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>

    <?php endif ?>

    <small class="text-muted"><?= sprintf(language()->s_status_page->timezone, $data->status_page->timezone) ?></small>
</div>

<?= include_view(THEME_PATH . 'views/s/partials/share.php', ['external_url' => $data->status_page->full_url . $data->monitor->monitor_id]) ?>


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
        redirect(`<?= $data->status_page->full_url . $data->monitor->monitor_id ?>&start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

    });

    <?php if($data->total_monitor_logs): ?>
    let css = window.getComputedStyle(document.body)

    /* Response Time chart */
    let monitor_logs_chart = document.getElementById('monitor_logs_chart').getContext('2d');

    let response_time_color = css.getPropertyValue('--primary');
    let response_time_gradient = monitor_logs_chart.createLinearGradient(0, 0, 0, 250);
    response_time_gradient.addColorStop(0, 'rgba(16, 183, 127, .1)');
    response_time_gradient.addColorStop(1, 'rgba(16, 183, 127, 0.025)');

    let is_ok_color = css.getPropertyValue('--gray-300');
    let is_ok_gradient = monitor_logs_chart.createLinearGradient(0, 0, 0, 250);
    is_ok_gradient.addColorStop(0, 'rgba(37, 45, 60, .1)');
    is_ok_gradient.addColorStop(1, 'rgba(37, 45, 60, 0.025)');

    /* Display chart */
    new Chart(monitor_logs_chart, {
        type: 'line',
        data: {
            labels: <?= $data->monitor_logs_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(language()->monitor->response_time_label) ?>,
                    data: <?= $data->monitor_logs_chart['response_time'] ?? '[]' ?>,
                    backgroundColor: response_time_gradient,
                    borderColor: response_time_color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });
    <?php endif ?>

</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
