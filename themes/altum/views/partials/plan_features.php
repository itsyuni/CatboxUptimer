<?php defined('ALTUMCODE') || die() ?>

<ul class="list-style-none m-0">
    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->monitors_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->monitors_limit ? null : 'text-muted' ?>">
            <?php if($data->plan_settings->monitors_limit == -1): ?>
                <?= language()->global->plan_settings->unlimited_monitors_limit ?>
            <?php else: ?>
                <?= sprintf(language()->global->plan_settings->monitors_limit, '<strong>' . nr($data->plan_settings->monitors_limit) . '</strong>') ?>
            <?php endif ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->heartbeats_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->heartbeats_limit ? null : 'text-muted' ?>">
            <?php if($data->plan_settings->heartbeats_limit == -1): ?>
                <?= language()->global->plan_settings->unlimited_heartbeats_limit ?>
            <?php else: ?>
                <?= sprintf(language()->global->plan_settings->heartbeats_limit, '<strong>' . nr($data->plan_settings->heartbeats_limit) . '</strong>') ?>
            <?php endif ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->status_pages_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->status_pages_limit ? null : 'text-muted' ?>">
            <?php if($data->plan_settings->status_pages_limit == -1): ?>
                <?= language()->global->plan_settings->unlimited_status_pages_limit ?>
            <?php else: ?>
                <?= sprintf(language()->global->plan_settings->status_pages_limit, '<strong>' . nr($data->plan_settings->status_pages_limit) . '</strong>') ?>
            <?php endif ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->projects_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->projects_limit ? null : 'text-muted' ?>">
            <?php if($data->plan_settings->projects_limit == -1): ?>
                <?= language()->global->plan_settings->unlimited_projects_limit ?>
            <?php else: ?>
                <?= sprintf(language()->global->plan_settings->projects_limit, '<strong>' . nr($data->plan_settings->projects_limit) . '</strong>') ?>
            <?php endif ?>
        </div>
    </li>

    <?php if(settings()->status_pages->domains_is_enabled): ?>
    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->domains_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->domains_limit ? null : 'text-muted' ?>">
            <?php if($data->plan_settings->domains_limit == -1): ?>
                <?= language()->global->plan_settings->unlimited_domains_limit ?>
            <?php else: ?>
                <?= sprintf(language()->global->plan_settings->domains_limit, '<strong>' . nr($data->plan_settings->domains_limit) . '</strong>') ?>
            <?php endif ?>
        </div>
    </li>
    <?php endif ?>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->logs_retention ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->logs_retention ? null : 'text-muted' ?>">
            <?= sprintf(language()->global->plan_settings->logs_retention, '<strong>' . nr($data->plan_settings->logs_retention) . '</strong>') ?>
        </div>
    </li>

    <?php if(settings()->status_pages->additional_domains_is_enabled): ?>
    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->additional_domains_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->additional_domains_is_enabled ? null : 'text-muted' ?>">
            <?= language()->global->plan_settings->additional_domains_is_enabled ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= language()->global->plan_settings->additional_domains_is_enabled_help ?>"><i class="fa fa-fw fa-xs fa-question-circle text-gray-500"></i></span>
        </div>
    </li>
    <?php endif ?>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->analytics_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->analytics_is_enabled ? null : 'text-muted' ?>">
            <?= language()->global->plan_settings->analytics_is_enabled ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= language()->global->plan_settings->analytics_is_enabled_help ?>"><i class="fa fa-fw fa-xs fa-question-circle text-gray-500"></i></span>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->password_protection_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->password_protection_is_enabled ? null : 'text-muted' ?>">
            <?= language()->global->plan_settings->password_protection_is_enabled ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= language()->global->plan_settings->password_protection_is_enabled_help ?>"><i class="fa fa-fw fa-xs fa-question-circle text-gray-500"></i></span>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->removable_branding_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->removable_branding_is_enabled ? null : 'text-muted' ?>">
            <?= language()->global->plan_settings->removable_branding_is_enabled ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= language()->global->plan_settings->removable_branding_is_enabled_help ?>"><i class="fa fa-fw fa-xs fa-question-circle text-gray-500"></i></span>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->custom_url_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->custom_url_is_enabled ? null : 'text-muted' ?>">
            <?= language()->global->plan_settings->custom_url_is_enabled ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= language()->global->plan_settings->custom_url_is_enabled_help ?>"><i class="fa fa-fw fa-xs fa-question-circle text-gray-500"></i></span>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->search_engine_block_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->search_engine_block_is_enabled ? null : 'text-muted' ?>">
            <?= language()->global->plan_settings->search_engine_block_is_enabled ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= language()->global->plan_settings->search_engine_block_is_enabled_help ?>"><i class="fa fa-fw fa-xs fa-question-circle text-gray-500"></i></span>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->custom_css_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->custom_css_is_enabled ? null : 'text-muted' ?>">
            <?= language()->global->plan_settings->custom_css_is_enabled ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= language()->global->plan_settings->custom_css_is_enabled_help ?>"><i class="fa fa-fw fa-xs fa-question-circle text-gray-500"></i></span>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->custom_js_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->custom_js_is_enabled ? null : 'text-muted' ?>">
            <?= language()->global->plan_settings->custom_js_is_enabled ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= language()->global->plan_settings->custom_js_is_enabled_help ?>"><i class="fa fa-fw fa-xs fa-question-circle text-gray-500"></i></span>
        </div>
    </li>

    <?php if(settings()->monitors_heartbeats->email_reports_is_enabled): ?>
    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->email_reports_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->email_reports_is_enabled ? null : 'text-muted' ?>">
            <?= language()->global->plan_settings->email_reports_is_enabled ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= language()->global->plan_settings->email_reports_is_enabled_help ?>"><i class="fa fa-fw fa-xs fa-question-circle text-gray-500"></i></span>
        </div>
    </li>
    <?php endif ?>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->email_notifications_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->email_notifications_is_enabled ? null : 'text-muted' ?>">
            <?= language()->global->plan_settings->email_notifications_is_enabled ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= language()->global->plan_settings->email_notifications_is_enabled_help ?>"><i class="fa fa-fw fa-xs fa-question-circle text-gray-500"></i></span>
        </div>
    </li>

    <?php if(settings()->monitors_heartbeats->twilio_notifications_is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->twilio_notifications_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->twilio_notifications_is_enabled ? null : 'text-muted' ?>">
                <?= language()->global->plan_settings->twilio_notifications_is_enabled ?>
                <span class="mr-1" data-toggle="tooltip" title="<?= language()->global->plan_settings->twilio_notifications_is_enabled_help ?>"><i class="fa fa-fw fa-xs fa-question-circle text-gray-500"></i></span>
            </div>
        </li>
    <?php endif ?>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->api_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->api_is_enabled ? null : 'text-muted' ?>">
            <?= language()->global->plan_settings->api_is_enabled ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= language()->global->plan_settings->api_is_enabled_help ?>"><i class="fa fa-fw fa-xs fa-question-circle text-gray-500"></i></span>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->no_ads ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->no_ads ? null : 'text-muted' ?>">
            <?= language()->global->plan_settings->no_ads ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= language()->global->plan_settings->no_ads_help ?>"><i class="fa fa-fw fa-xs fa-question-circle text-gray-500"></i></span>
        </div>
    </li>
</ul>
