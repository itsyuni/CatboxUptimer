<?php defined('ALTUMCODE') || die() ?>

<div class="container d-flex flex-column flex-md-row align-items-md-center my-5">
    <span class="text-muted mb-2 mb-md-0 mr-md-3"><?= language()->s_status_page->share->header ?></span>
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $data->external_url ?>" target="_blank" class="btn btn-sm btn-blue-50 mb-2 mb-md-0 mr-md-3"><?= language()->s_status_page->share->facebook ?></a>
    <a href="http://twitter.com/share?url=<?= $data->external_url ?>" target="_blank" class="btn btn-sm btn-blue-50 mb-2 mb-md-0 mr-md-3"><?= language()->s_status_page->share->twitter ?></a>
    <a href="mailto:?body=<?= $data->external_url ?>" target="_blank" class="btn btn-sm btn-blue-50 mb-2 mb-md-0 mr-md-3"><?= language()->s_status_page->share->email ?></a>
    <a href="https://wa.me/?text=<?= $data->external_url ?>" class="btn btn-sm btn-blue-50 mb-2 mb-md-0 mr-md-3"><?= language()->s_status_page->share->whatsapp ?></a>
</div>
