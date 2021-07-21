<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-project-diagram text-primary-900 mr-2"></i> <?= language()->admin_projects->header ?></h1>

    <div class="col-auto d-flex">
        <div class="ml-3">
            <div class="dropdown">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle-simple" data-toggle="dropdown" title="<?= language()->global->export ?>">
                    <i class="fa fa-fw fa-sm fa-download"></i>
                </button>

                <div class="dropdown-menu  dropdown-menu-right">
                    <a href="<?= url('admin/projects?' . $data->filters->get_get() . '&export=csv') ?>" target="_blank" class="dropdown-item">
                        <i class="fa fa-fw fa-sm fa-file-csv mr-1"></i> <?= language()->global->export_csv ?>
                    </a>
                    <a href="<?= url('admin/projects?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
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
                            <a href="<?= url('admin/projects') ?>" class="text-muted"><?= language()->global->filters->reset ?></a>
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
                                <option value="name" <?= $data->filters->search_by == 'name' ? 'selected="selected"' : null ?>><?= language()->admin_projects->filters->search_by_name ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="order_by" class="small"><?= language()->global->filters->order_by ?></label>
                            <select name="order_by" id="order_by" class="form-control form-control-sm">
                                <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= language()->global->filters->order_by_datetime ?></option>
                                <option value="name" <?= $data->filters->order_by == 'name' ? 'selected="selected"' : null ?>><?= language()->admin_projects->filters->order_by_name ?></option>
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

<?= \Altum\Alerts::output_alerts() ?>

<div class="table-responsive table-custom-container">
    <table class="table table-custom">
        <thead>
        <tr>
            <th><?= language()->admin_projects->table->user ?></th>
            <th><?= language()->admin_projects->table->name ?></th>
            <th><?= language()->admin_projects->table->color ?></th>
            <th></th>
            <th><?= language()->admin_projects->table->datetime ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($data->projects as $row): ?>
            <tr>
                <td>
                    <div class="d-flex flex-column">
                        <div>
                            <a href="<?= url('admin/user-view/' . $row->user_id) ?>"><?= $row->user_name ?></a>
                        </div>

                        <span class="text-muted"><?= $row->user_email ?></span>
                    </div>
                </td>
                <td>
                    <?= $row->name ?>
                </td>
                <td>
                    <span class="py-1 px-2 border rounded text-muted small" style="border-color: <?= $row->color ?> !important;">
                        <?= $row->color ?>
                    </span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <a href="<?= url('admin/monitors?project_id=' . $row->project_id) ?>" class="mr-2" data-toggle="tooltip" title="<?= language()->admin_projects->table->monitors ?>">
                            <i class="fa fa-fw fa-server text-muted"></i>
                        </a>

                        <a href="<?= url('admin/heartbeats?project_id=' . $row->project_id) ?>" class="mr-2" data-toggle="tooltip" title="<?= language()->admin_projects->table->heartbeats ?>">
                            <i class="fa fa-fw fa-heartbeat text-muted"></i>
                        </a>

                        <a href="<?= url('admin/status-pages?project_id=' . $row->project_id) ?>" class="mr-2" data-toggle="tooltip" title="<?= language()->admin_projects->table->status_pages ?>">
                            <i class="fa fa-fw fa-wifi text-muted"></i>
                        </a>
                    </div>
                </td>
                <td>
                    <span class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime) ?>">
                        <?= \Altum\Date::get($row->datetime, 2) ?>
                    </span>
                </td>
                <td>
                    <?= include_view(THEME_PATH . 'views/admin/projects/admin_project_dropdown_button.php', ['id' => $row->project_id]) ?>
                </td>
            </tr>
        <?php endforeach ?>

        </tbody>
    </table>
</div>

<div class="mt-3"><?= $data->pagination ?></div>
