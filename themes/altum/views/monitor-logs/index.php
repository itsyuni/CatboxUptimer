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
                <li>
                    <a href="<?= url('monitor/' . $data->monitor->monitor_id) ?>"><?= language()->monitor->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page"><?= language()->monitor_logs->breadcrumb ?></li>
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
                        <h1 class="h3 text-truncate text-white mb-0 mr-2"><?= sprintf(language()->monitor_logs->header, $data->monitor->name) ?></h1>

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
                            <a href="<?= url('monitor-logs/' . $data->monitor->monitor_id . '?start_date=' . $data->date->start_date . '&end_date=' . $data->date->end_date . '&export=csv')  ?>" target="_blank" class="dropdown-item">
                                <i class="fa fa-fw fa-sm fa-file-csv mr-1"></i> <?= language()->global->export_csv ?>
                            </a>
                            <a href="<?= url('monitor-logs/' . $data->monitor->monitor_id . '?start_date=' . $data->date->start_date . '&end_date=' . $data->date->end_date . '&export=json') ?>') ?>" target="_blank" class="dropdown-item">
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

                <div class="ml-2">
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm <?= count($data->filters->get) ? 'btn-outline-primary' : 'btn-outline-secondary' ?> filters-button dropdown-toggle-simple" data-toggle="dropdown"><i class="fa fa-fw fa-sm fa-filter"></i></button>

                        <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                            <div class="dropdown-header d-flex justify-content-between">
                                <span class="h6 m-0"><?= language()->global->filters->header ?></span>

                                <?php if(count($data->filters->get)): ?>
                                    <a href="<?= url('monitor-logs/' . $data->monitor->monitor_id) ?>" class="text-muted"><?= language()->global->filters->reset ?></a>
                                <?php endif ?>
                            </div>

                            <div class="dropdown-divider"></div>

                            <form action="" method="get" role="form">
                                <div class="form-group px-4">
                                    <label for="search" class="small"><?= language()->global->filters->search ?></label>
                                    <input type="search" name="search" id="search" class="form-control form-control-sm" value="<?= $data->filters->search ?>" />
                                </div>

                                <div class="form-group px-4">
                                    <label for="search_by" class="small"><?= language()->global->filters->search_by ?></label>
                                    <select name="search_by" id="search_by" class="form-control form-control-sm">
                                        <option value="response_status_code" <?= $data->filters->search_by == 'response_status_code' ? 'selected="selected"' : null ?>><?= language()->monitor_logs->filters->search_by_response_status_code ?></option>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="is_ok" class="small"><?= language()->monitor_logs->filters->status ?></label>
                                    <select name="is_ok" id="is_ok" class="form-control form-control-sm">
                                        <option value=""><?= language()->global->filters->all ?></option>
                                        <option value="1" <?= isset($data->filters->filters['is_ok']) && $data->filters->filters['is_ok'] == '1' ? 'selected="selected"' : null ?>><?= language()->monitor_logs->filters->is_ok ?></option>
                                        <option value="0" <?= isset($data->filters->filters['is_ok']) && $data->filters->filters['is_ok'] == '0' ? 'selected="selected"' : null ?>><?= language()->monitor_logs->filters->is_not_ok ?></option>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="ping_server_id" class="small"><?= language()->monitor_logs->filters->ping_server_id ?></label>
                                    <select name="ping_server_id" id="ping_server_id" class="form-control form-control-sm">
                                        <option value=""><?= language()->global->filters->all ?></option>
                                        <?php foreach($data->ping_servers as $ping_server_id => $ping_server): ?>
                                            <option value="<?= $ping_server_id ?>" <?= isset($data->filters->filters['ping_server_id']) && $data->filters->filters['ping_server_id'] == $ping_server_id ? 'selected="selected"' : null ?>><?= $ping_server->city_name . ' (' . $ping_server->country_code . ')' ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="order_by" class="small"><?= language()->global->filters->order_by ?></label>
                                    <select name="order_by" id="order_by" class="form-control form-control-sm">
                                        <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= language()->global->filters->order_by_datetime ?></option>
                                        <option value="response_time" <?= $data->filters->order_by == 'response_time' ? 'selected="selected"' : null ?>><?= language()->monitor_logs->filters->order_by_response_time ?></option>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="order_type" class="small"><?= language()->global->filters->order_type ?></label>
                                    <select name="order_type" id="order_type" class="form-control form-control-sm">
                                        <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= language()->global->filters->order_type_asc ?></option>
                                        <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= language()->global->filters->order_type_desc ?></option>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="results_per_page" class="small"><?= language()->global->filters->results_per_page ?></label>
                                    <select name="results_per_page" id="results_per_page" class="form-control form-control-sm">
                                        <?php foreach($data->filters->allowed_results_per_page as $key): ?>
                                            <option value="<?= $key ?>" <?= $data->filters->results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div class="form-group px-4 mt-4">
                                    <button type="submit" class="btn btn-sm btn-primary btn-block"><?= language()->global->submit ?></button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="table-responsive table-custom-container">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th colspan="4"><?= language()->monitor->checks->header ?></th>
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
                    <?php if(!count($data->monitor_logs)): ?>
                        <tr>
                            <td colspan="4" class="text-muted"><?= language()->monitor->checks->no_data ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data->monitor_logs as $monitor_log): ?>

                            <tr>
                                <td>
                                    <?php if($monitor_log->is_ok): ?>
                                        <i class="fa fa-fw fa-sm fa-check-circle text-success"></i>
                                    <?php else: ?>
                                        <i class="fa fa-fw fa-sm fa-times-circle text-danger"></i>
                                    <?php endif ?>

                                    <?php if($data->monitor->type == 'website' && !$monitor_log->is_ok): ?>
                                        <?php
                                        $monitor_log->error = json_decode($monitor_log->error);
                                        if($monitor_log->error->type == 'exception') {
                                            $error = $monitor_log->error->message;
                                        } elseif(in_array($monitor_log->error->type, ['response_status_code', 'response_body', 'response_header'])) {
                                            $error = language()->monitor->checks->error->{$monitor_log->error->type};
                                        }
                                        ?>

                                        <span class="ml-3" data-toggle="tooltip" title="<?= $error ?>">
                                            <i class="fa fa-fw fa-sm fa-envelope-open-text text-muted"></i>
                                        </span>
                                    <?php endif ?>

                                </td>

                                <td>
                                    <img src="<?= ASSETS_FULL_URL . 'images/countries/' . mb_strtolower($data->ping_servers[$monitor_log->ping_server_id]->country_code) . '.svg' ?>" class="img-fluid icon-favicon" data-toggle="tooltip" title="<?= get_country_from_country_code($data->ping_servers[$monitor_log->ping_server_id]->country_code). ', ' . $data->ping_servers[$monitor_log->ping_server_id]->city_name ?>" />
                                </td>

                                <td>
                                    <?= display_response_time($monitor_log->response_time) ?>
                                </td>

                                <?php if($data->monitor->type == 'website'): ?>
                                    <td><?= $monitor_log->response_status_code ?></td>
                                <?php endif ?>

                                <td>
                                    <span class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($monitor_log->datetime) ?>">
                                        <?= \Altum\Date::get_timeago($monitor_log->datetime) ?>
                                    </span>
                                </td>
                            </tr>

                        <?php endforeach ?>
                    <?php endif ?>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>

    <?php endif ?>

</div>

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/daterangepicker.min.css' ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
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
        redirect(`<?= url('monitor-logs/' . $data->monitor->monitor_id) ?>&start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

    });

</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
