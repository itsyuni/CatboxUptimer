<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-xl d-flex align-items-center mb-3 mb-xl-0">
            <h1 class="h4 m-0"><?= language()->domains->header ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= language()->domains->subheader ?>">
                    <i class="fa fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

        <div class="col-12 col-xl-auto">
            <?php if($this->user->plan_settings->domains_limit != -1 && $data->total_domains >= $this->user->plan_settings->domains_limit): ?>
                <button type="button" class="btn btn-outline-primary disabled" data-toggle="tooltip" title="<?= language()->domains->error_message->domains_limit ?>">
                    <i class="fa fa-fw fa-sm fa-plus"></i> <?= language()->domains->create ?>
                </button>
            <?php else: ?>
                <a href="<?= url('domain-create') ?>" class="btn btn-outline-primary"><i class="fa fa-fw fa-sm fa-plus"></i> <?= language()->domains->create ?></a>
            <?php endif ?>
        </div>
    </div>

    <?php if(count($data->domains)): ?>
        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th><?= language()->domains->table->host ?></th>
                        <th><?= language()->domains->table->is_enabled ?></th>
                        <th><?= language()->domains->table->datetime ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach($data->domains as $row): ?>

                    <tr>
                        <td>
                            <a href="<?= url('domain-update/' . $row->domain_id) ?>"><?= $row->host ?></a>
                        </td>

                        <td>
                            <?php if($row->is_enabled): ?>
                                <span class="badge badge-pill badge-success"><i class="fa fa-fw fa-check"></i> <?= language()->domains->table->is_enabled_active ?></span>
                            <?php else: ?>
                                <span class="badge badge-pill badge-warning"><i class="fa fa-fw fa-eye-slash"></i> <?= language()->domains->table->is_enabled_pending ?></span>
                            <?php endif ?>
                        </td>

                        <td class="text-muted">
                            <span data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>"><?= \Altum\Date::get($row->datetime, 2) ?></span>
                        </td>

                        <td>
                            <?= include_view(THEME_PATH . 'views/domains/domain_dropdown_button.php', ['id' => $row->domain_id]) ?>
                        </td>
                    </tr>
                <?php endforeach ?>

                </tbody>
            </table>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= ASSETS_FULL_URL . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-5 mb-3" alt="<?= language()->domains->no_data ?>" />
            <h2 class="h4 text-muted mt-3"><?= language()->domains->no_data ?></h2>
            <p class="text-muted"><?= language()->domains->no_data_help ?></p>
        </div>

    <?php endif ?>

</div>
