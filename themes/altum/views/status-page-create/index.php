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
                <li class="active" aria-current="page"><?= language()->status_page_create->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <h1 class="h4 text-truncate"><?= language()->status_page_create->header ?></h1>
    <p></p>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                <?php if(count($data->domains) && (settings()->status_pages->domains_is_enabled || settings()->status_pages->additional_domains_is_enabled)): ?>
                    <div class="form-group">
                        <label for="domain_id"><i class="fa fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= language()->status_page->input->domain_id ?></label>
                        <select id="domain_id" name="domain_id" class="form-control">
                            <?php if(settings()->status_pages->main_domain_is_enabled || \Altum\Middlewares\Authentication::is_admin()): ?>
                                <option value=""><?= url('s/') ?></option>
                            <?php endif ?>

                            <?php foreach($data->domains as $row): ?>
                                <option value="<?= $row->domain_id ?>" data-type="<?= $row->type ?>" <?= $data->values['domain_id'] && $data->values['domain_id'] == $row->domain_id ? 'selected="selected"' : null ?>><?= $row->url ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= language()->status_page->input->domain_id_help ?></small>
                    </div>

                    <div id="is_main_status_page_wrapper" class="custom-control custom-switch my-3">
                        <input id="is_main_status_page" name="is_main_status_page" type="checkbox" class="custom-control-input" <?= $data->values['is_main_status_page'] ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="is_main_status_page"><?= language()->status_page->input->is_main_status_page ?></label>
                        <small class="form-text text-muted"><?= language()->status_page->input->is_main_status_page_help ?></small>
                    </div>

                    <div <?= $this->user->plan_settings->custom_url_is_enabled ? null : 'data-toggle="tooltip" title="' . language()->global->info_message->plan_feature_no_access . '"' ?>>
                        <div class="<?= $this->user->plan_settings->custom_url_is_enabled ? null : 'container-disabled' ?>">
                            <div class="form-group">
                                <label for="url"><i class="fa fa-fw fa-sm fa-link text-muted mr-1"></i> <?= language()->status_page->input->url ?></label>
                                <input type="text" id="url" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" value="<?= $data->values['url'] ?>" placeholder="<?= language()->status_page->input->url_placeholder ?>" />
                                <?= \Altum\Alerts::output_field_error('url') ?>
                                <small class="form-text text-muted"><?= language()->status_page->input->url_help ?></small>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div <?= $this->user->plan_settings->custom_url_is_enabled ? null : 'data-toggle="tooltip" title="' . language()->global->info_message->plan_feature_no_access . '"' ?>>
                        <div class="<?= $this->user->plan_settings->custom_url_is_enabled ? null : 'container-disabled' ?>">
                            <label for="url"><i class="fa fa-fw fa-sm fa-link text-muted mr-1"></i> <?= language()->status_page->input->url ?></label>
                            <div class="mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?= url('s/') ?></span>
                                    </div>
                                    <input type="text" id="url" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" <?= $data->values['url'] ?> placeholder="<?= language()->status_page->input->url_placeholder ?>" />
                                    <?= \Altum\Alerts::output_field_error('url') ?>
                                </div>
                                <small class="form-text text-muted"><?= language()->status_page->input->url_help ?></small>
                            </div>
                        </div>
                    </div>
                <?php endif ?>

                <div class="form-group">
                    <label for="name"><i class="fa fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= language()->status_page->input->name ?></label>
                    <input type="text" id="name" name="name" class="form-control <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->values['name'] ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('name') ?>
                </div>

                <div class="form-group">
                    <label for="description"><i class="fa fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= language()->status_page->input->description ?></label>
                    <input type="text" id="description" name="description" class="form-control" value="<?= $data->values['description'] ?>" />
                    <small class="form-text text-muted"><?= language()->status_page->input->description_help ?></small>
                </div>

                <div class="mb-3">
                    <div><i class="fa fa-fw fa-sm fa-server text-muted mr-1"></i> <?= language()->status_page->input->monitors_ids ?></div>
                    <div><small class="form-text text-muted"><?= language()->status_page->input->monitors_ids_help ?></small></div>

                    <div class="row">
                        <?php foreach($data->monitors as $monitor): ?>
                            <div class="col-12 col-lg-6">
                                <div class="custom-control custom-checkbox my-2">
                                    <input id="monitor_id_<?= $monitor->monitor_id ?>" name="monitors_ids[]" value="<?= $monitor->monitor_id ?>" type="checkbox" class="custom-control-input" <?= in_array($monitor->monitor_id, $data->values['monitors_ids']) ? 'checked="checked"' : null ?>>
                                    <label class="custom-control-label" for="monitor_id_<?= $monitor->monitor_id ?>">
                                        <span><?= $monitor->name ?></span>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>

                <p><small class="form-text text-muted"><i class="fa fa-fw fa-sm fa-info-circle"></i> <?= language()->status_page_create->info ?></small></p>

                <button type="submit" name="submit" class="btn btn-block btn-primary mt-4"><?= language()->global->create ?></button>
            </form>

        </div>
    </div>
</div>


<?php ob_start() ?>
<script>
    'use strict';

    /* Is main status_page handler */
    let is_main_status_page_handler = () => {
        if(document.querySelector('#is_main_status_page').checked) {
            document.querySelector('#url').setAttribute('disabled', 'disabled');
        } else {
            document.querySelector('#url').removeAttribute('disabled');
        }
    }

    document.querySelector('#is_main_status_page') && document.querySelector('#is_main_status_page').addEventListener('change', is_main_status_page_handler);

    /* Domain Id Handler */
    let domain_id_handler = () => {
        let domain_id = document.querySelector('select[name="domain_id"]').value;

        if(document.querySelector(`select[name="domain_id"] option[value="${domain_id}"]`).getAttribute('data-type') == '0') {
            document.querySelector('#is_main_status_page_wrapper').classList.remove('d-none');
        } else {
            document.querySelector('#is_main_status_page_wrapper').classList.add('d-none');
            document.querySelector('#is_main_status_page').checked = false;
        }

        is_main_status_page_handler();
    }

    domain_id_handler();

    document.querySelector('select[name="domain_id"]') && document.querySelector('select[name="domain_id"]').addEventListener('change', domain_id_handler);
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
