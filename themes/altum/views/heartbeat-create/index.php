<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <small>
            <ol class="custom-breadcrumbs">
                <li>
                    <a href="<?= url('heartbeats') ?>"><?= language()->heartbeats->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page"><?= language()->heartbeat_create->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <h1 class="h4 text-truncate"><?= language()->heartbeat_create->header ?></h1>
    <p></p>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="name"><i class="fa fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= language()->heartbeat->input->name ?></label>
                    <input type="text" id="name" name="name" class="form-control <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->values['name'] ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('name') ?>
                    <small class="form-text text-muted"><?= language()->heartbeat->input->name_help ?></small>
                </div>

                <div class="form-row">
                    <div class="form-group col">
                        <label for="run_interval"><?= language()->heartbeat->input->run_interval ?></label>
                        <input type="number" step="1" id="run_interval" name="run_interval" class="form-control" value="<?= $data->values['run_interval'] ?>" />
                    </div>

                    <div class="form-group col">
                        <label>&nbsp;</label>
                        <select id="run_interval_type" name="run_interval_type" class="form-control">
                            <option value="minutes" <?= $data->values['run_interval_type'] == 'minutes' ? 'selected="selected"' : null ?>><?= language()->global->date->minutes ?></option>
                            <option value="hours" <?= $data->values['run_interval_type'] == 'hours' ? 'selected="selected"' : null ?>><?= language()->global->date->hours ?></option>
                            <option value="days" <?= $data->values['run_interval_type'] == 'days' ? 'selected="selected"' : null ?>><?= language()->global->date->days ?></option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col">
                        <label for="run_interval_grace"><?= language()->heartbeat->input->run_interval_grace ?></label>
                        <input type="number" step="1" id="run_interval_grace" name="run_interval_grace" class="form-control" value="<?= $data->values['run_interval_grace'] ?>" />
                        <small class="form-text text-muted"><?= language()->heartbeat->input->run_interval_grace_help ?></small>
                    </div>

                    <div class="form-group col">
                        <label>&nbsp;</label>
                        <select id="run_interval_grace_type" name="run_interval_grace_type" class="form-control">
                            <option value="seconds" <?= $data->values['run_interval_grace_type'] == 'seconds' ? 'selected="selected"' : null ?>><?= language()->global->date->seconds ?></option>
                            <option value="minutes" <?= $data->values['run_interval_grace_type'] == 'minutes' ? 'selected="selected"' : null ?>><?= language()->global->date->minutes ?></option>
                            <option value="hours" <?= $data->values['run_interval_grace_type'] == 'hours' ? 'selected="selected"' : null ?>><?= language()->global->date->hours ?></option>
                            <option value="days" <?= $data->values['run_interval_grace_type'] == 'days' ? 'selected="selected"' : null ?>><?= language()->global->date->days ?></option>
                        </select>
                    </div>
                </div>

                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item mr-1" role="presentation">
                        <a class="btn btn-sm btn-outline-blue-500" id="advanced-tab" data-toggle="tab" href="#advanced" role="tab" aria-controls="advanced" aria-selected="true"><?= language()->heartbeat->input->advanced ?></a>
                    </li>
                </ul>

                <div class="tab-content my-3">
                    <div class="tab-pane fade" id="advanced" role="tabpanel" aria-labelledby="advanced-tab">

                        <div <?= $this->user->plan_settings->email_notifications_is_enabled ? null : 'data-toggle="tooltip" title="' . language()->global->info_message->plan_feature_no_access . '"' ?>>
                            <div class="form-group <?= $this->user->plan_settings->email_notifications_is_enabled ? null : 'container-disabled' ?>">
                                <label for="email_notifications_is_enabled"><?= language()->heartbeat->input->email_notifications_is_enabled ?></label>
                                <select id="email_notifications_is_enabled" name="email_notifications_is_enabled" class="form-control">
                                    <option value="1" <?= $data->values['email_notifications_is_enabled'] == 1 ? 'selected="selected"' : null ?>><?= language()->global->yes ?></option>
                                    <option value="0" <?= $data->values['email_notifications_is_enabled'] == 0 ? 'selected="selected"' : null ?>><?= language()->global->no ?></option>
                                </select>
                                <small class="form-text text-muted"><?= language()->heartbeat->input->email_notifications_is_enabled_help ?></small>
                            </div>
                        </div>

                        <?php if(settings()->monitors_heartbeats->email_reports_is_enabled): ?>
                            <div <?= $this->user->plan_settings->email_reports_is_enabled ? null : 'data-toggle="tooltip" title="' . language()->global->info_message->plan_feature_no_access . '"' ?>>
                                <div class="form-group <?= $this->user->plan_settings->email_reports_is_enabled ? null : 'container-disabled' ?>">
                                    <label for="email_reports_is_enabled"><?= language()->heartbeat->input->email_reports_is_enabled ?></label>
                                    <select id="email_reports_is_enabled" name="email_reports_is_enabled" class="form-control">
                                        <option value="1" <?= $data->values['email_reports_is_enabled'] == 1 ? 'selected="selected"' : null ?>><?= language()->global->yes ?></option>
                                        <option value="0" <?= $data->values['email_reports_is_enabled'] == 0 ? 'selected="selected"' : null ?>><?= language()->global->no ?></option>
                                    </select>
                                    <small class="form-text text-muted"><?= language()->heartbeat->input->email_reports_is_enabled_help ?></small>
                                </div>
                            </div>
                        <?php endif ?>

                        <div class="form-group">
                            <label for="webhook_notifications"><?= language()->heartbeat->input->webhook_notifications ?></label>
                            <input type="url" id="webhook_notifications" name="webhook_notifications" class="form-control" value="<?= $data->values['webhook_notifications'] ?>" />
                            <small class="form-text text-muted"><?= language()->heartbeat->input->webhook_notifications_help ?></small>
                        </div>

                        <div class="form-group">
                            <label for="slack_notifications"><?= language()->heartbeat->input->slack_notifications ?></label>
                            <input type="url" id="slack_notifications" name="slack_notifications" class="form-control" value="<?= $data->values['slack_notifications'] ?>" />
                            <small class="form-text text-muted"><?= language()->heartbeat->input->slack_notifications_help ?></small>
                        </div>

                        <?php if(settings()->monitors_heartbeats->twilio_notifications_is_enabled): ?>
                            <div <?= $this->user->plan_settings->twilio_notifications_is_enabled ? null : 'data-toggle="tooltip" title="' . language()->global->info_message->plan_feature_no_access . '"' ?>>
                                <div class="form-group <?= $this->user->plan_settings->twilio_notifications_is_enabled ? null : 'container-disabled' ?>">
                                    <label for="twilio_notifications"><?= language()->heartbeat->input->twilio_notifications ?></label>
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

                    </div>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary mt-4"><?= language()->global->create ?></button>
            </form>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
'use strict';

</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
