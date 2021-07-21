<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-xl d-flex align-items-center mb-3 mb-xl-0">
            <h1 class="h4 m-0"><?= language()->monitors->header ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= language()->monitors->subheader ?>">
                    <i class="fa fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

        <div class="col-12 col-xl-auto d-flex">
            <div>
                <?php if($this->user->plan_settings->monitors_limit != -1 && $data->total_monitors >= $this->user->plan_settings->monitors_limit): ?>
                    <button type="button" class="btn btn-outline-primary disabled" data-toggle="tooltip" title="<?= language()->monitor->error_message->monitors_limit ?>">
                        <i class="fa fa-fw fa-sm fa-plus"></i> <?= language()->monitors->create ?>
                    </button>
                <?php else: ?>
                    <a href="<?= url('monitor-create') ?>" class="btn btn-outline-primary"><i class="fa fa-fw fa-sm fa-plus"></i> <?= language()->monitors->create ?></a>
                <?php endif ?>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle-simple" data-toggle="dropdown" title="<?= language()->global->export ?>">
                        <i class="fa fa-fw fa-sm fa-download"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="<?= url('monitors?' . $data->filters->get_get() . '&export=csv')  ?>" target="_blank" class="dropdown-item">
                            <i class="fa fa-fw fa-sm fa-file-csv mr-1"></i> <?= language()->global->export_csv ?>
                        </a>
                        <a href="<?= url('monitors?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
                            <i class="fa fa-fw fa-sm fa-file-code mr-1"></i> <?= language()->global->export_json ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn <?= count($data->filters->get) ? 'btn-outline-primary' : 'btn-outline-secondary' ?> filters-button dropdown-toggle-simple" data-toggle="dropdown"><i class="fa fa-fw fa-sm fa-filter"></i></button>

                    <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                        <div class="dropdown-header d-flex justify-content-between">
                            <span class="h6 m-0"><?= language()->global->filters->header ?></span>

                            <?php if(count($data->filters->get)): ?>
                                <a href="<?= url('monitors') ?>" class="text-muted"><?= language()->global->filters->reset ?></a>
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
                                    <option value="name" <?= $data->filters->search_by == 'name' ? 'selected="selected"' : null ?>><?= language()->monitors->filters->search_by_name ?></option>
                                    <option value="target" <?= $data->filters->search_by == 'target' ? 'selected="selected"' : null ?>><?= language()->monitors->filters->search_by_target ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="is_enabled" class="small"><?= language()->global->filters->status ?></label>
                                <select name="is_enabled" id="is_enabled" class="form-control form-control-sm">
                                    <option value=""><?= language()->global->filters->all ?></option>
                                    <option value="1" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '1' ? 'selected="selected"' : null ?>><?= language()->global->active ?></option>
                                    <option value="0" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '0' ? 'selected="selected"' : null ?>><?= language()->global->disabled ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="type" class="small"><?= language()->monitors->filters->type ?></label>
                                <select name="type" id="type" class="form-control form-control-sm">
                                    <option value=""><?= language()->global->filters->all ?></option>
                                    <option value="website" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'website' ? 'selected="selected"' : null ?>><?= language()->monitors->filters->type_website ?></option>
                                    <option value="ping" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'ping' ? 'selected="selected"' : null ?>><?= language()->monitors->filters->type_ping ?></option>
                                    <option value="port" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'port' ? 'selected="selected"' : null ?>><?= language()->monitors->filters->type_port ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="project_id" class="small"><?= language()->projects->project_id ?></label>
                                <select name="project_id" id="project_id" class="form-control form-control-sm">
                                    <option value=""><?= language()->global->filters->all ?></option>
                                    <?php foreach($data->projects as $project_id => $project): ?>
                                        <option value="<?= $project_id ?>" <?= isset($data->filters->filters['project_id']) && $data->filters->filters['project_id'] == $project_id ? 'selected="selected"' : null ?>><?= $project->name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="order_by" class="small"><?= language()->global->filters->order_by ?></label>
                                <select name="order_by" id="order_by" class="form-control form-control-sm">
                                    <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= language()->global->filters->order_by_datetime ?></option>
                                    <option value="last_check_datetime" <?= $data->filters->order_by == 'last_check_datetime' ? 'selected="selected"' : null ?>><?= language()->monitors->filters->order_by_last_check_datetime ?></option>
                                    <option value="name" <?= $data->filters->order_by == 'name' ? 'selected="selected"' : null ?>><?= language()->monitors->filters->order_by_name ?></option>
                                    <option value="uptime" <?= $data->filters->order_by == 'uptime' ? 'selected="selected"' : null ?>><?= language()->monitors->filters->order_by_uptime ?></option>
                                    <option value="average_response_time" <?= $data->filters->order_by == 'average_response_time' ? 'selected="selected"' : null ?>><?= language()->monitors->filters->order_by_average_response_time ?></option>
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

    <?php if(count($data->monitors)): ?>
        <div class="row">

            <?php foreach($data->monitors as $row): ?>
                <?= (new \Altum\Views\View('monitors/monitor_widget', (array) $this))->run(['monitor' => $row, 'projects' => $data->projects]) ?>
            <?php endforeach ?>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= ASSETS_FULL_URL . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-5 mb-3" alt="<?= language()->monitors->no_data ?>" />
            <h2 class="h4 text-muted mt-3"><?= language()->monitors->no_data ?></h2>
            <p class="text-muted"><?= language()->monitors->no_data_help ?></p>
        </div>

    <?php endif ?>
</div>

