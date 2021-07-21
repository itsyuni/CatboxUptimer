<?php defined('ALTUMCODE') || die() ?>

<div class="col-12 col-md-6 col-xl-4 mb-4">
    <div class="card h-100" <?= $data->status_page->project_id ? 'style="border-color: ' . $data->projects[$data->status_page->project_id]->color . ';"' : null ?>>
        <div class="card-body d-flex flex-column justify-content-between">
            <div class="d-flex justify-content-between">
                <h2 class="h5 m-0 card-title">
                    <a href="<?= url('status-page-update/' . $data->status_page->status_page_id) ?>"><?= $data->status_page->name ?></a>
                </h2>

                <?= include_view(THEME_PATH . 'views/status-page/status_page_dropdown_button.php', ['id' => $data->status_page->status_page_id]) ?>
            </div>

            <div>
                <small class="text-muted">
                    <i class="fa fa-fw fa-sm fa-external-link-alt text-muted mr-1"></i>

                    <a href="<?= $data->status_page->full_url ?>" class="text-muted" target="_blank" rel="noreferrer"><?= $data->status_page->full_url ?></a>
                </small>
            </div>

            <div>
                <small class="text-muted">
                    <i class="fa fa-fw fa-sm fa-chart-pie text-muted mr-1"></i>

                    <a href="<?= url('status-page-statistics/' . $data->status_page->status_page_id) ?>" class="text-muted"><?= sprintf(language()->status_pages->pageviews, nr($data->status_page->pageviews)) ?></a>
                </small>
            </div>
        </div>
    </div>
</div>
