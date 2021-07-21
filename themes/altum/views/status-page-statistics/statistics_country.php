<?php defined('ALTUMCODE') || die() ?>

<div class="card my-3">
    <div class="card-body">
        <h3 class="h5"><?= language()->status_page_statistics->statistics->country ?></h3>
        <p class="text-muted mb-3"><?= language()->status_page_statistics->statistics->country_help ?></p>

        <?php foreach($data->rows as $row): ?>
            <?php $percentage = round($row->total / $data->total_sum * 100, 1) ?>

            <div class="mt-4">
                <div class="d-flex justify-content-between mb-1">
                    <div class="text-truncate">
                        <img src="<?= ASSETS_FULL_URL . 'images/countries/' . ($row->country_code ? mb_strtolower($row->country_code) : 'unknown') . '.svg' ?>" class="img-fluid icon-favicon mr-1" />
                        <?php if($row->country_code): ?>
                            <a href="<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=city_name&country_code=' . $row->country_code . '&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>" title="<?= $row->country_code ?>" class="align-middle"><?= $row->country_code ? get_country_from_country_code($row->country_code) : language()->status_page_statistics->statistics->country_unknown ?></a>
                        <?php else: ?>
                            <span class="align-middle"><?= $row->country_code ? get_country_from_country_code($row->country_code) : language()->status_page_statistics->statistics->country_unknown ?></span>
                        <?php endif ?>
                    </div>

                    <div>
                        <small class="text-muted"><?= nr($percentage) . '%' ?></small>
                        <span class="ml-3"><?= nr($row->total) ?></span>
                    </div>
                </div>

                <div class="progress" style="height: 6px;">
                    <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
