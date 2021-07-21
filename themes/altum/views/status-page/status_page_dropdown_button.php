<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
        <i class="fa fa-fw fa-ellipsis-v mr-1"></i>

        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="<?= url('status-page-redirect/' . $data->id) ?>" target="_blank" rel="noreferrer"><i class="fa fa-fw fa-sm fa-external-link-alt mr-1"></i> <?= language()->status_pages->external_url ?></a>
            <a class="dropdown-item" href="<?= url('status-page-qr/' . $data->id) ?>"><i class="fa fa-fw fa-sm fa-qrcode mr-1"></i> <?= language()->status_page_qr->menu ?></a>
            <a class="dropdown-item" href="<?= url('status-page-statistics/' . $data->id) ?>"><i class="fa fa-fw fa-sm fa-chart-pie mr-1"></i> <?= language()->status_page_statistics->menu ?></a>
            <a class="dropdown-item" href="<?= url('status-page-update/' . $data->id) ?>"><i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= language()->global->edit ?></a>
            <a href="#" data-toggle="modal" data-target="#status_page_delete_modal" data-status-page-id="<?= $data->id ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= language()->global->delete ?></a>
        </div>
    </a>
</div>
