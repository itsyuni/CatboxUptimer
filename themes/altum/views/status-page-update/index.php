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
                <li>
                    <?= language()->status_page->breadcrumb ?><i class="fa fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page"><?= language()->status_page_update->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <div class="d-flex align-items-center mb-2">
        <h1 class="h4 text-truncate mb-0 mr-2"><?= sprintf(language()->status_page_update->header, $data->status_page->name) ?></h1>

        <?= include_view(THEME_PATH . 'views/status-page/status_page_dropdown_button.php', ['id' => $data->status_page->status_page_id]) ?>
    </div>

    <p>
        <a href="<?= $data->status_page->full_url ?>" target="_blank">
            <img src="https://external-content.duckduckgo.com/ip3/<?= parse_url($data->status_page->full_url)['host'] ?>.ico" class="img-fluid icon-favicon mr-1" />

            <?= $data->status_page->full_url ?>
        </a>

        <button
                id="url_copy"
                type="button"
                class="btn btn-link"
                data-toggle="tooltip"
                title="<?= language()->global->clipboard_copy ?>"
                aria-label="<?= language()->global->clipboard_copy ?>"
                data-copy="<?= language()->global->clipboard_copy ?>"
                data-copied="<?= language()->global->clipboard_copied ?>"
                data-clipboard-text="<?= $data->status_page->full_url ?>"
        >
            <i class="fa fa-fw fa-sm fa-copy"></i>
        </button>
    </p>


    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form" enctype="multipart/form-data">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                <?php if(count($data->domains) && (settings()->status_pages->domains_is_enabled || settings()->status_pages->additional_domains_is_enabled)): ?>
                    <div class="form-group">
                        <label for="domain_id"><i class="fa fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= language()->status_page->input->domain_id ?></label>
                        <select id="domain_id" name="domain_id" class="form-control">
                            <?php if(settings()->status_pages->main_domain_is_enabled || \Altum\Middlewares\Authentication::is_admin()): ?>
                                <option value="" <?= $data->status_page->domain_id ? null : 'selected="selected"' ?>><?= url('s/') ?></option>
                            <?php endif ?>

                            <?php foreach($data->domains as $row): ?>
                                <option value="<?= $row->domain_id ?>" data-type="<?= $row->type ?>" <?= $data->status_page->domain_id && $data->status_page->domain_id == $row->domain_id ? 'selected="selected"' : null ?>><?= $row->url ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= language()->status_page->input->domain_id_help ?></small>
                    </div>

                    <div id="is_main_status_page_wrapper" class="custom-control custom-switch my-3">
                        <input id="is_main_status_page" name="is_main_status_page" type="checkbox" class="custom-control-input" <?= $data->status_page->domain_id && $data->domains[$data->status_page->domain_id]->status_page_id == $data->status_page->status_page_id ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="is_main_status_page"><?= language()->status_page->input->is_main_status_page ?></label>
                        <small class="form-text text-muted"><?= language()->status_page->input->is_main_status_page_help ?></small>
                    </div>

                    <div <?= $this->user->plan_settings->custom_url_is_enabled ? null : 'data-toggle="tooltip" title="' . language()->global->info_message->plan_feature_no_access . '"' ?>>
                        <div class="<?= $this->user->plan_settings->custom_url_is_enabled ? null : 'container-disabled' ?>">
                            <div class="form-group">
                                <label for="url"><i class="fa fa-fw fa-sm fa-link text-muted mr-1"></i> <?= language()->status_page->input->url ?></label>
                                <input type="text" id="url" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" value="<?= $data->status_page->url ?>" placeholder="<?= language()->status_page->input->url_placeholder ?>" />
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
                                    <input type="text" id="url" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" value="<?= $data->status_page->url ?>" placeholder="<?= language()->status_page->input->url_placeholder ?>" />
                                    <?= \Altum\Alerts::output_field_error('url') ?>
                                </div>
                                <small class="form-text text-muted"><?= language()->status_page->input->url_help ?></small>
                            </div>
                        </div>
                    </div>
                <?php endif ?>

                <div class="form-group">
                    <label for="name"><i class="fa fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= language()->status_page->input->name ?></label>
                    <input type="text" id="name" name="name" class="form-control <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->status_page->name ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('name') ?>
                </div>

                <div class="form-group">
                    <label for="description"><i class="fa fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= language()->status_page->input->description ?></label>
                    <input type="text" id="description" name="description" class="form-control" value="<?= $data->status_page->description ?>" />
                    <small class="form-text text-muted"><?= language()->status_page->input->description_help ?></small>
                </div>

                <div class="mb-3">
                    <div><i class="fa fa-fw fa-sm fa-server text-muted mr-1"></i> <?= language()->status_page->input->monitors_ids ?></div>
                    <div><small class="form-text text-muted"><?= language()->status_page->input->monitors_ids_help ?></small></div>

                    <div class="row">
                        <?php foreach($data->monitors as $monitor): ?>
                            <div class="col-12 col-lg-6">
                                <div class="custom-control custom-checkbox my-2">
                                    <input id="monitor_id_<?= $monitor->monitor_id ?>" name="monitors_ids[]" value="<?= $monitor->monitor_id ?>" type="checkbox" class="custom-control-input" <?= in_array($monitor->monitor_id, $data->status_page->monitors_ids) ? 'checked="checked"' : null ?>>
                                    <label class="custom-control-label" for="monitor_id_<?= $monitor->monitor_id ?>">
                                        <span><?= $monitor->name ?></span>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>

                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item mr-1" role="presentation">
                        <a class="btn btn-sm btn-outline-blue-500" id="advanced-tab" data-toggle="tab" href="#advanced" role="tab" aria-controls="advanced" aria-selected="true"><?= language()->status_page->input->advanced ?></a>
                    </li>
                    <li class="nav-item mr-1" role="presentation">
                        <a class="btn btn-sm btn-outline-blue-500" id="socials-tab" data-toggle="tab" href="#socials" role="tab" aria-controls="socials" aria-selected="true"><?= language()->status_page->input->socials ?></a>
                    </li>
                    <li class="nav-item mr-1" role="presentation">
                        <a class="btn btn-sm <?= \Altum\Alerts::has_field_errors(['logo', 'favicon']) ? 'btn-outline-danger' : 'btn-outline-blue-500' ?>" id="customizations-tab" data-toggle="tab" href="#customizations" role="tab" aria-controls="customizations" aria-selected="true"><?= language()->status_page->input->customizations ?></a>
                    </li>
                </ul>

                <div class="tab-content my-3">
                    <div class="tab-pane fade" id="advanced" role="tabpanel" aria-labelledby="advanced-tab">
                        <div class="form-group">
                            <label for="project_id"><i class="fa fa-fw fa-sm fa-project-diagram text-muted mr-1"></i> <?= language()->projects->project_id ?></label>
                            <select id="project_id" name="project_id" class="form-control">
                                <option value=""><?= language()->projects->project_id_null ?></option>
                                <?php foreach($data->projects as $project_id => $project): ?>
                                    <option value="<?= $project_id ?>" <?= $data->status_page->project_id == $project_id ? 'selected="selected"' : null ?>><?= $project->name ?></option>
                                <?php endforeach ?>
                            </select>
                            <small class="form-text text-muted"><?= language()->projects->project_id_help ?></small>
                        </div>

                        <div class="form-group">
                            <label for="timezone"><i class="fa fa-fw fa-sm fa-clock text-muted mr-1"></i> <?= language()->status_page->input->timezone ?></label>
                            <select id="timezone" name="timezone" class="form-control">
                                <?php foreach(DateTimeZone::listIdentifiers() as $timezone) echo '<option value="' . $timezone . '" ' . ($data->status_page->timezone == $timezone ? 'selected="selected"' : null) . '>' . $timezone . '</option>' ?>
                            </select>
                            <small class="form-text text-muted"><?= language()->status_page->input->timezone_help ?></small>
                        </div>

                        <div <?= $this->user->plan_settings->password_protection_is_enabled ? null : 'data-toggle="tooltip" title="' . language()->global->info_message->plan_feature_no_access . '"' ?>>
                            <div class="form-group <?= $this->user->plan_settings->password_protection_is_enabled ? null : 'container-disabled' ?>">
                                <label for="password"><i class="fa fa-fw fa-sm fa-lock text-muted mr-1"></i> <?= language()->status_page->input->password ?></label>
                                <input type="password" id="password" name="password" class="form-control" value="<?= $data->status_page->password ?>" autocomplete="new-password" />
                            </div>
                        </div>

                        <div <?= $this->user->plan_settings->search_engine_block_is_enabled ? null : 'data-toggle="tooltip" title="' . language()->global->info_message->plan_feature_no_access . '"' ?>>
                            <div class="custom-control custom-switch my-3 <?= $this->user->plan_settings->search_engine_block_is_enabled ? null : 'container-disabled' ?>">
                                <input id="is_se_visible" name="is_se_visible" type="checkbox" class="custom-control-input" <?= $data->status_page->is_se_visible ? 'checked="checked"' : null?> <?= $this->user->plan_settings->search_engine_block_is_enabled ? null : 'disabled="disabled"' ?>>
                                <label class="custom-control-label" for="is_se_visible"><?= language()->status_page->input->is_se_visible ?></label>
                                <small class="form-text text-muted"><?= language()->status_page->input->is_se_visible_help ?></small>
                            </div>
                        </div>

                        <div <?= $this->user->plan_settings->removable_branding_is_enabled ? null : 'data-toggle="tooltip" title="' . language()->global->info_message->plan_feature_no_access . '"' ?>>
                            <div class="custom-control custom-switch my-3 <?= $this->user->plan_settings->removable_branding_is_enabled ? null : 'container-disabled' ?>">
                                <input id="is_removed_branding" name="is_removed_branding" type="checkbox" class="custom-control-input" <?= $data->status_page->is_removed_branding ? 'checked="checked"' : null?> <?= $this->user->plan_settings->removable_branding_is_enabled ? null : 'disabled="disabled"' ?>>
                                <label class="custom-control-label" for="is_removed_branding"><?= language()->status_page->input->is_removed_branding ?></label>
                                <small class="form-text text-muted"><?= language()->status_page->input->is_removed_branding_help ?></small>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="socials" role="tabpanel" aria-labelledby="socials-tab">
                        <?php foreach(require APP_PATH . 'includes/s/socials.php' as $key => $value): ?>

                            <div class="form-group">
                                <label for="socials_<?= $key ?>"><i class="<?= $value['icon'] ?> fa-fw fa-sm mr-1"></i> <?= language()->status_page->input->{$key} ?></label>
                                <div class="input-group mb-3">
                                    <?php if($value['input_display_format']): ?>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><?= str_replace('%s', '', $value['format']) ?></span>
                                        </div>
                                    <?php endif ?>
                                    <input id="socials_<?= $key ?>" type="text" class="form-control" name="socials[<?= $key ?>]" placeholder="<?= language()->status_page->input->{$key . '_placeholder'} ?>" value="<?= $data->status_page->socials->{$key} ?? '' ?>" />
                                </div>
                            </div>

                        <?php endforeach ?>
                    </div>

                    <div class="tab-pane fade" id="customizations" role="tabpanel" aria-labelledby="customizations-tab">
                        <div class="form-group">
                            <label for="logo"><i class="fa fa-fw fa-sm fa-image text-muted mr-1"></i> <?= language()->status_page->input->logo ?></label>
                            <?php if(!empty($data->status_page->logo)): ?>
                            <div class="row">
                                <div class="m-1 col-6 col-xl-3">
                                    <img src="<?= UPLOADS_FULL_URL . 'status_pages_logos/' . $data->status_page->logo ?>" class="img-fluid" loading="lazy" />
                                </div>
                            </div>
                            <div class="custom-control custom-checkbox my-2">
                                <input id="logo_remove" name="logo_remove" type="checkbox" class="custom-control-input" onchange="this.checked ? document.querySelector('#logo').classList.add('d-none') : document.querySelector('#logo').classList.remove('d-none')">
                                <label class="custom-control-label" for="logo_remove">
                                    <span class="text-muted"><?= language()->global->delete_file ?></span>
                                </label>
                            </div>
                            <?php endif ?>
                            <input id="logo" type="file" name="logo" accept=".gif, .png, .jpg, .jpeg, .svg" class="form-control-file <?= \Altum\Alerts::has_field_errors('logo') ? 'is-invalid' : null ?>" />
                            <?= \Altum\Alerts::output_field_error('logo') ?>
                            <small class="form-text text-muted"><?= language()->status_page->input->logo_help ?></small>
                        </div>

                        <div class="form-group">
                            <label for="favicon"><i class="fa fa-fw fa-sm fa-clone text-muted mr-1"></i> <?= language()->status_page->input->favicon ?></label>
                            <?php if(!empty($data->status_page->favicon)): ?>
                            <div class="row">
                                <div class="m-1 col-6 col-xl-3">
                                    <img src="<?= UPLOADS_FULL_URL . 'status_pages_favicons/' . $data->status_page->favicon ?>" class="img-fluid" loading="lazy" />
                                </div>
                            </div>
                            <div class="custom-control custom-checkbox my-2">
                                <input id="favicon_remove" name="favicon_remove" type="checkbox" class="custom-control-input" onchange="this.checked ? document.querySelector('#favicon').classList.add('d-none') : document.querySelector('#favicon').classList.remove('d-none')">
                                <label class="custom-control-label" for="favicon_remove">
                                    <span class="text-muted"><?= language()->global->delete_file ?></span>
                                </label>
                            </div>
                            <?php endif ?>
                            <input id="favicon" type="file" name="favicon" accept=".gif, .png, .jpg, .jpeg, .ico" class="form-control-file <?= \Altum\Alerts::has_field_errors('favicon') ? 'is-invalid' : null ?>" />
                            <?= \Altum\Alerts::output_field_error('favicon') ?>
                            <small class="form-text text-muted"><?= language()->status_page->input->favicon_help ?></small>
                        </div>

                        <div <?= $this->user->plan_settings->custom_css_is_enabled ? null : 'data-toggle="tooltip" title="' . language()->global->info_message->plan_feature_no_access . '"' ?>>
                            <div class="form-group <?= $this->user->plan_settings->custom_css_is_enabled ? null : 'container-disabled' ?>">
                                <label for="custom_css"><i class="fa fa-fw fa-sm fa-code text-muted mr-1"></i> <?= language()->status_page->input->custom_css ?></label>
                                <textarea id="custom_css" class="form-control" name="custom_css"><?= $data->status_page->custom_css ?></textarea>
                            </div>
                        </div>

                        <div <?= $this->user->plan_settings->custom_js_is_enabled ? null : 'data-toggle="tooltip" title="' . language()->global->info_message->plan_feature_no_access . '"' ?>>
                            <div class="form-group <?= $this->user->plan_settings->custom_js_is_enabled ? null : 'container-disabled' ?>">
                                <label for="custom_js"><i class="fa fa-fw fa-sm fa-code text-muted mr-1"></i> <?= language()->status_page->input->custom_js ?></label>
                                <textarea id="custom_js" class="form-control" name="custom_js"><?= $data->status_page->custom_js ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>


                <button type="submit" name="submit" class="btn btn-block btn-primary mt-4"><?= language()->global->update ?></button>
            </form>

        </div>
    </div>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

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
