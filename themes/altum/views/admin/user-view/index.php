<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <h1 class="h3 mr-3"><i class="fa fa-fw fa-xs fa-user text-primary-900 mr-2"></i> <?= language()->admin_user_view->header ?></h1>

        <?= include_view(THEME_PATH . 'views/admin/users/admin_user_dropdown_button.php', ['id' => $data->user->user_id]) ?>
    </div>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold"><?= language()->admin_user_view->main->type ?></label>
                    <input type="text" class="form-control-plaintext" value="<?= $data->user->type ? language()->admin_user_view->main->type_admin : language()->admin_user_view->main->type_user ?>" readonly />
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold"><?= language()->admin_user_view->main->email ?></label>
                    <input type="text" class="form-control-plaintext" value="<?= $data->user->email ?>" readonly />
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold"><?= language()->admin_user_view->main->name ?></label>
                    <input type="text" class="form-control-plaintext" value="<?= $data->user->name ?>" readonly />
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold"><?= language()->admin_user_view->main->status ?></label>
                    <input type="text" class="form-control-plaintext" value="<?= $data->user->active ? language()->admin_user_view->main->status_active : language()->admin_user_view->main->status_disabled ?>" readonly />
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold"><?= language()->admin_user_view->main->ip ?></label>
                    <input type="text" class="form-control-plaintext" value="<?= $data->user->ip ?>" readonly />
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold"><?= language()->admin_user_view->main->country ?></label>
                    <input type="text" class="form-control-plaintext" value="<?= $data->user->country ?>" readonly />
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold"><?= language()->admin_user_view->main->last_activity ?></label>
                    <input type="text" class="form-control-plaintext" value="<?= $data->user->last_activity ? \Altum\Date::get($data->user->last_activity) : '-' ?>" readonly />
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold"><?= language()->admin_user_view->main->last_user_agent ?></label>
                    <input type="text" class="form-control-plaintext" value="<?= $data->user->last_user_agent ?>" readonly />
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold"><?= language()->admin_user_view->main->plan ?></label>
                    <div>
                        <a href="<?= url('admin/plan-update/' . $data->user->plan->plan_id) ?>"><?= $data->user->plan->name ?></a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold"><?= language()->admin_user_view->main->plan_expiration_date ?></label>
                    <input type="text" class="form-control-plaintext" value="<?= \Altum\Date::get($data->user->plan_expiration_date) ?>" readonly />
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold"><?= language()->admin_user_view->main->plan_trial_done ?></label>
                    <input type="text" class="form-control-plaintext" value="<?= $data->user->plan_trial_done ? language()->global->yes : language()->global->no ?>" readonly />
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold"><?= language()->admin_user_view->main->total_logins ?></label>
                    <input type="text" class="form-control-plaintext" value="<?= $data->user->total_logins ?>" readonly />
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold"><?= language()->admin_user_view->main->language ?></label>
                    <input type="text" class="form-control-plaintext" value="<?= $data->user->language ?>" readonly />
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold"><?= language()->admin_user_view->main->timezone ?></label>
                    <input type="text" class="form-control-plaintext" value="<?= $data->user->timezone ?>" readonly />
                </div>
            </div>

        </div>
    </div>
</div>

<div class="my-5 row justify-content-between">
    <div class="col-12 col-sm-6 col-xl-3 mb-4 position-relative">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-server mr-1"></i> <?= language()->admin_user_view->monitors ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->monitors) ?></span></div>
            </div>

            <div class="bg-gray-200 px-2 d-flex flex-column justify-content-center">
                <a href="<?= url('admin/monitors?user_id=' . $data->user->user_id) ?>" class="stretched-link">
                    <i class="fa fa-fw fa-angle-right text-gray-500"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4 position-relative">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-heartbeat mr-1"></i> <?= language()->admin_user_view->heartbeats ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->heartbeats) ?></span></div>
            </div>

            <div class="bg-gray-200 px-2 d-flex flex-column justify-content-center">
                <a href="<?= url('admin/heartbeats?user_id=' . $data->user->user_id) ?>" class="stretched-link">
                    <i class="fa fa-fw fa-angle-right text-gray-500"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4 position-relative">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-wifi mr-1"></i> <?= language()->admin_user_view->status_pages ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->status_pages) ?></span></div>
            </div>

            <div class="bg-gray-200 px-2 d-flex flex-column justify-content-center">
                <a href="<?= url('admin/status-pages?user_id=' . $data->user->user_id) ?>" class="stretched-link">
                    <i class="fa fa-fw fa-angle-right text-gray-500"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4 position-relative">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-project-diagram mr-1"></i> <?= language()->admin_user_view->projects ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->projects) ?></span></div>
            </div>

            <div class="bg-gray-200 px-2 d-flex flex-column justify-content-center">
                <a href="<?= url('admin/projects?user_id=' . $data->user->user_id) ?>" class="stretched-link">
                    <i class="fa fa-fw fa-angle-right text-gray-500"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4 position-relative">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-globe mr-1"></i> <?= language()->admin_user_view->domains ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->domains) ?></span></div>
            </div>

            <div class="bg-gray-200 px-2 d-flex flex-column justify-content-center">
                <a href="<?= url('admin/domains?user_id=' . $data->user->user_id) ?>" class="stretched-link">
                    <i class="fa fa-fw fa-angle-right text-gray-500"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4 position-relative">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-funnel-dollar mr-1"></i> <?= language()->admin_user_view->payments ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->payments) ?></span></div>
            </div>

            <div class="bg-gray-200 px-2 d-flex flex-column justify-content-center">
                <a href="<?= url('admin/payments?user_id=' . $data->user->user_id) ?>" class="stretched-link">
                    <i class="fa fa-fw fa-angle-right text-gray-500"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<?php if($data->user_logs_result->num_rows): ?>
    <h2 class="h4 mt-5"><?= language()->admin_user_view->logs->header ?></h2>
    <p class="text-muted"><?= language()->admin_user_view->logs->subheader ?></p>

    <div class="table-responsive table-custom-container">
        <table class="table table-custom">
            <thead>
            <tr>
                <th><?= language()->admin_user_view->logs->type ?></th>
                <th><?= language()->admin_user_view->logs->ip ?></th>
                <th><?= language()->admin_user_view->logs->date ?></th>
            </tr>
            </thead>
            <tbody>

            <?php while($row = $data->user_logs_result->fetch_object()): ?>
                <tr>
                    <td><?= $row->type ?></td>
                    <td><?= $row->ip ?></td>
                    <td><span class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->date, 1) ?>"><?= \Altum\Date::get_timeago($row->date) ?></span></td>
                </tr>
            <?php endwhile ?>

            </tbody>
        </table>
    </div>
<?php endif ?>
