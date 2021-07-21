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
                <li class="active" aria-current="page"><?= language()->monitor_create->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <h1 class="h4 text-truncate"><?= language()->monitor_create->header ?></h1>
    <p></p>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="name"><i class="fa fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= language()->monitor->input->name ?></label>
                    <input type="text" id="name" name="name" class="form-control <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->values['name'] ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('name') ?>
                    <small class="form-text text-muted"><?= language()->monitor->input->name_help ?></small>
                </div>

                <div class="form-group">
                    <label for="type"><i class="fa fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= language()->monitor->input->type ?></label>
                    <select id="type" name="type" class="form-control" required="required">
                        <option value="website" <?= $data->values['type'] == 'website' ? 'selected="selected"' : null ?>><?= language()->monitor->input->type_website ?></option>
                        <option value="ping" <?= $data->values['type'] == 'ping' ? 'selected="selected"' : null ?>><?= language()->monitor->input->type_ping ?></option>
                        <option value="port" <?= $data->values['type'] == 'ping' ? 'selected="selected"' : null ?>><?= language()->monitor->input->type_port ?></option>
                    </select>
                    <small id="type_website_help" data-type="website" class="form-text text-muted"><?= language()->monitor->input->type_website_help ?></small id=type_help>
                    <small id="type_ping_help" data-type="ping" class="form-text text-muted"><?= language()->monitor->input->type_ping_help ?></small id=type_help>
                    <small id="type_port_help" data-type="port" class="form-text text-muted"><?= language()->monitor->input->type_port_help ?></small>
                </div>

                <div class="form-group" data-type="website">
                    <label for="target_website_url"><i class="fa fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= language()->monitor->input->target_url ?></label>
                    <input type="text" id="target_website_url" name="target" class="form-control <?= \Altum\Alerts::has_field_errors('target') ? 'is-invalid' : null ?>" value="<?= $data->values['target'] ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('target') ?>
                </div>

                <div class="form-group" data-type="ping">
                    <label for="target_ping_host"><i class="fa fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= language()->monitor->input->target_host ?></label>
                    <input type="text" id="target_ping_host" name="target" class="form-control" value="<?= $data->values['target'] ?>" required="required" />
                </div>

                <div class="row" data-type="port">
                    <div class="col-lg-3">
                        <div class="form-group" data-type="port">
                            <label for="target_port_host"><i class="fa fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= language()->monitor->input->target_host ?></label>
                            <input type="text" id="target_port_host" name="target" class="form-control" value="<?= $data->values['target'] ?>" required="required" />
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="form-group" data-type="port">
                            <label for="target_port_port"><i class="fa fa-fw fa-sm fa-dna text-muted mr-1"></i> <?= language()->monitor->input->target_port ?></label>
                            <input type="text" id="target_port_port" name="port" class="form-control" value="<?= $data->values['port'] ?>" required="required" />
                        </div>
                    </div>
                </div>

                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item mr-1" role="presentation">
                        <a class="btn btn-sm btn-outline-blue-500" id="advanced-tab" data-toggle="tab" href="#advanced" role="tab" aria-controls="advanced" aria-selected="true"><?= language()->monitor->input->advanced ?></a>
                    </li>
                    <li class="nav-item mr-1" role="presentation" data-type="website">
                        <a class="btn btn-sm btn-outline-blue-500" id="custom-request-tab" data-toggle="tab" href="#custom-request" role="tab" aria-controls="custom-request" aria-selected="true"><?= language()->monitor->input->custom_request ?></a>
                    </li>
                    <li class="nav-item mr-1" role="presentation" data-type="website">
                        <a class="btn btn-sm btn-outline-blue-500" id="custom-response-tab" data-toggle="tab" href="#custom-response" role="tab" aria-controls="custom-response" aria-selected="true"><?= language()->monitor->input->custom_response ?></a>
                    </li>
                </ul>

                <div class="tab-content my-3">
                    <div class="tab-pane fade" id="advanced" role="tabpanel" aria-labelledby="advanced-tab">

                        <div class="mb-3">
                            <div><i class="fa fa-fw fa-sm fa-map-marked-alt text-muted mr-1"></i><?= language()->monitor->input->ping_servers_ids ?></div>
                            <div><small class="form-text text-muted"><?= language()->monitor->input->ping_servers_ids_help ?></small></div>

                            <div class="row">
                                <?php foreach($data->ping_servers as $ping_server): ?>
                                    <div class="col-12 col-lg-6">
                                        <div class="custom-control custom-checkbox my-2">
                                            <input id="ping_server_id_<?= $ping_server->ping_server_id ?>" name="ping_servers_ids[]" value="<?= $ping_server->ping_server_id ?>" type="checkbox" class="custom-control-input" <?= in_array($ping_server->ping_server_id, $data->values['ping_servers_ids']) ? 'checked="checked"' : null ?>>
                                            <label class="custom-control-label d-flex align-items-center" for="ping_server_id_<?= $ping_server->ping_server_id ?>">
                                                <img src="<?= ASSETS_FULL_URL . 'images/countries/' . mb_strtolower($ping_server->country_code) . '.svg' ?>" class="img-fluid icon-favicon mr-1" />
                                                <span class="mr-1"><?= $ping_server->city_name ?></span>
                                                <small class="badge badge-light badge-pill"><?= $ping_server->name ?></small>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>

                        <div <?= $this->user->plan_settings->email_notifications_is_enabled ? null : 'data-toggle="tooltip" title="' . language()->global->info_message->plan_feature_no_access . '"' ?>>
                            <div class="form-group <?= $this->user->plan_settings->email_notifications_is_enabled ? null : 'container-disabled' ?>">
                                <label for="email_notifications_is_enabled"><?= language()->monitor->input->email_notifications_is_enabled ?></label>
                                <select id="email_notifications_is_enabled" name="email_notifications_is_enabled" class="form-control">
                                    <option value="1" <?= $data->values['email_notifications_is_enabled'] == 1 ? 'selected="selected"' : null ?>><?= language()->global->yes ?></option>
                                    <option value="0" <?= $data->values['email_notifications_is_enabled'] == 0 ? 'selected="selected"' : null ?>><?= language()->global->no ?></option>
                                </select>
                                <small class="form-text text-muted"><?= language()->monitor->input->email_notifications_is_enabled_help ?></small>
                            </div>
                        </div>

                        <?php if(settings()->monitors_heartbeats->email_reports_is_enabled): ?>
                        <div <?= $this->user->plan_settings->email_reports_is_enabled ? null : 'data-toggle="tooltip" title="' . language()->global->info_message->plan_feature_no_access . '"' ?>>
                            <div class="form-group <?= $this->user->plan_settings->email_reports_is_enabled ? null : 'container-disabled' ?>">
                                <label for="email_reports_is_enabled"><?= language()->monitor->input->email_reports_is_enabled ?></label>
                                <select id="email_reports_is_enabled" name="email_reports_is_enabled" class="form-control">
                                    <option value="1" <?= $data->values['email_reports_is_enabled'] == 1 ? 'selected="selected"' : null ?>><?= language()->global->yes ?></option>
                                    <option value="0" <?= $data->values['email_reports_is_enabled'] == 0 ? 'selected="selected"' : null ?>><?= language()->global->no ?></option>
                                </select>
                                <small class="form-text text-muted"><?= language()->monitor->input->email_reports_is_enabled_help ?></small>
                            </div>
                        </div>
                        <?php endif ?>

                        <div class="row" data-type="website">
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="ssl_is_enabled"><?= language()->monitor->input->ssl_is_enabled ?></label>
                                    <select id="ssl_is_enabled" name="ssl_is_enabled" class="form-control">
                                        <option value="1" <?= $data->monitor->settings->ssl_is_enabled ? 'selected="selected"' : null ?>><?= language()->global->yes ?></option>
                                        <option value="0" <?= !$data->monitor->settings->ssl_is_enabled ? 'selected="selected"' : null ?>><?= language()->global->no ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="ssl_email_notifications_is_enabled"><?= language()->monitor->input->ssl_email_notifications_is_enabled ?></label>
                                    <select id="ssl_email_notifications_is_enabled" name="ssl_email_notifications_is_enabled" class="form-control" required="required">
                                        <option value="1" <?= $data->monitor->notifications->ssl_email_is_enabled == 1 ? 'selected="selected"' : null ?>><?= sprintf(language()->monitor->input->ssl_email_notifications_is_enabled_input, '1 ' . language()->global->date->day) ?></option>
                                        <option value="2" <?= $data->monitor->notifications->ssl_email_is_enabled == 2 ? 'selected="selected"' : null ?>><?= sprintf(language()->monitor->input->ssl_email_notifications_is_enabled_input, '2 ' . language()->global->date->days) ?></option>
                                        <option value="3" <?= $data->monitor->notifications->ssl_email_is_enabled == 3 ? 'selected="selected"' : null ?>><?= sprintf(language()->monitor->input->ssl_email_notifications_is_enabled_input, '3 ' . language()->global->date->days) ?></option>
                                        <option value="7" <?= $data->monitor->notifications->ssl_email_is_enabled == 7 ? 'selected="selected"' : null ?>><?= sprintf(language()->monitor->input->ssl_email_notifications_is_enabled_input, '7 ' . language()->global->date->days) ?></option>
                                        <option value="14" <?= $data->monitor->notifications->ssl_email_is_enabled == 14 ? 'selected="selected"' : null ?>><?= sprintf(language()->monitor->input->ssl_email_notifications_is_enabled_input, '14 ' . language()->global->date->days) ?></option>
                                        <option value="30" <?= $data->monitor->notifications->ssl_email_is_enabled == 30 ? 'selected="selected"' : null ?>><?= sprintf(language()->monitor->input->ssl_email_notifications_is_enabled_input, '1 ' . language()->global->date->month) ?></option>
                                        <option value="60" <?= $data->monitor->notifications->ssl_email_is_enabled == 60 ? 'selected="selected"' : null ?>><?= sprintf(language()->monitor->input->ssl_email_notifications_is_enabled_input, '2 ' . language()->global->date->month) ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="webhook_notifications"><?= language()->monitor->input->webhook_notifications ?></label>
                            <input type="url" id="webhook_notifications" name="webhook_notifications" class="form-control" value="<?= $data->values['webhook_notifications'] ?>" />
                            <small class="form-text text-muted"><?= language()->monitor->input->webhook_notifications_help ?></small>
                        </div>

                        <div class="form-group">
                            <label for="slack_notifications"><?= language()->monitor->input->slack_notifications ?></label>
                            <input type="url" id="slack_notifications" name="slack_notifications" class="form-control" value="<?= $data->values['slack_notifications'] ?>" />
                            <small class="form-text text-muted"><?= language()->monitor->input->slack_notifications_help ?></small>
                        </div>

                        <?php if(settings()->monitors_heartbeats->twilio_notifications_is_enabled): ?>
                            <div <?= $this->user->plan_settings->twilio_notifications_is_enabled ? null : 'data-toggle="tooltip" title="' . language()->global->info_message->plan_feature_no_access . '"' ?>>
                                <div class="form-group <?= $this->user->plan_settings->twilio_notifications_is_enabled ? null : 'container-disabled' ?>">
                                    <label for="twilio_notifications"><?= language()->monitor->input->twilio_notifications ?></label>
                                    <input type="text" id="twilio_notifications" name="twilio_notifications" class="form-control" value="<?= $data->values['twilio_notifications'] ?>" />
                                    <small class="form-text text-muted"><?= language()->heartbeat->input->twilio_notifications_help ?></small>
                                </div>
                            </div>
                        <?php endif ?>

                        <div class="form-group">
                            <label for="project_id"><i class="fa fa-fw fa-sm fa-project-diagram text-muted mr-1"></i> <?= language()->projects->project_id ?></label>
                            <select id="project_id" name="project_id" class="form-control">
                                <option value=""><?= language()->projects->project_id_null ?></option>
                                <?php foreach($data->projects as $project_id => $project): ?>
                                    <option value="<?= $project_id ?>" <?= $data->values['project_id'] == $project_id ? 'selected="selected"' : null ?>><?= $project->name ?></option>
                                <?php endforeach ?>
                            </select>
                            <small class="form-text text-muted"><?= language()->projects->project_id_help ?></small>
                        </div>

                        <div class="form-group">
                            <label for="check_interval_seconds"><?= language()->monitor->input->check_interval_seconds ?></label>
                            <select id="check_interval_seconds" name="check_interval_seconds" class="form-control" required="required">
                                <option value="60" <?= $data->values['check_interval_seconds'] == 60 ? 'selected="selected"' : null ?>><?= '1 ' . language()->global->date->minute ?></option>
                                <option value="180" <?= $data->values['check_interval_seconds'] == 180 ? 'selected="selected"' : null ?>><?= '3 ' . language()->global->date->minutes ?></option>
                                <option value="300" <?= $data->values['check_interval_seconds'] == 300 ? 'selected="selected"' : null ?>><?= '5 ' . language()->global->date->minutes ?></option>
                                <option value="600" <?= $data->values['check_interval_seconds'] == 600 ? 'selected="selected"' : null ?>><?= '10 ' . language()->global->date->minutes ?></option>
                                <option value="1800" <?= $data->values['check_interval_seconds'] == 1800 ? 'selected="selected"' : null ?>><?= '30 ' . language()->global->date->minutes ?></option>
                                <option value="3600" <?= $data->values['check_interval_seconds'] == 3600 ? 'selected="selected"' : null ?>><?= '60 ' . language()->global->date->minutes ?></option>
                            </select>
                            <small class="form-text text-muted"><?= language()->monitor->input->check_interval_seconds_help ?></small>
                        </div>

                        <div class="form-group">
                            <label for="timeout_seconds"><?= language()->monitor->input->timeout_seconds ?></label>
                            <select id="timeout_seconds" name="timeout_seconds" class="form-control" required="required">
                                <option value="1" <?= $data->values['timeout_seconds'] == 1 ? 'selected="selected"' : null ?>><?= '1 ' . language()->global->date->second ?></option>
                                <option value="2" <?= $data->values['timeout_seconds'] == 2 ? 'selected="selected"' : null ?>><?= '2 ' . language()->global->date->seconds ?></option>
                                <option value="3" <?= $data->values['timeout_seconds'] == 3 ? 'selected="selected"' : null ?>><?= '3 ' . language()->global->date->seconds ?></option>
                                <option value="5" <?= $data->values['timeout_seconds'] == 5 ? 'selected="selected"' : null ?>><?= '5 ' . language()->global->date->seconds ?></option>
                                <option value="10" <?= $data->values['timeout_seconds'] == 10 ? 'selected="selected"' : null ?>><?= '10 ' . language()->global->date->seconds ?></option>
                            </select>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="custom-request" role="tabpanel" aria-labelledby="custom-request-tab" data-type="website">

                        <div class="form-group">
                            <label for="request_method"><?= language()->monitor->input->request_method ?></label>
                            <select id="request_method" name="request_method" class="form-control" required="required">
                                <option value="GET" <?= $data->values['request_method'] == 'GET' ? 'selected="selected"' : null ?>>GET</option>
                                <option value="POST" <?= $data->values['request_method'] == 'POST' ? 'selected="selected"' : null ?>>POST</option>
                                <option value="HEAD" <?= $data->values['request_method'] == 'HEAD' ? 'selected="selected"' : null ?>>HEAD</option>
                                <option value="OPTIONS" <?= $data->values['request_method'] == 'OPTIONS' ? 'selected="selected"' : null ?>>OPTIONS</option>
                                <option value="PUT" <?= $data->values['request_method'] == 'PUT' ? 'selected="selected"' : null ?>>PUT</option>
                                <option value="PATCH" <?= $data->values['request_method'] == 'PATCH' ? 'selected="selected"' : null ?>>PATCH</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="request_body"><?= language()->monitor->input->request_body ?></label>
                            <textarea id="request_body" name="request_body" class="form-control"><?= $data->values['request_body'] ?></textarea>
                            <small class="form-text text-muted"><?= language()->monitor->input->request_body_help ?></small>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-lg-6">
                                <label for="request_basic_auth_username"><?= language()->monitor->input->request_basic_auth_username ?></label>
                                <input type="text" id="request_basic_auth_username" name="request_basic_auth_username" class="form-control" value="<?= $data->values['request_basic_auth_username'] ?>" autocomplete="off" />
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="request_basic_auth_password"><?= language()->monitor->input->request_basic_auth_password ?></label>
                                <input type="text" id="request_basic_auth_password" name="request_basic_auth_password" class="form-control" value="<?= $data->values['request_basic_auth_password'] ?>" autocomplete="off" />
                            </div>
                        </div>

                        <label><?= language()->monitor->input->request_headers ?></label>
                        <div id="request_headers">
                            <?php foreach($data->values['request_headers'] as $key => $request_header): ?>
                                <div class="form-row">
                                    <div class="form-group col-lg-6">
                                        <input type="text" name="request_header_name[<?= $key ?>]" class="form-control" value="<?= $request_header->name ?>" placeholder="<?= language()->monitor->input->request_header_name ?>" />
                                    </div>

                                    <div class="form-group col-lg-5">
                                        <input type="text" name="request_header_value[<?= $key ?>]" class="form-control" value="<?= $request_header->value ?>" placeholder="<?= language()->monitor->input->request_header_value ?>" />
                                    </div>

                                    <div class="form-group col-lg-1 text-center">
                                        <button type="button" data-remove="request" class="btn btn-outline-danger" title="<?= language()->monitor->input->request_header_remove ?>"><i class="fa fa-fw fa-times"></i></button>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                        <div class="mb-3">
                            <button data-add="request" type="button" class="btn btn-sm btn-outline-success"><i class="fa fa-fw fa-plus-circle"></i> <?= language()->monitor->input->request_header_add ?></button>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="custom-response" role="tabpanel" aria-labelledby="custom-response-tab" data-type="website">

                        <div class="form-group">
                            <label for="response_status_code"><?= language()->monitor->input->response_status_code ?></label>
                            <input type="text" id="response_status_code" name="response_status_code" class="form-control" value="<?= $data->values['response_status_code'] ?>" required="required" />
                        </div>

                        <div class="form-group">
                            <label for="response_body"><?= language()->monitor->input->response_body ?></label>
                            <textarea id="response_body" name="response_body" class="form-control"><?= $data->values['response_body'] ?></textarea>
                            <small class="form-text text-muted"><?= language()->monitor->input->response_body_help ?></small>
                        </div>

                        <label><?= language()->monitor->input->response_headers ?></label>
                        <div id="response_headers">
                            <?php foreach($data->values['response_headers'] as $key => $response_header): ?>
                                <div class="form-row">
                                    <div class="form-group col-lg-6">
                                        <input type="text" name="response_header_name[<?= $key ?>]" class="form-control" value="<?= $response_header->name ?>" placeholder="<?= language()->monitor->input->response_header_name ?>" />
                                    </div>

                                    <div class="form-group col-lg-5">
                                        <input type="text" name="response_header_value[<?= $key ?>]" class="form-control" value="<?= $response_header->value ?>" placeholder="<?= language()->monitor->input->response_header_value ?>" />
                                    </div>

                                    <div class="form-group col-lg-1 text-center">
                                        <button type="button" data-remove="response" class="btn btn-outline-danger" title="<?= language()->monitor->input->response_header_remove ?>"><i class="fa fa-fw fa-times"></i></button>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                        <div class="mb-3">
                            <button data-add="response" type="button" class="btn btn-sm btn-outline-success"><i class="fa fa-fw fa-plus-circle"></i> <?= language()->monitor->input->request_header_add ?></button>
                        </div>

                    </div>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary mt-4"><?= language()->global->create ?></button>
            </form>

        </div>
    </div>
</div>

<template id="template_request_header">
    <div class="form-row">
        <div class="form-group col-lg-6">
            <input type="text" name="request_header_name[]" class="form-control" value="" placeholder="<?= language()->monitor->input->request_header_name ?>" />
        </div>

        <div class="form-group col-lg-5">
            <input type="text" name="request_header_value[]" class="form-control" value="" placeholder="<?= language()->monitor->input->request_header_value ?>" />
        </div>

        <div class="form-group col-lg-1 text-center">
            <button type="button" data-remove="request" class="btn btn-outline-danger" title="<?= language()->monitor->input->request_header_remove ?>"><i class="fa fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<template id="template_response_header">
    <div class="form-row">
        <div class="form-group col-lg-6">
            <input type="text" name="response_header_name[]" class="form-control" value="" placeholder="<?= language()->monitor->input->response_header_name ?>" />
        </div>

        <div class="form-group col-lg-5">
            <input type="text" name="response_header_value[]" class="form-control" value="" placeholder="<?= language()->monitor->input->response_header_value ?>" />
        </div>

        <div class="form-group col-lg-1 text-center">
            <button type="button" data-remove="response" class="btn btn-outline-danger" title="<?= language()->monitor->input->response_header_remove ?>"><i class="fa fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<?php ob_start() ?>
<script>
'use strict';

/* Type handler */
let type_handler = () => {
    let type = document.querySelector('select[name="type"]').value;

    document.querySelectorAll(`[data-type]:not([data-type="${type}"])`).forEach(element => {
        element.classList.add('d-none');

        element.querySelector('input[name="target"],input[name="port"]') && element.querySelector('input[name="target"],input[name="port"]').setAttribute('disabled', 'disabled');
        element.querySelector('input[name="target"],input[name="port"]') && element.querySelector('input[name="target"],input[name="port"]').removeAttribute('required');
    });

    document.querySelectorAll(`[data-type="${type}"]`).forEach(element => {
        element.classList.remove('d-none');

        element.querySelector('input[name="target"],input[name="port"]') && element.querySelector('input[name="target"],input[name="port"]').removeAttribute('disabled');
        element.querySelector('input[name="target"],input[name="port"]') && element.querySelector('input[name="target"],input[name="port"]').setAttribute('required', 'required');
    });
}

type_handler();

document.querySelector('select[name="type"]') && document.querySelector('select[name="type"]').addEventListener('change', type_handler);

/* add new request header */
let header_add = event => {
    let type = event.currentTarget.getAttribute('data-add');

    let clone = document.querySelector(`#template_${type}_header`).content.cloneNode(true);

    let request_headers_count = document.querySelectorAll(`#${type}_headers .form-row`).length;

    clone.querySelector(`input[name="${type}_header_name[]"`).setAttribute('name', `${type}_header_name[${request_headers_count}]`);
    clone.querySelector(`input[name="${type}_header_value[]"`).setAttribute('name', `${type}_header_value[${request_headers_count}]`);

    document.querySelector(`#${type}_headers`).appendChild(clone);

    header_remove_initiator();
};

document.querySelectorAll('[data-add]').forEach(element => {
    element.addEventListener('click', header_add);
})


/* remove request header */
let header_remove = event => {
    event.currentTarget.closest('.form-row').remove();
};

let header_remove_initiator = () => {
    document.querySelectorAll('#request_headers [data-remove], #response_headers [data-remove]').forEach(element => {
        element.removeEventListener('click', header_remove);
        element.addEventListener('click', header_remove)
    })
};

header_remove_initiator();
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
