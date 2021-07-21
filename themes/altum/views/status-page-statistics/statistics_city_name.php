<?php defined('ALTUMCODE') || die() ?>

<div class="card my-3">
    <div class="card-body">
        <h3 class="h5"><?= sprintf(language()->status_page_statistics->statistics->city, get_country_from_country_code($data->country_code)) ?></h3>
        <p class="text-muted mb-3"><?= language()->status_page_statistics->statistics->city_help ?></p>

        <?php foreach($data->rows as $row): ?>
            <?php $percentage = round($row->total / $data->total_sum * 100, 1) ?>

            <div class="mt-4">
                <div class="d-flex justify-content-between mb-1">
                    <div class="text-truncate">
                        <img src="<?= ASSETS_FULL_URL . 'images/countries/' . ($data->country_code ? mb_strtolower($data->country_code) : 'unknown') . '.svg' ?>" class="img-fluid icon-favicon mr-1" />
                        <span class="align-middle"><?= $row->city_name ? $row->city_name : language()->status_page_statistics->statistics->city_unknown ?></span>
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
