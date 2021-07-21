<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <h1 class="h3 mr-3"><i class="fa fa-fw fa-xs fa-user text-primary-900 mr-2"></i> <?= language()->admin_user_update->header ?></h1>

        <?= include_view(THEME_PATH . 'views/admin/users/admin_user_dropdown_button.php', ['id' => $data->user->user_id]) ?>
    </div>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">

        <form action="" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

            <div class="row">
                <div class="col-12 col-md-4">
                    <h2 class="h4"><?= language()->admin_user_update->main->header ?></h2>
                    <p class="text-muted"><?= language()->admin_user_update->main->subheader ?></p>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="name"><?= language()->admin_user_update->main->name ?></label>
                        <input id="name" type="text" name="name" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->user->name ?>" required="required" />
                        <?= \Altum\Alerts::output_field_error('name') ?>
                    </div>

                    <div class="form-group">
                        <label for="email"><?= language()->admin_user_update->main->email ?></label>
                        <input id="email" type="text" name="email" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" value="<?= $data->user->email ?>" required="required" />
                        <?= \Altum\Alerts::output_field_error('email') ?>
                    </div>

                    <div class="form-group">
                        <label for="is_enabled"><?= language()->admin_user_update->main->is_enabled ?></label>
                        <select id="is_enabled" name="is_enabled" class="form-control form-control-lg">
                            <option value="2" <?= $data->user->active == 2 ? 'selected="selected"' : null ?>><?= language()->admin_user_update->main->is_enabled_disabled ?></option>
                            <option value="1" <?= $data->user->active == 1 ? 'selected="selected"' : null ?>><?= language()->admin_user_update->main->is_enabled_active ?></option>
                            <option value="0" <?= $data->user->active == 0 ? 'selected="selected"' : null ?>><?= language()->admin_user_update->main->is_enabled_unconfirmed ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="type"><?= language()->admin_user_update->main->type ?></label>
                        <select id="type" name="type" class="form-control form-control-lg">
                            <option value="1" <?= $data->user->type == 1 ? 'selected="selected"' : null ?>><?= language()->admin_user_update->main->type_admin ?></option>
                            <option value="0" <?= $data->user->type == 0 ? 'selected="selected"' : null ?>><?= language()->admin_user_update->main->type_user ?></option>
                        </select>
                        <small class="form-text text-muted"><?= language()->admin_user_update->main->type_help ?></small>
                    </div>
                </div>
            </div>

            <div class="mt-5"></div>

            <div class="row">
                <div class="col-12 col-md-4">
                    <h2 class="h4"><?= language()->admin_user_update->plan->header ?></h2>
                    <p class="text-muted"><?= language()->admin_user_update->plan->subheader ?></p>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="plan_id"><?= language()->admin_user_update->plan->plan_id ?></label>
                        <select id="plan_id" name="plan_id" class="form-control form-control-lg">
                            <option value="free" <?= $data->user->plan->plan_id == 'free' ? 'selected="selected"' : null ?>><?= settings()->plan_free->name ?></option>
                            <option value="trial" <?= $data->user->plan->plan_id == 'trial' ? 'selected="selected"' : null ?>><?= settings()->plan_trial->name ?></option>
                            <option value="custom" <?= $data->user->plan->plan_id == 'custom' ? 'selected="selected"' : null ?>><?= settings()->plan_custom->name ?></option>

                            <?php foreach($data->plans as $plan): ?>
                                <option value="<?= $plan->plan_id ?>" <?= $data->user->plan->plan_id == $plan->plan_id ? 'selected="selected"' : null ?>><?= $plan->name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="plan_trial_done"><?= language()->admin_user_update->plan->plan_trial_done ?></label>
                        <select id="plan_trial_done" name="plan_trial_done" class="form-control form-control-lg">
                            <option value="1" <?= $data->user->plan_trial_done ? 'selected="selected"' : null ?>><?= language()->global->yes ?></option>
                            <option value="0" <?= !$data->user->plan_trial_done ? 'selected="selected"' : null ?>><?= language()->global->no ?></option>
                        </select>
                    </div>

                    <div id="plan_expiration_date_container" class="form-group">
                        <label for="plan_expiration_date"><?= language()->admin_user_update->plan->plan_expiration_date ?></label>
                        <input id="plan_expiration_date" type="text" name="plan_expiration_date" class="form-control form-control-lg" autocomplete="off" value="<?= $data->user->plan_expiration_date ?>">
                        <div class="invalid-feedback">
                            <?= language()->admin_user_update->plan->plan_expiration_date_invalid ?>
                        </div>
                    </div>

                    <div id="plan_settings" style="display: none">
                        <div class="form-group">
                            <label for="monitors_limit"><?= language()->admin_plans->plan->monitors_limit ?></label>
                            <input type="number" id="monitors_limit" name="monitors_limit" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->monitors_limit ?>" />
                            <small class="form-text text-muted"><?= language()->admin_plans->plan->monitors_limit_help ?></small>
                        </div>

                        <div class="form-group">
                            <label for="heartbeats_limit"><?= language()->admin_plans->plan->heartbeats_limit ?></label>
                            <input type="number" id="heartbeats_limit" name="heartbeats_limit" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->heartbeats_limit ?>" />
                            <small class="form-text text-muted"><?= language()->admin_plans->plan->heartbeats_limit_help ?></small>
                        </div>

                        <div class="form-group">
                            <label for="status_pages_limit"><?= language()->admin_plans->plan->status_pages_limit ?></label>
                            <input type="number" id="status_pages_limit" name="status_pages_limit" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->status_pages_limit ?>" />
                            <small class="form-text text-muted"><?= language()->admin_plans->plan->status_pages_limit_help ?></small>
                        </div>

                        <div class="form-group">
                            <label for="projects_limit"><?= language()->admin_plans->plan->projects_limit ?></label>
                            <input type="number" id="projects_limit" name="projects_limit" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->projects_limit ?>" />
                            <small class="form-text text-muted"><?= language()->admin_plans->plan->projects_limit_help ?></small>
                        </div>

                        <div class="form-group">
                            <label for="domains_limit"><?= language()->admin_plans->plan->domains_limit ?></label>
                            <input type="number" id="domains_limit" name="domains_limit" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->domains_limit ?>" />
                            <small class="form-text text-muted"><?= language()->admin_plans->plan->domains_limit_help ?></small>
                        </div>

                        <div class="form-group">
                            <label for="logs_retention"><?= language()->admin_plans->plan->logs_retention ?></label>
                            <input type="number" id="logs_retention" name="logs_retention" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->logs_retention ?>" />
                            <small class="form-text text-muted"><?= language()->admin_plans->plan->logs_retention_help ?></small>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="additional_domains_is_enabled" name="additional_domains_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->additional_domains_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="additional_domains_is_enabled"><?= language()->admin_plans->plan->additional_domains_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= language()->admin_plans->plan->additional_domains_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="no_ads" name="no_ads" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->no_ads ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="no_ads"><?= language()->admin_plans->plan->no_ads ?></label>
                            <div><small class="form-text text-muted"><?= language()->admin_plans->plan->no_ads_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="analytics_is_enabled" name="analytics_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->analytics_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="analytics_is_enabled"><?= language()->admin_plans->plan->analytics_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= language()->admin_plans->plan->analytics_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="removable_branding_is_enabled" name="removable_branding_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->removable_branding_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="removable_branding_is_enabled"><?= language()->admin_plans->plan->removable_branding_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= language()->admin_plans->plan->removable_branding_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="custom_url_is_enabled" name="custom_url_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->custom_url_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="custom_url_is_enabled"><?= language()->admin_plans->plan->custom_url_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= language()->admin_plans->plan->custom_url_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="password_protection_is_enabled" name="password_protection_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->password_protection_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="password_protection_is_enabled"><?= language()->admin_plans->plan->password_protection_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= language()->admin_plans->plan->password_protection_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="search_engine_block_is_enabled" name="search_engine_block_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->search_engine_block_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="search_engine_block_is_enabled"><?= language()->admin_plans->plan->search_engine_block_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= language()->admin_plans->plan->search_engine_block_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="custom_css_is_enabled" name="custom_css_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->custom_css_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="custom_css_is_enabled"><?= language()->admin_plans->plan->custom_css_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= language()->admin_plans->plan->custom_css_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="custom_js_is_enabled" name="custom_js_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->custom_js_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="custom_js_is_enabled"><?= language()->admin_plans->plan->custom_js_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= language()->admin_plans->plan->custom_js_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="email_reports_is_enabled" name="email_reports_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->email_reports_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="email_reports_is_enabled"><?= language()->admin_plans->plan->email_reports_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= language()->admin_plans->plan->email_reports_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="email_notifications_is_enabled" name="email_notifications_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->email_notifications_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="email_notifications_is_enabled"><?= language()->admin_plans->plan->email_notifications_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= language()->admin_plans->plan->email_notifications_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="twilio_notifications_is_enabled" name="twilio_notifications_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->twilio_notifications_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="twilio_notifications_is_enabled"><?= language()->admin_plans->plan->twilio_notifications_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= language()->admin_plans->plan->twilio_notifications_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="api_is_enabled" name="api_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->api_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="api_is_enabled"><?= language()->admin_plans->plan->api_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= language()->admin_plans->plan->api_is_enabled_help ?></small></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5"></div>

            <div class="row">
                <div class="col-12 col-md-4">
                    <h2 class="h4"><?= language()->admin_user_update->change_password->header ?></h2>
                    <p class="text-muted"><?= language()->admin_user_update->change_password->subheader ?></p>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="new_password"><?= language()->admin_user_update->change_password->new_password ?></label>
                        <input id="new_password" type="password" name="new_password" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('new_password') ? 'is-invalid' : null ?>" />
                        <?= \Altum\Alerts::output_field_error('new_password') ?>
                    </div>

                    <div class="form-group">
                        <label for="repeat_password"><?= language()->admin_user_update->change_password->repeat_password ?></label>
                        <input id="repeat_password" type="password" name="repeat_password" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('new_password') ? 'is-invalid' : null ?>" />
                        <?= \Altum\Alerts::output_field_error('new_password') ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-4"></div>

                <div class="col">
                    <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= language()->global->update ?></button>
                </div>
            </div>

        </form>
    </div>
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

    let check_plan_id = () => {
        let selected_plan_id = document.querySelector('[name="plan_id"]').value;

        if(selected_plan_id == 'free') {
            document.querySelector('#plan_expiration_date_container').style.display = 'none';
        } else {
            document.querySelector('#plan_expiration_date_container').style.display = 'block';
        }

        if(selected_plan_id == 'custom') {
            document.querySelector('#plan_settings').style.display = 'block';
        } else {
            document.querySelector('#plan_settings').style.display = 'none';
        }
    };

    check_plan_id();

    /* Dont show expiration date when the chosen plan is the free one */
    document.querySelector('[name="plan_id"]').addEventListener('change', check_plan_id);

    /* Check for expiration date to show a warning if expired */
    let check_plan_expiration_date = () => {
        let plan_expiration_date = document.querySelector('[name="plan_expiration_date"]');

        let plan_expiration_date_object = new Date(plan_expiration_date.value);
        let today_date_object = new Date();

        if(plan_expiration_date_object < today_date_object) {
            plan_expiration_date.classList.add('is-invalid');
        } else {
            plan_expiration_date.classList.remove('is-invalid');
        }
    };

    check_plan_expiration_date();
    document.querySelector('[name="plan_expiration_date"]').addEventListener('change', check_plan_expiration_date);

    /* Daterangepicker */
    $('[name="plan_expiration_date"]').daterangepicker({
        startDate: <?= json_encode($data->user->plan_expiration_date) ?>,
        minDate: new Date(),
        alwaysShowCalendars: true,
        singleCalendar: true,
        singleDatePicker: true,
        locale: <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>,
    }, (start, end, label) => {
        check_plan_expiration_date()
    });

</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
