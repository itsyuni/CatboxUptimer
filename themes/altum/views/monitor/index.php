<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <small>
            <ol class="custom-breadcrumbs">
                <li>
                    <a href="<?= url('monitors') ?>"><?= language()->monitors->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page"><?= language()->monitor->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <div class="card bg-blue-900 border-0">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <?php if($data->monitor->is_enabled): ?>
                        <?php if($data->monitor->is_ok): ?>
                            <span data-toggle="tooltip" title="<?= language()->monitor->is_ok ?>">
                                <i class="fa fa-fw fa-check-circle fa-3x text-primary-400"></i>
                            </span>
                        <?php else: ?>
                            <span data-toggle="tooltip" title="<?= language()->monitor->is_not_ok ?>">
                                <i class="fa fa-fw fa-sm fa-times-circle fa-3x text-danger"></i>
                            </span>
                        <?php endif ?>
                    <?php else: ?>
                        <span data-toggle="tooltip" title="<?= language()->monitor->is_enabled_paused ?>">
                            <i class="fa fa-fw fa-sm fa-pause-circle fa-3x text-warning"></i>
                        </span>
                    <?php endif ?>
                </div>

                <div class="ml-3">
                    <div class="d-flex align-items-center">
                        <h1 class="h3 text-truncate text-white mb-0 mr-2"><?= sprintf(language()->monitor->header, $data->monitor->name) ?></h1>

                        <?= include_view(THEME_PATH . 'views/monitor/monitor_dropdown_button.php', ['id' => $data->monitor->monitor_id]) ?>
                    </div>

                    <div class="text-gray-400">
                        <span><?= $data->monitor->target ?><?= $data->monitor->port ? ':' . $data->monitor->port : null ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(!$data->monitor->total_checks): ?>
        <div class="d-flex flex-column align-items-center justify-content-center mt-4">
            <img src="<?= ASSETS_FULL_URL . 'images/processing.svg' ?>" class="col-10 col-md-7 col-lg-5 mb-3" alt="<?= language()->monitor->no_data ?>" />
            <h2 class="h4 text-muted mt-3"><?= language()->monitor->no_data ?></h2>
            <p class="text-muted"><?= sprintf(language()->monitor->no_data_help, $data->monitor->name) ?></p>
        </div>
    <?php endif ?>

    <?php if($data->monitor->total_checks): ?>

        <div class="row justify-content-between mt-4">
            <div class="col-12 col-xl mb-3 mb-xl-0">
                <div class="card h-100">
                    <div class="card-body d-flex">

                        <div>
                            <div class="card border-0 bg-blue-50 text-blue-800 mr-3">
                                <div class="p-3 d-flex align-items-center justify-content-between">
                                    <i class="fa fa-fw fa-globe fa-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <span class="text-muted"><?= language()->monitor->uptime ?></span>
                            <div class="d-flex align-items-center">
                                <div class="card-title h4 m-0"><?= $data->total_monitor_logs ? nr($data->monitor_logs_data['uptime'], 3) . '%' : '?' ?></div>
                                <div class="ml-2">
                                    <span data-toggle="tooltip" title="<?= sprintf(language()->monitor->total_checks_tooltip, nr($data->total_monitor_logs)) ?>">
                                        <i class="fa fa-fw fa-info-circle text-muted"></i>
                                    </span>
                                </div>
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
                                    <i class="fa fa-fw fa-bolt fa-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <span class="text-muted"><?= language()->monitor->average_response_time ?></span>
                            <div class="d-flex align-items-center">
                                <div class="card-title h4 m-0"><?= $data->total_monitor_logs ? display_response_time($data->monitor_logs_data['average_response_time']) : '?' ?></div>
                                <div class="ml-2">
                                    <span data-toggle="tooltip" title="<?= sprintf(language()->monitor->total_ok_checks_tooltip, nr($data->monitor_logs_data['total_ok_checks'])) ?>">
                                        <i class="fa fa-fw fa-info-circle text-muted"></i>
                                    </span>
                                </div>
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
                                    <i class="fa fa-fw fa-times-circle fa-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <span class="text-muted"><?= language()->monitor->total_incidents ?></span>
                            <div class="d-flex align-items-center">
                                <div class="card-title h4 m-0"><?= $data->total_monitor_logs ? nr(count($data->monitor_incidents)) : '?' ?></div>
                                <div class="ml-2">
                                    <span data-toggle="tooltip" title="<?= sprintf(language()->monitor->downtime_tooltip, nr($data->monitor_logs_data['downtime'], 3) . '%') ?>">
                                        <i class="fa fa-fw fa-info-circle text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if(($data->date->start_date != $data->date->end_date && $data->date->end_date == \Altum\Date::get('', 4)) || ($data->date->start_date == $data->date->end_date && $data->date->start_date == \Altum\Date::get('', 4))): ?>
        <div class="mt-4">
            <div class="row justify-content-between">
                <?php if($data->monitor->is_enabled): ?>
                <div class="col-12 col-xl mb-3 mb-xl-0">
                    <?php if($data->monitor->is_ok): ?>
                    <div class="card h-100">
                        <div class="card-body d-flex">
                            <div>
                                <div class="card border-0 bg-blue-50 text-blue-800 mr-3">
                                    <div class="p-3 d-flex align-items-center justify-content-between">
                                        <i class="fa fa-fw fa-check fa-lg"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <span class="text-muted"><?= language()->monitor->currently_up_for ?></span>
                                <div class="d-flex align-items-center">
                                    <div class="card-title h4 m-0"><?= \Altum\Date::get_elapsed_time($data->monitor->main_ok_datetime) ?></div>
                                    <div class="ml-2">
                                        <span data-toggle="tooltip" title="<?= sprintf(language()->monitor->last_not_ok_datetime_tooltip, \Altum\Date::get($data->monitor->last_not_ok_datetime)) ?>">
                                            <i class="fa fa-fw fa-info-circle text-muted"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                        <div class="card h-100">
                            <div class="card-body d-flex">
                                <div>
                                    <div class="card border-0 bg-blue-50 text-blue-800 mr-3">
                                        <div class="p-3 d-flex align-items-center justify-content-between">
                                            <i class="fa fa-fw fa-times fa-lg"></i>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <span class="text-muted"><?= language()->monitor->currently_down_for ?></span>
                                    <div class="d-flex align-items-center">
                                        <div class="card-title h4 m-0"><?= \Altum\Date::get_elapsed_time($data->monitor->main_not_ok_datetime) ?></div>
                                        <div class="ml-2">
                                        <span data-toggle="tooltip" title="<?= sprintf(language()->monitor->last_ok_datetime_tooltip, \Altum\Date::get($data->monitor->last_ok_datetime)) ?>">
                                            <i class="fa fa-fw fa-info-circle text-muted"></i>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
                <?php endif ?>

                <div class="col-12 col-xl mb-3 mb-xl-0">
                    <div class="card h-100">
                        <div class="card-body d-flex">
                            <div>
                                <div class="card border-0 bg-blue-50 text-blue-800 mr-3">
                                    <div class="p-3 d-flex align-items-center justify-content-between">
                                        <i class="fa fa-fw fa-calendar-check fa-lg"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <span class="text-muted"><?= language()->monitor->last_check_datetime ?></span>
                                <div class="d-flex align-items-center">
                                    <div class="card-title h4 m-0"><?= \Altum\Date::get_timeago($data->monitor->last_check_datetime) ?></div>
                                    <div class="ml-2">
                                    <span data-toggle="tooltip" title="<?= sprintf(language()->monitor->check_interval_seconds_tooltip, $data->monitor->settings->check_interval_seconds) ?>">
                                        <i class="fa fa-fw fa-info-circle text-muted"></i>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif ?>

        <div class="d-flex flex-column flex-lg-row justify-content-between mt-4">
            <div>&nbsp;</div>

            <div class="d-flex">
                <button
                        id="daterangepicker"
                        type="button"
                        class="btn btn-sm btn-outline-blue-500"
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

                <div class="ml-2">
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle-simple" data-toggle="dropdown" title="<?= language()->global->export ?>">
                            <i class="fa fa-fw fa-sm fa-download"></i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="<?= url('monitor/' . $data->monitor->monitor_id . '?start_date=' . $data->date->start_date . '&end_date=' . $data->date->end_date . '&export=csv')  ?>" target="_blank" class="dropdown-item">
                                <i class="fa fa-fw fa-sm fa-file-csv mr-1"></i> <?= language()->global->export_csv ?>
                            </a>
                            <a href="<?= url('monitor/' . $data->monitor->monitor_id . '?start_date=' . $data->date->start_date . '&end_date=' . $data->date->end_date . '&export=json') ?>') ?>" target="_blank" class="dropdown-item">
                                <i class="fa fa-fw fa-sm fa-file-code mr-1"></i> <?= language()->global->export_json ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="ml-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary d-print-none" onclick="window.print()" title="<?= language()->global->export_pdf ?>">
                        <i class="fa fa-fw fa-file-pdf"></i>
                    </button>
                </div>
            </div>
        </div>

        <?php if($data->total_monitor_logs): ?>

        <div class="card mt-4">
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monitor_logs_chart"></canvas>
                </div>
            </div>
        </div>

        <?php endif ?>

        <div class="mt-4">
            <div class="table-responsive table-custom-container">
                <table class="table table-custom">
                    <thead>
                    <tr>
                        <th colspan="5"><?= language()->monitor->ping_servers_checks->header ?></th>
                    </tr>
                    <tr>
                        <th><?= language()->monitor->ping_servers_checks->ping_server ?></th>
                        <th><?= language()->monitor->ping_servers_checks->lowest_response_time ?></th>
                        <th><?= language()->monitor->ping_servers_checks->highest_response_time ?></th>
                        <th><?= language()->monitor->ping_servers_checks->average_response_time ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!$data->total_monitor_logs): ?>
                        <tr>
                            <td colspan="3" class="text-muted"><?= language()->monitor->ping_servers_checks->no_data ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data->ping_servers_checks as $ping_server_id => $ping_server_data): ?>
                            <?php
                            /* Calculate */
                            $ping_server_data['average_response_time'] = $ping_server_data['total_ok_checks'] > 0 ? $ping_server_data['total_response_time'] / $ping_server_data['total_ok_checks'] : 0;
                            ?>

                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= ASSETS_FULL_URL . 'images/countries/' . mb_strtolower($data->ping_servers[$ping_server_id]->country_code) . '.svg' ?>" class="img-fluid icon-favicon mr-1" data-toggle="tooltip" title="<?= get_country_from_country_code($data->ping_servers[$ping_server_id]->country_code). ', ' . $data->ping_servers[$ping_server_id]->city_name ?>" />
                                    </div>
                                </td>

                                <td>
                                    <?= display_response_time($ping_server_data['lowest_response_time']) ?>
                                </td>

                                <td>
                                    <?= display_response_time($ping_server_data['highest_response_time']) ?>
                                </td>

                                <td>
                                    <?= display_response_time($ping_server_data['average_response_time']) ?>
                                </td>

                                <td>
                                    <span class="text-muted">
                                        <?= sprintf(language()->monitor->ping_servers_checks->total_ok_checks, nr($ping_server_data['total_ok_checks'])) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach ?>

                        <tr>
                            <td colspan="5" class="text-muted">
                                <div class="d-flex">
                                    <span class="font-weight-bold"><?= language()->monitor->ping_servers_checks->self_location ?></span>

                                    <span><i class="fa fa-fw fa-sm fa-arrow-right mx-1"></i></span>

                                    <div class="d-flex align-items-center">
                                        <img src="<?= ASSETS_FULL_URL . 'images/countries/' . mb_strtolower($data->monitor->details->country_code) . '.svg' ?>" class="img-fluid icon-favicon mr-1" data-toggle="tooltip" title="<?= $data->monitor->details->continent_name . ', ' . get_country_from_country_code($data->monitor->details->country_code) ?>" />
                                        <span><?= $data->monitor->details->city_name ?></span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endif ?>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <div class="table-responsive table-custom-container">
                <table class="table table-custom">
                    <thead>
                    <tr>
                        <th colspan="4">
                            <?= language()->monitor->checks->last_checks ?>
                            <span class="ml-3 small">
                                <a href="<?= url('monitor-logs/' . $data->monitor->monitor_id) ?>"><?= language()->monitor->checks->view_all ?></a>
                            </span>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="2"><?= language()->monitor->checks->status ?></th>
                        <th><?= language()->monitor->checks->response_time ?></th>
                        <?php if($data->monitor->type == 'website'): ?>
                        <th><?= language()->monitor->checks->response_status_code ?></th>
                        <?php endif ?>
                        <th><?= language()->monitor->checks->datetime ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!$data->total_monitor_logs): ?>
                        <tr>
                            <td colspan="4" class="text-muted"><?= language()->monitor->checks->no_data ?></td>
                        </tr>
                    <?php else: ?>
                        <?php for($i = count($data->monitor_logs) - 1; $i >= count($data->monitor_logs) - 5; $i--): ?>

                            <?php
                            if(!isset($data->monitor_logs[$i])) {
                                continue;
                            }
                            ?>

                            <tr>
                                <td>
                                    <?php if($data->monitor_logs[$i]->is_ok): ?>
                                        <i class="fa fa-fw fa-sm fa-check-circle text-success"></i>
                                    <?php else: ?>
                                        <i class="fa fa-fw fa-sm fa-times-circle text-danger"></i>
                                    <?php endif ?>

                                    <?php if($data->monitor->type == 'website' && !$data->monitor_logs[$i]->is_ok): ?>
                                        <?php
                                        $data->monitor_logs[$i]->error = json_decode($data->monitor_logs[$i]->error);
                                        if($data->monitor_logs[$i]->error->type == 'exception') {
                                            $error = $data->monitor_logs[$i]->error->message;
                                        } elseif(in_array($data->monitor_logs[$i]->error->type, ['response_status_code', 'response_body', 'response_header'])) {
                                            $error = language()->monitor->checks->error->{$data->monitor_logs[$i]->error->type};
                                        }
                                        ?>

                                        <span class="ml-3" data-toggle="tooltip" title="<?= $error ?>">
                                            <i class="fa fa-fw fa-sm fa-envelope-open-text text-muted"></i>
                                        </span>
                                    <?php endif ?>
                                </td>

                                <td>
                                    <img src="<?= ASSETS_FULL_URL . 'images/countries/' . mb_strtolower($data->ping_servers[$data->monitor_logs[$i]->ping_server_id]->country_code) . '.svg' ?>" class="img-fluid icon-favicon" data-toggle="tooltip" title="<?= get_country_from_country_code($data->ping_servers[$data->monitor_logs[$i]->ping_server_id]->country_code). ', ' . $data->ping_servers[$data->monitor_logs[$i]->ping_server_id]->city_name ?>" />
                                </td>

                                <td>
                                    <?= display_response_time($data->monitor_logs[$i]->response_time) ?>
                                </td>

                                <?php if($data->monitor->type == 'website'): ?>
                                    <td><?= $data->monitor_logs[$i]->response_status_code ?></td>
                                <?php endif ?>

                                <td>
                                    <span class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($data->monitor_logs[$i]->datetime) ?>">
                                        <?= \Altum\Date::get_timeago($data->monitor_logs[$i]->datetime) ?>
                                    </span>
                                </td>
                            </tr>

                        <?php endfor ?>
                    <?php endif ?>

                    </tbody>
                </table>
            </div>
        </div>

        <?php if($data->total_monitor_logs): ?>
        <div class="mt-4">
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
                    </thead>
                    <tbody>
                    <?php if(!count($data->monitor_incidents)): ?>
                        <tr>
                            <td colspan="3" class="text-muted"><?= language()->monitor->incidents->no_data ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data->monitor_incidents as $monitor_incident): ?>
                            <tr>
                                <td class="text-muted">
                                    <?= \Altum\Date::get($monitor_incident->start_datetime) ?>
                                </td>
                                <td class="text-muted">
                                    <?= $monitor_incident->end_datetime ? \Altum\Date::get($monitor_incident->end_datetime) : language()->monitor->incidents->end_datetime_null ?>
                                </td>
                                <td>
                                    <?= \Altum\Date::get_elapsed_time($monitor_incident->start_datetime, $monitor_incident->end_datetime) ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif ?>

        <div class="mt-4">
            <div class="row justify-content-between">
                <?php if($data->monitor->settings->ssl_is_enabled && isset($data->monitor->ssl->start_date)): ?>
                    <div class="col-12 col-xl mb-3 mb-xl-0">
                        <div class="card h-100">
                            <div class="card-body d-flex">
                                <div>
                                    <div class="card border-0 bg-blue-50 text-blue-800 mr-3">
                                        <div class="p-3 d-flex align-items-center justify-content-between">
                                            <i class="fa fa-fw fa-lock fa-lg"></i>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <span class="text-muted"><?= language()->monitor->ssl->header ?></span>
                                    <div class="d-flex align-items-center">
                                        <div class="card-title h4 m-0"><?= sprintf(language()->monitor->ssl->subheader, \Altum\Date::get_time_until($data->monitor->ssl->end_date)) ?></div>
                                        <div class="ml-2">
                                            <span data-toggle="tooltip" title="<?= $data->monitor->ssl->issuer_full ?>">
                                                <i class="fa fa-fw fa-info-circle text-muted"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>

    <?php endif ?>

</div>

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/daterangepicker.min.css' ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/Chart.bundle.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/chartjs_defaults.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/daterangepicker.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment-timezone-with-data-10-year-range.min.js' ?>"></script>

<script>
    'use strict';

    moment.tz.setDefault(<?= json_encode($this->user->timezone) ?>);

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
        redirect(`<?= url('monitor/' . $data->monitor->monitor_id) ?>&start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

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
                },
                {
                    label: <?= json_encode(language()->monitor->is_ok_label) ?>,
                    data: <?= $data->monitor_logs_chart['is_ok'] ?? '[]' ?>,
                    backgroundColor: is_ok_gradient,
                    borderColor: is_ok_color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });
    <?php endif ?>
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
