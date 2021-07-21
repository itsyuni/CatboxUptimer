<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="mb-3 d-flex justify-content-between">
        <div>
            <h1 class="h4 mb-0 text-truncate"><?= language()->dashboard->header ?></h1>
        </div>
    </div>

    <div class="my-4">
        <div class="d-flex align-items-center mb-3">
            <h2 class="h6 text-uppercase text-muted mb-0 mr-3"><i class="fa fa-fw fa-sm fa-server mr-1"></i> <?= language()->dashboard->monitors->header ?></h2>

            <div class="flex-fill">
                <hr class="border-gray-100" />
            </div>

            <div class="ml-3">
                <a href="<?= url('monitor-create') ?>" class="btn btn-sm btn-outline-primary"><i class="fa fa-fw fa-sm fa-plus"></i> <?= language()->monitors->create ?></a>
            </div>
        </div>

        <?php if(count($data->monitors)): ?>
            <div class="row">

                <?php foreach($data->monitors as $row): ?>
                    <?= (new \Altum\Views\View('monitors/monitor_widget', (array) $this))->run(['monitor' => $row, 'projects' => $data->projects]) ?>
                <?php endforeach ?>

                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100 position-relative">
                        <div class="card-body d-flex justify-content-center align-items-center h-100">
                            <span class="h6 m-0 card-title">
                                <a href="<?= url('monitors') ?>" class="stretched-link text-muted"><?= language()->dashboard->view_all ?></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>

            <div class="d-flex flex-column align-items-center justify-content-center">
                <h2 class="h4 text-muted mt-3"><?= language()->monitors->no_data ?></h2>
                <p class="text-muted"><?= language()->monitors->no_data_help ?></p>
            </div>

        <?php endif ?>
    </div>


    <div class="my-4">
        <div class="d-flex align-items-center mb-3">
            <h2 class="h6 text-uppercase text-muted mb-0 mr-3"><i class="fa fa-fw fa-sm fa-heartbeat mr-1"></i> <?= language()->dashboard->heartbeats->header ?></h2>

            <div class="flex-fill">
                <hr class="border-gray-100" />
            </div>

            <div class="ml-3">
                <a href="<?= url('heartbeat-create') ?>" class="btn btn-sm btn-outline-primary"><i class="fa fa-fw fa-sm fa-plus"></i> <?= language()->heartbeats->create ?></a>
            </div>
        </div>

        <?php if(count($data->heartbeats)): ?>
            <div class="row">

                <?php foreach($data->heartbeats as $row): ?>
                    <?= (new \Altum\Views\View('heartbeats/heartbeat_widget', (array) $this))->run(['heartbeat' => $row, 'projects' => $data->projects]) ?>
                <?php endforeach ?>

                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100 position-relative">
                        <div class="card-body d-flex justify-content-center align-items-center h-100">
                            <span class="h6 m-0 card-title">
                                <a href="<?= url('heartbeats') ?>" class="stretched-link text-muted"><?= language()->dashboard->view_all ?></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>

            <div class="d-flex flex-column align-items-center justify-content-center">
                <h2 class="h4 text-muted mt-3"><?= language()->heartbeats->no_data ?></h2>
                <p class="text-muted"><?= language()->heartbeats->no_data_help ?></p>
            </div>

        <?php endif ?>
    </div>


    <?php if(count($data->monitors)): ?>
    <div class="my-4">
        <div class="d-flex align-items-center mb-3">
            <h2 class="h6 text-uppercase text-muted mb-0 mr-3"><i class="fa fa-fw fa-sm fa-wifi mr-1"></i> <?= language()->dashboard->status_pages->header ?></h2>

            <div class="flex-fill">
                <hr class="border-gray-100" />
            </div>

            <div class="ml-3">
                <a href="<?= url('status-page-create') ?>" class="btn btn-sm btn-outline-primary"><i class="fa fa-fw fa-sm fa-plus"></i> <?= language()->status_pages->create ?></a>
            </div>
        </div>

        <?php if(count($data->status_pages)): ?>
            <div class="row">

                <?php foreach($data->status_pages as $row): ?>
                    <?= (new \Altum\Views\View('status-pages/status_page_widget', (array) $this))->run(['status_page' => $row, 'projects' => $data->projects]) ?>
                <?php endforeach ?>

                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100 position-relative">
                        <div class="card-body d-flex justify-content-center align-items-center h-100">
                            <span class="h6 m-0 card-title">
                                <a href="<?= url('status-pages') ?>" class="stretched-link text-muted"><?= language()->dashboard->view_all ?></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>

            <div class="d-flex flex-column align-items-center justify-content-center">
                <h2 class="h4 text-muted mt-3"><?= language()->status_pages->no_data ?></h2>
                <p class="text-muted"><?= language()->status_pages->no_data_help ?></p>
            </div>

        <?php endif ?>
    </div>
    <?php endif ?>
</div>

