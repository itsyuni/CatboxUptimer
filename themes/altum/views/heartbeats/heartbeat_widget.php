<?php defined('ALTUMCODE') || die() ?>

<?php
/* Determine the border color based on the status */
$status_class_name = 'warning';

if($data->heartbeat->is_enabled) {
    $status_class_name = $data->heartbeat->is_ok ? 'primary' : 'danger';
}
?>
<div class="col-12 col-md-6 col-xl-4 mb-4">
    <div class="card h-100" <?= $data->heartbeat->project_id ? 'style="border-color: ' . $data->projects[$data->heartbeat->project_id]->color . ';"' : null ?>>
        <div class="card-body d-flex flex-column justify-content-between">
            <div class="d-flex justify-content-between">
                <h2 class="h5 m-0 card-title">
                    <a href="<?= url('heartbeat/' . $data->heartbeat->heartbeat_id) ?>"><?= $data->heartbeat->name ?></a>
                </h2>

                <?= include_view(THEME_PATH . 'views/heartbeat/heartbeat_dropdown_button.php', ['id' => $data->heartbeat->heartbeat_id]) ?>
            </div>

            <div class="mb-3">
                <small class="text-muted">
                    <?php if($data->heartbeat->is_enabled): ?>
                        <?php if($data->heartbeat->is_ok): ?>
                            <span class="mr-1" data-toggle="tooltip" title="<?= language()->heartbeat->is_ok ?>">
                                <i class="fa fa-fw fa-sm fa-check-circle text-success"></i>
                            </span>
                        <?php else: ?>
                            <span class="mr-1" data-toggle="tooltip" title="<?= language()->heartbeat->is_not_ok ?>">
                                <i class="fa fa-fw fa-sm fa-times-circle text-danger"></i>
                            </span>
                        <?php endif ?>
                    <?php else: ?>
                        <span class="mr-1" data-toggle="tooltip" title="<?= language()->heartbeat->is_enabled_paused ?>">
                            <i class="fa fa-fw fa-sm fa-pause-circle text-warning"></i>
                        </span>
                    <?php endif ?>

                    <span data-toggle="tooltip" title="<?= $data->heartbeat->last_run_datetime ? \Altum\Date::get($data->heartbeat->last_run_datetime) : '' ?>"><?= sprintf(language()->heartbeats->last_run_datetime, $data->heartbeat->last_run_datetime ? \Altum\Date::get_timeago($data->heartbeat->last_run_datetime) : '-') ?></span>
                </small>
            </div>

            <div class="d-flex flex-column flex-xl-row justify-content-xl-between">
                <div class="d-flex flex-column mb-2 mb-xl-0">
                    <small class="text-muted"><?= language()->heartbeat->uptime ?></small>
                    <span class="font-weight-bold" data-toggle="tooltip" title="<?= sprintf(language()->heartbeat->total_runs_tooltip, nr($data->heartbeat->total_runs)) ?>">
                        <?= nr($data->heartbeat->uptime, 3) . '%' ?>
                    </span>
                </div>

                <div class="d-flex flex-column mb-2 mb-xl-0">
                    <small class="text-muted"><?= language()->heartbeat->downtime ?></small>
                    <span class="font-weight-bold" data-toggle="tooltip" title="<?= sprintf(language()->heartbeat->total_missed_runs_tooltip, nr($data->heartbeat->total_missed_runs)) ?>">
                        <?= nr($data->heartbeat->downtime, 3) . '%' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
