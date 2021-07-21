<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mr-3"><i class="fa fa-fw fa-xs fa-box-open text-primary-900 mr-2"></i> <?= language()->admin_plan_create->header ?></h1>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

            <div class="row">
                <div class="col-12 col-md-4">
                    <h2 class="h4"><?= language()->admin_plans->main->header ?></h2>
                    <p class="text-muted"><?= language()->admin_plans->main->subheader ?></p>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="name"><?= language()->admin_plans->main->name ?></label>
                        <input type="text" id="name" name="name" class="form-control form-control-lg" />
                    </div>

                    <div class="form-group">
                        <label for="status"><?= language()->admin_plans->main->status ?></label>
                        <select id="status" name="status" class="form-control form-control-lg">
                            <option value="1"><?= language()->global->active ?></option>
                            <option value="0"><?= language()->global->disabled ?></option>
                            <option value="2"><?= language()->global->hidden ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="order"><?= language()->admin_plans->main->order ?></label>
                        <input type="number" min="0" id="order" name="order" class="form-control form-control-lg" value="" />
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-xl-4">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="monthly_price"><?= language()->admin_plans->main->monthly_price ?> <small class="form-text text-muted"><?= settings()->payment->currency ?></small></label>
                                    <input type="text" id="monthly_price" name="monthly_price" class="form-control form-control-lg" />
                                    <small class="form-text text-muted"><?= sprintf(language()->admin_plans->main->price_help, language()->admin_plans->main->monthly_price) ?></small>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-xl-4">
                            <div class="form-group">
                                <label for="annual_price"><?= language()->admin_plans->main->annual_price ?> <small class="form-text text-muted"><?= settings()->payment->currency ?></small></label>
                                <input type="text" id="annual_price" name="annual_price" class="form-control form-control-lg" />
                                <small class="form-text text-muted"><?= sprintf(language()->admin_plans->main->price_help, language()->admin_plans->main->annual_price) ?></small>
                            </div>
                        </div>

                        <div class="col-sm-12 col-xl-4">
                            <div class="form-group">
                                <label for="lifetime_price"><?= language()->admin_plans->main->lifetime_price ?> <small class="form-text text-muted"><?= settings()->payment->currency ?></small></label>
                                <input type="text" id="lifetime_price" name="lifetime_price" class="form-control form-control-lg" />
                                <small class="form-text text-muted"><?= sprintf(language()->admin_plans->main->price_help, language()->admin_plans->main->lifetime_price) ?></small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <span><?= language()->admin_plans->main->taxes_ids ?></span>
                        <div><small class="form-text text-muted"><?= sprintf(language()->admin_plans->main->taxes_ids_help, '<a href="' . url('admin/taxes') .'">', '</a>') ?></small></div>
                    </div>

                    <?php if($data->taxes): ?>
                        <div class="row">
                            <?php foreach($data->taxes as $row): ?>
                                <div class="col-12 col-xl-6">
                                    <div class="custom-control custom-switch my-3">
                                        <input id="<?= 'tax_id_' . $row->tax_id ?>" name="taxes_ids[<?= $row->tax_id ?>]" type="checkbox" class="custom-control-input">
                                        <label class="custom-control-label" for="<?= 'tax_id_' . $row->tax_id ?>"><?= $row->internal_name ?></label>
                                        <div><span><small><?= $row->name ?></small> - <small class="text-muted"><?= $row->description ?></small></span></div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>

                </div>
            </div>

            <div class="mt-5"></div>

            <div class="row">
                <div class="col-12 col-md-4">
                    <h2 class="h4"><?= language()->admin_plans->plan->header ?></h2>
                    <p class="text-muted"><?= language()->admin_plans->plan->subheader ?></p>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="monitors_limit"><?= language()->admin_plans->plan->monitors_limit ?></label>
                        <input type="number" id="monitors_limit" name="monitors_limit" min="-1" class="form-control form-control-lg" value="0" required="required" />
                        <small class="form-text text-muted"><?= language()->admin_plans->plan->monitors_limit_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="heartbeats_limit"><?= language()->admin_plans->plan->heartbeats_limit ?></label>
                        <input type="number" id="heartbeats_limit" name="heartbeats_limit" min="-1" class="form-control form-control-lg" value="0" required="required" />
                        <small class="form-text text-muted"><?= language()->admin_plans->plan->heartbeats_limit_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="status_pages_limit"><?= language()->admin_plans->plan->status_pages_limit ?></label>
                        <input type="number" id="status_pages_limit" name="status_pages_limit" min="-1" class="form-control form-control-lg" value="0" required="required" />
                        <small class="form-text text-muted"><?= language()->admin_plans->plan->status_pages_limit_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="projects_limit"><?= language()->admin_plans->plan->projects_limit ?></label>
                        <input type="number" id="projects_limit" name="projects_limit" min="-1" class="form-control form-control-lg" value="0" required="required" />
                        <small class="form-text text-muted"><?= language()->admin_plans->plan->projects_limit_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="domains_limit"><?= language()->admin_plans->plan->domains_limit ?></label>
                        <input type="number" id="domains_limit" name="domains_limit" min="-1" class="form-control form-control-lg" value="0" required="required" />
                        <small class="form-text text-muted"><?= language()->admin_plans->plan->domains_limit_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="logs_retention"><?= language()->admin_plans->plan->logs_retention ?></label>
                        <input type="number" id="logs_retention" name="logs_retention" min="-1" class="form-control form-control-lg" value="0" required="required" />
                        <small class="form-text text-muted"><?= language()->admin_plans->plan->logs_retention_help ?></small>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="additional_domains_is_enabled" name="additional_domains_is_enabled" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="additional_domains_is_enabled"><?= language()->admin_plans->plan->additional_domains_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= language()->admin_plans->plan->additional_domains_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="no_ads" name="no_ads" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="no_ads"><?= language()->admin_plans->plan->no_ads ?></label>
                        <div><small class="form-text text-muted"><?= language()->admin_plans->plan->no_ads_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="analytics_is_enabled" name="analytics_is_enabled" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="analytics_is_enabled"><?= language()->admin_plans->plan->analytics_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= language()->admin_plans->plan->analytics_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="removable_branding_is_enabled" name="removable_branding_is_enabled" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="removable_branding_is_enabled"><?= language()->admin_plans->plan->removable_branding_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= language()->admin_plans->plan->removable_branding_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="custom_url_is_enabled" name="custom_url_is_enabled" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="custom_url_is_enabled"><?= language()->admin_plans->plan->custom_url_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= language()->admin_plans->plan->custom_url_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="password_protection_is_enabled" name="password_protection_is_enabled" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="password_protection_is_enabled"><?= language()->admin_plans->plan->password_protection_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= language()->admin_plans->plan->password_protection_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="search_engine_block_is_enabled" name="search_engine_block_is_enabled" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="search_engine_block_is_enabled"><?= language()->admin_plans->plan->search_engine_block_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= language()->admin_plans->plan->search_engine_block_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="custom_css_is_enabled" name="custom_css_is_enabled" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="custom_css_is_enabled"><?= language()->admin_plans->plan->custom_css_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= language()->admin_plans->plan->custom_css_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="custom_js_is_enabled" name="custom_js_is_enabled" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="custom_js_is_enabled"><?= language()->admin_plans->plan->custom_js_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= language()->admin_plans->plan->custom_js_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="email_reports_is_enabled" name="email_reports_is_enabled" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="email_reports_is_enabled"><?= language()->admin_plans->plan->email_reports_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= language()->admin_plans->plan->email_reports_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="email_notifications_is_enabled" name="email_notifications_is_enabled" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="email_notifications_is_enabled"><?= language()->admin_plans->plan->email_notifications_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= language()->admin_plans->plan->email_notifications_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="twilio_notifications_is_enabled" name="twilio_notifications_is_enabled" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="twilio_notifications_is_enabled"><?= language()->admin_plans->plan->twilio_notifications_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= language()->admin_plans->plan->twilio_notifications_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="api_is_enabled" name="api_is_enabled" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="api_is_enabled"><?= language()->admin_plans->plan->api_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= language()->admin_plans->plan->api_is_enabled_help ?></small></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-4"></div>

                <div class="col">
                    <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= language()->global->create ?></button>
                </div>
            </div>
        </form>

    </div>
</div>
