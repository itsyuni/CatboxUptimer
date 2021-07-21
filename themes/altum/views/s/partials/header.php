<?php defined('ALTUMCODE') || die() ?>

<nav class="p-2 bg-white status-page-navbar d-lg-none">
    <div class="container">
        <div class="d-flex">
            <?php if($data->status_page->logo): ?>
                <img src="<?= UPLOADS_FULL_URL . 'status_pages_logos/' . $data->status_page->logo ?>" class="img-fluid status-page-navbar-logo mr-3" alt="<?= $data->status_page->name ?>" loading="lazy" />
            <?php endif ?>

            <div class="d-flex flex-column min-width-0">
                <div class="text-truncate">
                    <a class="font-weight-bold status-page-title" href="<?= $data->status_page->full_url ?>">
                        <?= $data->status_page->name ?>
                    </a>
                </div>
                <small class="text-truncate text-muted"><?= $data->status_page->description ?></small>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-5 d-none d-lg-block">
    <div class="d-flex align-items-center position-relative">
        <?php if($data->status_page->logo): ?>
        <div>
            <img src="<?= UPLOADS_FULL_URL . 'status_pages_logos/' . $data->status_page->logo ?>" class="img-fluid status-page-logo mr-4" alt="<?= $data->status_page->name ?>" loading="lazy" />
        </div>
        <?php endif ?>

        <div class="d-flex flex-column">
            <a href="<?= $data->status_page->full_url ?>" class="status-page-title stretched-link">
                <h1 class="h2 mb-0"><?= $data->status_page->name ?></h1>
            </a>

            <?php if($data->status_page->description): ?>
                <span class="status-page-description">
                    <?= $data->status_page->description ?>
                </span>
            <?php endif ?>
        </div>
    </div>
</div>
