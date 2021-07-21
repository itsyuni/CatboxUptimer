<?php defined('ALTUMCODE') || die() ?>

<div class="row">

    <div class="col-12 col-lg-6 my-3">
        <div class="card h-100">
            <div class="card-body">
                <h3 class="h5"><?= language()->status_page_statistics->statistics->country ?></h3>
                <p></p>

                <?php $i = 0; foreach($data->statistics['country_code'] as $key => $value): $i++; if($i > 5) break; ?>
                    <?php $percentage = round($value / $data->statistics['country_code_total_sum'] * 100, 1) ?>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-1">
                            <div class="text-truncate">
                                <img src="<?= ASSETS_FULL_URL . 'images/countries/' . ($key ? mb_strtolower($key) : 'unknown') . '.svg' ?>" class="img-fluid icon-favicon mr-1" />
                                <?php if($key): ?>
                                    <a href="<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=city_name&country_code=' . $key . '&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>" title="<?= $key ?>" class="align-middle"><?= $key ? get_country_from_country_code($key) : language()->status_page_statistics->statistics->country_unknown ?></a>
                                <?php else: ?>
                                    <span class="align-middle"><?= $key ? get_country_from_country_code($key) : language()->status_page_statistics->statistics->country_unknown ?></span>
                                <?php endif ?>
                            </div>

                            <div>
                                <small class="text-muted"><?= nr($percentage) . '%' ?></small>
                                <span class="ml-3"><?= nr($value) ?></span>
                            </div>
                        </div>

                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>

            <div class="px-3 py-2">
                <a href="<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=country&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>" class="text-muted"><?= language()->status_page_statistics->statistics->view_more ?></a>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 my-3">
        <div class="card h-100">
            <div class="card-body">
                <h3 class="h5"><?= language()->status_page_statistics->statistics->referrer_host ?></h3>
                <p></p>

                <?php $i = 0; foreach($data->statistics['referrer_host'] as $key => $value): $i++; if($i > 5) break; ?>
                    <?php $percentage = round($value / $data->statistics['referrer_host_total_sum'] * 100, 1) ?>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-1">
                            <div class="text-truncate">
                                <?php if(!$key): ?>
                                    <span><?= language()->status_page_statistics->statistics->referrer_direct ?></span>
                                <?php elseif($key == 'qr'): ?>
                                    <span><?= language()->status_page_statistics->statistics->referrer_qr ?></span>
                                <?php else: ?>
                                    <img src="https://external-content.duckduckgo.com/ip3/<?= $key ?>.ico" class="img-fluid icon-favicon mr-1" />
                                    <a href="<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=referrer_path&referrer_host=' . $key . '&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>" title="<?= $key ?>" class="align-middle"><?= $key ?></a>
                                    <a href="<?= 'https://' . $key ?>" target="_blank" rel="nofollow noopener" class="text-muted ml-1"><i class="fa fa-fw fa-xs fa-external-link-alt"></i></a>
                                <?php endif ?>
                            </div>

                            <div>
                                <small class="text-muted"><?= nr($percentage) . '%' ?></small>
                                <span class="ml-3"><?= nr($value) ?></span>
                            </div>
                        </div>

                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>

            <div class="px-3 py-2">
                <a href="<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=referrer_host&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>" class="text-muted"><?= language()->status_page_statistics->statistics->view_more ?></a>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 my-3">
        <div class="card h-100">
            <div class="card-body">
                <h3 class="h5"><?= language()->status_page_statistics->statistics->device ?></h3>
                <p></p>

                <?php $i = 0; foreach($data->statistics['device_type'] as $key => $value): $i++; if($i > 5) break; ?>
                    <?php $percentage = round($value / $data->statistics['device_type_total_sum'] * 100, 1) ?>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-1">
                            <div class="text-truncate">
                                <?php if(!$key): ?>
                                    <span><?= language()->status_page_statistics->statistics->device_type_unknown ?></span>
                                <?php else: ?>
                                    <span><?= language()->status_page_statistics->statistics->{'device_' . $key} ?></span>
                                <?php endif ?>
                            </div>

                            <div>
                                <small class="text-muted"><?= nr($percentage) . '%' ?></small>
                                <span class="ml-3"><?= nr($value) ?></span>
                            </div>
                        </div>

                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>

            <div class="px-3 py-2">
                <a href="<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=device&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>" class="text-muted"><?= language()->status_page_statistics->statistics->view_more ?></a>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 my-3">
        <div class="card h-100">
            <div class="card-body">
                <h3 class="h5"><?= language()->status_page_statistics->statistics->os ?></h3>
                <p></p>

                <?php $i = 0; foreach($data->statistics['os_name'] as $key => $value): $i++; if($i > 5) break; ?>
                    <?php $percentage = round($value / $data->statistics['os_name_total_sum'] * 100, 1) ?>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-1">
                            <div class="text-truncate">
                                <?php if(!$key): ?>
                                    <span><?= language()->status_page_statistics->statistics->os_unknown ?></span>
                                <?php else: ?>
                                    <span><?= $key ?></span>
                                <?php endif ?>
                            </div>

                            <div>
                                <small class="text-muted"><?= nr($percentage) . '%' ?></small>
                                <span class="ml-3"><?= nr($value) ?></span>
                            </div>
                        </div>

                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>

            <div class="px-3 py-2">
                <a href="<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=os&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>" class="text-muted"><?= language()->status_page_statistics->statistics->view_more ?></a>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 my-3">
        <div class="card h-100">
            <div class="card-body">
                <h3 class="h5"><?= language()->status_page_statistics->statistics->browser ?></h3>
                <p></p>

                <?php $i = 0; foreach($data->statistics['browser_name'] as $key => $value): $i++; if($i > 5) break; ?>
                    <?php $percentage = round($value / $data->statistics['browser_name_total_sum'] * 100, 1) ?>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-1">
                            <div class="text-truncate">
                                <?php if(!$key): ?>
                                    <span><?= language()->status_page_statistics->statistics->browser_unknown ?></span>
                                <?php else: ?>
                                    <span><?= $key ?></span>
                                <?php endif ?>
                            </div>

                            <div>
                                <small class="text-muted"><?= nr($percentage) . '%' ?></small>
                                <span class="ml-3"><?= nr($value) ?></span>
                            </div>
                        </div>

                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>

            <div class="px-3 py-2">
                <a href="<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=browser&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>" class="text-muted"><?= language()->status_page_statistics->statistics->view_more ?></a>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 my-3">
        <div class="card h-100">
            <div class="card-body">
                <h3 class="h5"><?= language()->status_page_statistics->statistics->language ?></h3>
                <p></p>

                <?php $i = 0; foreach($data->statistics['browser_language'] as $key => $value): $i++; if($i > 5) break; ?>
                    <?php $percentage = round($value / $data->statistics['browser_language_total_sum'] * 100, 1) ?>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-1">
                            <div class="text-truncate">
                                <?php if(!$key): ?>
                                    <span><?= language()->status_page_statistics->statistics->language_unknown ?></span>
                                <?php else: ?>
                                    <span><?= get_language_from_locale($key) ?></span>
                                <?php endif ?>
                            </div>

                            <div>
                                <small class="text-muted"><?= nr($percentage) . '%' ?></small>
                                <span class="ml-3"><?= nr($value) ?></span>
                            </div>
                        </div>

                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>

            <div class="px-3 py-2">
                <a href="<?= url('status-page-statistics/' . $data->status_page->status_page_id . '?type=language&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>" class="text-muted"><?= language()->status_page_statistics->statistics->view_more ?></a>
            </div>
        </div>
    </div>

</div>

