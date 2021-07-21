<?php defined('ALTUMCODE') || die() ?>

<div class="index-hero pt-6 pb-4">
    <div class="container">
        <?= \Altum\Alerts::output_alerts() ?>

        <div class="row">
            <div class="col-12 col-lg-8 offset-lg-2 d-flex flex-column justify-content-center align-items-center text-center">
                <h1 class="index-header mb-4"><?= language()->index->header ?></h1>
                <p class="index-subheader"><?= sprintf(language()->index->subheader, '<span class="text-primary-800 font-weight-bold">', '</span>') ?></p>

                <div class="mt-5 mb-4">
                    <a href="<?= url('register') ?>" class="btn btn-primary index-button mb-2 mb-lg-0 mr-lg-2"><?= language()->index->get_started ?></a>
                    <a href="<?= url('s/example') ?>" target="_blank" class="btn btn-gray-100 index-button mb-2 mb-lg-0"><?= language()->index->example ?> <i class="fa fa-fw fa-xs fa-external-link-alt"></i></a>
                </div>

                <ul class="list-style-none d-flex flex-column flex-lg-row my-4">
                    <li class="d-flex align-items-center mb-2 mb-lg-0 mx-lg-3">
                        <i class="fa fa-fw mr-2 fa-check-circle text-primary"></i>
                        <div class="text-muted">
                            <?= language()->index->feature->one ?>
                        </div>
                    </li>

                    <li class="d-flex align-items-center mb-2 mb-lg-0 mx-lg-3">
                        <i class="fa fa-fw mr-2 fa-check-circle text-primary"></i>
                        <div class="text-muted">
                            <?= language()->index->feature->two ?>
                        </div>
                    </li>

                    <li class="d-flex align-items-center mb-2 mb-lg-0 mx-lg-3">
                        <i class="fa fa-fw mr-2 fa-check-circle text-primary"></i>
                        <div class="text-muted">
                            <?= language()->index->feature->three ?>
                        </div>
                    </li>
                </ul>

            </div>
        </div>

    </div>
</div>

<div class="my-3">&nbsp;</div>

<div class="container">

    <div class="row">
        <div class="col-12 col-lg-4">
            <div class="card bg-gray-50 border-0 mb-4 position-relative">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fa fa-fw fa-server mr-1"></i> <a href="#tab-monitors" class="h5 text-blue-500 stretched-link text-decoration-none">
                            <?= language()->index->monitors->header ?>
                        </a>
                    </div>
                    <span class="text-muted"><?= language()->index->monitors->subheader ?></span>
                </div>
            </div>

            <div class="card bg-gray-50 border-0 mb-4 position-relative">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fa fa-fw fa-heartbeat mr-1"></i> <a href="#tab-heartbeats" class="h5 text-blue-500 stretched-link text-decoration-none">
                            <?= language()->index->heartbeats->header ?>
                        </a>
                    </div>
                    <span class="text-muted"><?= language()->index->heartbeats->subheader ?></span>
                </div>
            </div>

            <div class="card bg-gray-50 border-0 mb-4 position-relative">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fa fa-fw fa-wifi mr-1"></i> <a href="#tab-status-pages" class="h5 text-blue-500 stretched-link text-decoration-none">
                            <?= language()->index->status_pages->header ?>
                        </a>
                    </div>
                    <span class="text-muted"><?= language()->index->status_pages->subheader ?></span>
                </div>
            </div>

            <div class="card bg-gray-50 border-0 mb-4 position-relative">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fa fa-fw fa-times mr-1"></i> <a href="#tab-incidents" class="h5 text-blue-500 stretched-link text-decoration-none">
                            <?= language()->index->incidents->header ?>
                        </a>
                    </div>
                    <span class="text-muted"><?= language()->index->incidents->subheader ?></span>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="" id="tab-monitors">
                <img src="<?= ASSETS_FULL_URL . 'images/index/monitor.jpg' ?>" class="img-fluid shadow-lg rounded" loading="lazy" />
            </div>
            <div class="d-none" id="tab-heartbeats">
                <img src="<?= ASSETS_FULL_URL . 'images/index/heartbeat.jpg' ?>" class="img-fluid shadow-lg rounded" loading="lazy" />
            </div>
            <div class="d-none" id="tab-status-pages">
                <img src="<?= ASSETS_FULL_URL . 'images/index/status-page.jpg' ?>" class="img-fluid shadow-lg rounded" loading="lazy" />
            </div>
            <div class="d-none" id="tab-incidents">
                <img src="<?= ASSETS_FULL_URL . 'images/index/incidents.jpg' ?>" class="img-fluid shadow-lg rounded" loading="lazy" />
            </div>
        </div>
    </div>

    <?php ob_start() ?>
    <script>
        document.querySelectorAll('a[href^="#tab-"]').forEach(element => {
            element.addEventListener('click', event => {

                let target = element.getAttribute('href').replace('#', '');

                document.querySelectorAll('div[id^="tab-"]').forEach(image => {
                    image.classList.remove('d-none');
                    image.classList.add('d-none');
                });

                document.querySelector(`div[id="${target}"]`).classList.remove('d-none');

                event.preventDefault();
            })
        })
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
</div>

<div class="my-5">&nbsp;</div>

<div class="bg-blue-900 py-8">
    <div class="container text-center">
        <span class="text-white h2"><?= sprintf(language()->index->stats, nr($data->total_monitors_logs), nr($data->total_monitors), nr($data->total_status_pages)) ?></span>
    </div>
</div>

<div class="my-5">&nbsp;</div>

<div class="container">
    <div class="row">
        <div class="col-6 col-lg-4 mb-5">
            <div class="d-flex flex-column justify-content-between h-100">
                <img src="<?= ASSETS_FULL_URL . 'images/index/ping-servers.png' ?>" class="img-fluid rounded mb-4 index-card-image" loading="lazy" />

                <div>
                    <div class="mb-2">
                        <span class="h5"><?= language()->index->ping_servers->header ?></span>
                    </div>
                    <span class="text-muted"><?= language()->index->ping_servers->subheader ?></span>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-4 mb-5">
            <div class="d-flex flex-column justify-content-between h-100">
                <img src="<?= ASSETS_FULL_URL . 'images/index/custom-request.png' ?>" class="img-fluid rounded mb-4 index-card-image" loading="lazy" />

                <div>
                    <div class="mb-2">
                        <span class="h5"><?= language()->index->custom_request->header ?></span>
                    </div>
                    <span class="text-muted"><?= language()->index->custom_request->subheader ?></span>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-4 mb-5">
            <div class="d-flex flex-column justify-content-between h-100">
                <img src="<?= ASSETS_FULL_URL . 'images/index/custom-response.png' ?>" class="img-fluid rounded mb-4 index-card-image" loading="lazy" />

                <div>
                    <div class="mb-2">
                        <span class="h5"><?= language()->index->custom_response->header ?></span>
                    </div>
                    <span class="text-muted"><?= language()->index->custom_response->subheader ?></span>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-4 mb-5">
            <div class="d-flex flex-column justify-content-between h-100">
                <img src="<?= ASSETS_FULL_URL . 'images/index/notifications.png' ?>" class="img-fluid rounded mb-4 index-card-image" loading="lazy" />

                <div>
                    <div class="mb-2">
                        <span class="h5"><?= language()->index->notifications->header ?></span>
                    </div>
                    <span class="text-muted"><?= language()->index->notifications->subheader ?></span>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-4 mb-5">
            <div class="d-flex flex-column justify-content-between h-100">
                <img src="<?= ASSETS_FULL_URL . 'images/index/projects.png' ?>" class="img-fluid rounded mb-4 index-card-image" loading="lazy" />

                <div>
                    <div class="mb-2">
                        <span class="h5"><?= language()->index->projects->header ?></span>
                    </div>
                    <span class="text-muted"><?= language()->index->projects->subheader ?></span>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-4 mb-5">
            <div class="d-flex flex-column justify-content-between h-100">
                <img src="<?= ASSETS_FULL_URL . 'images/index/custom-domains.png' ?>" class="img-fluid rounded mb-4 index-card-image" loading="lazy" />

                <div>
                    <div class="mb-2">
                        <span class="h5"><?= language()->index->custom_domains->header ?></span>
                    </div>
                    <span class="text-muted"><?= language()->index->custom_domains->subheader ?></span>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="my-5">&nbsp;</div>

<div class="container">
    <div class="text-center mb-5">
        <small class="text-primary font-weight-bold text-uppercase"><?= language()->index->pricing->header_help ?></small>
        <h2 class="mt-2"><?= language()->index->pricing->header ?></h2>
    </div>

    <?= $this->views['plans'] ?>
</div>

<div class="my-5">&nbsp;</div>

<?php if(settings()->register_is_enabled): ?>
    <div class="bg-blue-50 py-6">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row justify-content-around align-items-lg-center">
                <div>
                    <h2 class="text-blue-900"><?= language()->index->cta->header ?></h2>
                    <p class="text-blue-800"><?= language()->index->cta->subheader ?></p>
                </div>

                <div>
                    <a href="<?= url('register') ?>" class="btn btn-primary index-button"><?= language()->index->cta->register ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>


