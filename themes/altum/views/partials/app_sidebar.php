<?php defined('ALTUMCODE') || die() ?>

<div class="app-sidebar">
    <div class="app-sidebar-title text-truncate">
        <a href="<?= url() ?>">
        <?php if(settings()->logo != ''): ?>
            <img src="<?= UPLOADS_FULL_URL . 'logo/' . settings()->logo ?>" class="img-fluid navbar-logo" alt="<?= language()->global->accessibility->logo_alt ?>" />
        <?php else: ?>
            <?= settings()->title ?>
        <?php endif ?>
        </a>
    </div>

    <div class="overflow-auto flex-grow-1">
        <ul class="app-sidebar-links">
            <li class="<?= \Altum\Routing\Router::$controller == 'Dashboard' ? 'active' : null ?>">
                <a href="<?= url('dashboard') ?>"><i class="fa fa-fw fa-sm fa-th mr-2"></i> <?= language()->dashboard->menu ?></a>
            </li>

            <li class="<?= \Altum\Routing\Router::$controller == 'Monitors' ? 'active' : null ?>">
                <a href="<?= url('monitors') ?>"><i class="fa fa-fw fa-sm fa-server mr-2"></i> <?= language()->monitors->menu ?></a>
            </li>

            <li class="<?= \Altum\Routing\Router::$controller == 'Heartbeats' ? 'active' : null ?>">
                <a href="<?= url('heartbeats') ?>"><i class="fa fa-fw fa-sm fa-heartbeat mr-2"></i> <?= language()->heartbeats->menu ?></a>
            </li>

            <li class="<?= \Altum\Routing\Router::$controller == 'StatusPages' ? 'active' : null ?>">
                <a href="<?= url('status-pages') ?>"><i class="fa fa-fw fa-sm fa-wifi mr-2"></i> <?= language()->status_pages->menu ?></a>
            </li>

            <li class="<?= \Altum\Routing\Router::$controller == 'Projects' ? 'active' : null ?>">
                <a href="<?= url('projects') ?>"><i class="fa fa-fw fa-sm fa-project-diagram mr-2"></i> <?= language()->projects->menu ?></a>
            </li>

            <?php if(settings()->status_pages->domains_is_enabled): ?>
                <li class="<?= \Altum\Routing\Router::$controller == 'Domains' ? 'active' : null ?>">
                    <a href="<?= url('domains') ?>"><i class="fa fa-fw fa-sm fa-globe mr-2"></i> <?= language()->domains->menu ?></a>
                </li>
            <?php endif ?>

            <?php foreach($data->pages as $data): ?>
                <li>
                    <a href="<?= $data->url ?>" target="<?= $data->target ?>"><?= $data->title ?></a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>

    <?php if(\Altum\Middlewares\Authentication::check()): ?>

    <ul class="app-sidebar-links">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="d-flex align-items-center app-sidebar-footer-block">
                    <img src="<?= get_gravatar($this->user->email) ?>" class="app-sidebar-avatar mr-3" />

                    <div class="app-sidebar-footer-text d-flex flex-column text-truncate">
                        <span class="text-truncate"><?= $this->user->name ?></span>
                        <small class="text-truncate"><?= $this->user->email ?></small>
                    </div>
                </div>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <?php if(\Altum\Middlewares\Authentication::is_admin()): ?>
                    <a class="dropdown-item" href="<?= url('admin') ?>"><i class="fa fa-fw fa-sm fa-user-shield mr-1"></i> <?= language()->global->menu->admin ?></a>
                <?php endif ?>
                <a class="dropdown-item" href="<?= url('account') ?>"><i class="fa fa-fw fa-sm fa-wrench mr-1"></i> <?= language()->account->menu ?></a>

                <a class="dropdown-item" href="<?= url('account-plan') ?>"><i class="fa fa-fw fa-sm fa-box-open mr-1"></i> <?= language()->account_plan->menu ?></a>

                <?php if(settings()->payment->is_enabled): ?>
                    <a class="dropdown-item" href="<?= url('account-payments') ?>"><i class="fa fa-fw fa-sm fa-dollar-sign mr-1"></i> <?= language()->account_payments->menu ?></a>

                    <?php if(settings()->affiliate->is_enabled): ?>
                        <a class="dropdown-item" href="<?= url('referrals') ?>"><i class="fa fa-fw fa-sm fa-wallet mr-1"></i> <?= language()->referrals->menu ?></a>
                    <?php endif ?>
                <?php endif ?>

                <a class="dropdown-item" href="<?= url('account-api') ?>"><i class="fa fa-fw fa-sm fa-code mr-1"></i> <?= language()->account_api->menu ?></a>

                <a class="dropdown-item" href="<?= url('logout') ?>"><i class="fa fa-fw fa-sm fa-sign-out-alt mr-1"></i> <?= language()->global->menu->logout ?></a>
            </div>
        </li>
    </ul>

    <?php else: ?>

        <ul class="app-sidebar-links">
            <li>
                <a class="nav-link" href="<?= url('login') ?>"><i class="fa fa-fw fa-sm fa-sign-in-alt mr-1"></i> <?= language()->login->menu ?></a>
            </li>

            <?php if(settings()->register_is_enabled): ?>
                <li><a class="nav-link" href="<?= url('register') ?>"><i class="fa fa-fw fa-sm fa-user-plus mr-1"></i> <?= language()->register->menu ?></a></li>
            <?php endif ?>
        </ul>

    <?php endif ?>
</div>
