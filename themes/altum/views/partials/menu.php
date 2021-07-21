<?php defined('ALTUMCODE') || die() ?>

<nav class="navbar navbar-main navbar-expand-lg navbar-light bg-white <?= \Altum\Routing\Router::$controller_key == 'index' ? null : 'mb-6' ?>">
    <div class="container">
        <a class="navbar-brand" href="<?= url() ?>">
            <?php if(settings()->logo != ''): ?>
                <img src="<?= UPLOADS_FULL_URL . 'logo/' . settings()->logo ?>" class="img-fluid navbar-logo" alt="<?= language()->global->accessibility->logo_alt ?>" />
            <?php else: ?>
                <?= settings()->title ?>
            <?php endif ?>
        </a>

        <button class="btn navbar-custom-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#main_navbar" aria-controls="main_navbar" aria-expanded="false" aria-label="<?= language()->global->accessibility->toggle_navigation ?>">
            <i class="fa fa-fw fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="main_navbar">
            <ul class="navbar-nav">

                <?php foreach($data->pages as $data): ?>
                <li class="nav-item"><a class="nav-link" href="<?= $data->url ?>" target="<?= $data->target ?>"><?= $data->title ?></a></li>
                <?php endforeach ?>

                <?php if(settings()->payment->is_enabled): ?>
                <li class="nav-item"><a class="nav-link" href="<?= url('plan') ?>"> <?= language()->plan->menu ?></a></li>

                    <?php if(settings()->affiliate->is_enabled): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= url('affiliate') ?>"> <?= language()->affiliate->menu ?></a></li>
                    <?php endif ?>
                <?php endif ?>

                <?php if(\Altum\Middlewares\Authentication::check()): ?>

                    <li class="nav-item"><a class="nav-link font-wi" href="<?= url('dashboard') ?>"> <?= language()->dashboard->menu ?></a></li>

                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
                            <img src="<?= get_gravatar($this->user->email) ?>" class="navbar-avatar mr-1" />
                            <span class="align-middle"><?= $this->user->name ?></span>
                            <span class="align-middle"><i class="fa fa-fw fa-sm fa-caret-down"></i></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <?php if(\Altum\Middlewares\Authentication::is_admin()): ?>
                                <a class="dropdown-item" href="<?= url('admin') ?>"><i class="fa fa-fw fa-sm fa-user-shield fa-fw mr-1"></i> <?= language()->global->menu->admin ?></a>
                            <?php endif ?>

                            <a class="dropdown-item" href="<?= url('account') ?>"><i class="fa fa-fw fa-sm fa-wrench fa-fw mr-1"></i> <?= language()->account->menu ?></a>

                            <a class="dropdown-item" href="<?= url('account-plan') ?>"><i class="fa fa-fw fa-sm fa-box-open fa-fw mr-1"></i> <?= language()->account_plan->menu ?></a>
                            <?php if(settings()->payment->is_enabled): ?>
                                <a class="dropdown-item" href="<?= url('account-payments') ?>"><i class="fa fa-fw fa-sm fa-dollar-sign mr-1"></i> <?= language()->account_payments->menu ?></a>

                                <?php if(settings()->affiliate->is_enabled): ?>
                                    <a class="dropdown-item" href="<?= url('referrals') ?>"><i class="fa fa-fw fa-sm fa-wallet mr-1"></i> <?= language()->referrals->menu ?></a>
                                <?php endif ?>
                            <?php endif ?>
                            <a class="dropdown-item" href="<?= url('account-api') ?>"><i class="fa fa-fw fa-sm fa-code mr-1"></i> <?= language()->account_api->menu ?></a>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= url('logout') ?>"><i class="fa fa-fw fa-sm fa-sign-out-alt fa-fw mr-1"></i> <?= language()->global->menu->logout ?></a>
                        </div>
                    </li>

                <?php else: ?>

                    <li class="nav-item d-flex align-items-center"><a class="btn btn-sm btn-outline-primary" href="<?= url('login') ?>"><?= language()->login->menu ?></a></li>

                    <?php if(settings()->register_is_enabled): ?>
                    <li class="nav-item d-flex align-items-center"><a class="btn btn-sm btn-primary" href="<?= url('register') ?>"><?= language()->register->menu ?> <i class="fa fa-fw fa-xs fa-arrow-right ml-1"></i></a></li>
                    <?php endif ?>

                <?php endif ?>

            </ul>
        </div>
    </div>
</nav>
