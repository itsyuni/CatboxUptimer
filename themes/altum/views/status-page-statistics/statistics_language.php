<?php defined('ALTUMCODE') || die() ?>

<div class="card my-3">
    <div class="card-body">
        <h3 class="h5"><?= language()->status_page_statistics->statistics->language ?></h3>
        <p class="text-muted mb-3"><?= language()->status_page_statistics->statistics->language_help ?></p>

        <?php foreach($data->rows as $row): ?>
            <?php $percentage = round($row->total / $data->total_sum * 100, 1) ?>

            <div class="mt-4">
                <div class="d-flex justify-content-between mb-1">
                    <div class="text-truncate">
                        <?php if(!$row->browser_language): ?>
                            <span><?= language()->status_page_statistics->statistics->browser_unknown ?></span>
                        <?php else: ?>
                            <span><?= get_language_from_locale($row->browser_language) ?></span>
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
