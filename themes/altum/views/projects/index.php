<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-xl d-flex align-items-center mb-3 mb-xl-0">
            <h1 class="h4 m-0"><?= language()->projects->header ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= language()->projects->subheader ?>">
                    <i class="fa fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

        <div class="col-12 col-xl-auto">
            <?php if($this->user->plan_settings->projects_limit != -1 && $data->total_projects >= $this->user->plan_settings->projects_limit): ?>
                <button type="button" class="btn btn-outline-primary disabled" data-toggle="tooltip" title="<?= language()->projects->error_message->projects_limit ?>">
                    <i class="fa fa-fw fa-sm fa-plus"></i> <?= language()->projects->create ?>
                </button>
            <?php else: ?>
                <a href="<?= url('project-create') ?>" class="btn btn-outline-primary"><i class="fa fa-fw fa-sm fa-plus"></i> <?= language()->projects->create ?></a>
            <?php endif ?>
        </div>
    </div>

    <?php if(count($data->projects)): ?>
        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th><?= language()->projects->table->name ?></th>
                        <th><?= language()->projects->table->color ?></th>
                        <th></th>
                        <th><?= language()->projects->table->datetime ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach($data->projects as $row): ?>

                    <tr>
                        <td>
                            <a href="<?= url('project-update/' . $row->project_id) ?>"><?= $row->name ?></a>
                        </td>

                        <td>
                            <span class="py-1 px-2 border rounded text-muted small" style="border-color: <?= $row->color ?> !important;">
                                <?= $row->color ?>
                            </span>
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <a href="<?= url('monitors?project_id=' . $row->project_id) ?>" class="mr-2" data-toggle="tooltip" title="<?= language()->projects->table->monitors ?>">
                                    <i class="fa fa-fw fa-server text-muted"></i>
                                </a>

                                <a href="<?= url('heartbeats?project_id=' . $row->project_id) ?>" class="mr-2" data-toggle="tooltip" title="<?= language()->projects->table->heartbeats ?>">
                                    <i class="fa fa-fw fa-heartbeat text-muted"></i>
                                </a>

                                <a href="<?= url('status-pages?project_id=' . $row->project_id) ?>" class="mr-2" data-toggle="tooltip" title="<?= language()->projects->table->status_pages ?>">
                                    <i class="fa fa-fw fa-wifi text-muted"></i>
                                </a>
                            </div>
                        </td>

                        <td class="text-muted">
                            <span data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>"><?= \Altum\Date::get($row->datetime, 2) ?></span>
                        </td>

                        <td>
                            <?= include_view(THEME_PATH . 'views/projects/project_dropdown_button.php', ['id' => $row->project_id]) ?>
                        </td>
                    </tr>
                <?php endforeach ?>

                </tbody>
            </table>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= ASSETS_FULL_URL . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-5 mb-3" alt="<?= language()->projects->no_data ?>" />
            <h2 class="h4 text-muted mt-3"><?= language()->projects->no_data ?></h2>
            <p class="text-muted"><?= language()->projects->no_data_help ?></p>
        </div>

    <?php endif ?>

</div>
