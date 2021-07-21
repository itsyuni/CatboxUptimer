<?php defined('ALTUMCODE') || die() ?>

<section class="admin-sidebar">
    <div class="admin-sidebar-title">
        <a href="<?= url() ?>" class="text-decoration-none text-truncate">
            <?php if(settings()->logo != ''): ?>
                <img src="<?= UPLOADS_FULL_URL . 'logo/' . settings()->logo ?>" class="img-fluid admin-navbar-logo" alt="<?= language()->global->accessibility->logo_alt ?>" />
            <?php else: ?>
                <span class="admin-navbar-brand"><?= settings()->title ?></span>
            <?php endif ?>
        </a>
    </div>

    <ul class="admin-sidebar-links">
        <li class="<?= \Altum\Routing\Router::$controller == 'AdminIndex' ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-tv"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->admin_index->menu ?></span>
                </div>
            </a>
        </li>

        <li class="<?= in_array(\Altum\Routing\Router::$controller, ['AdminUsers', 'AdminUserUpdate', 'AdminUserCreate', 'AdminUserView']) ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/users') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-users"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->admin_users->menu ?></span>
                </div>
            </a>
        </li>

        <li class="<?= in_array(\Altum\Routing\Router::$controller, ['AdminMonitors']) ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/monitors') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-server"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->admin_monitors->menu ?></span>
                </div>
            </a>
        </li>

        <li class="<?= in_array(\Altum\Routing\Router::$controller, ['AdminHeartbeats']) ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/heartbeats') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-heartbeat"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->admin_heartbeats->menu ?></span>
                </div>
            </a>
        </li>

        <li class="<?= in_array(\Altum\Routing\Router::$controller, ['AdminStatusPages']) ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/status-pages') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-wifi"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->admin_status_pages->menu ?></span>
                </div>
            </a>
        </li>

        <li class="<?= in_array(\Altum\Routing\Router::$controller, ['AdminProjects']) ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/projects') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-project-diagram"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->admin_projects->menu ?></span>
                </div>
            </a>
        </li>

        <li class="<?= in_array(\Altum\Routing\Router::$controller, ['AdminPingServers', 'AdminPingServerCreate', 'AdminPingServerUpdate']) ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/ping-servers') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-map-marked-alt"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->admin_ping_servers->menu ?></span>
                </div>
            </a>
        </li>

        <?php if(in_array(settings()->license->type, ['Extended License', 'extended'])): ?>
        <li class="<?= in_array(\Altum\Routing\Router::$controller, ['AdminDomains', 'AdminDomainCreate', 'AdminDomainUpdate']) ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/domains') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-globe"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->admin_domains->menu ?></span>
                </div>
            </a>
        </li>
        <?php endif ?>

        <li class="<?= in_array(\Altum\Routing\Router::$controller, ['AdminPages', 'AdminPageCreate', 'AdminPageUpdate', 'AdminPagesCategoryCreate', 'AdminPagesCategoryUpdate']) ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/pages') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-file-alt"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->admin_pages->menu ?></span>
                </div>
            </a>
        </li>

        <li class="<?= in_array(\Altum\Routing\Router::$controller, ['AdminPlans', 'AdminPlanCreate', 'AdminPlanUpdate']) ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/plans') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-box-open"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->admin_plans->menu ?></span>
                </div>
            </a>
        </li>

        <?php if(in_array(settings()->license->type, ['Extended License', 'extended'])): ?>
        <li class="<?= in_array(\Altum\Routing\Router::$controller, ['AdminCodes', 'AdminCodeCreate', 'AdminCodeUpdate']) ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/codes') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-tags"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->admin_codes->menu ?></span>
                </div>
            </a>
        </li>

        <li class="<?= in_array(\Altum\Routing\Router::$controller, ['AdminTaxes', 'AdminTaxCreate', 'AdminTaxUpdate']) ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/taxes') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-receipt"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->admin_taxes->menu ?></span>
                </div>
            </a>
        </li>

        <li class="<?= \Altum\Routing\Router::$controller == 'AdminPayments' ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/payments') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-dollar-sign"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->admin_payments->menu ?></span>
                </div>
            </a>
        </li>

            <li class="<?= \Altum\Routing\Router::$controller == 'AdminAffiliatesWithdrawals' ? 'active' : null ?>">
                <a class="nav-link d-flex flex-row" href="<?= url('admin/affiliates-withdrawals') ?>">
                    <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-wallet"></i></div>
                    <div class="col">
                        <span class="d-inline"><?= language()->admin_affiliates_withdrawals->menu ?></span>
                    </div>
                </a>
            </li>
        <?php endif ?>

        <li class="<?= \Altum\Routing\Router::$controller == 'AdminStatistics' ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/statistics') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-chart-line"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->admin_statistics->menu ?></span>
                </div>
            </a>
        </li>

        <li class="<?= \Altum\Routing\Router::$controller == 'AdminApiDocumentation' ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/api-documentation') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-code"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->admin_api_documentation->menu ?></span>
                </div>
            </a>
        </li>

        <li class="<?= \Altum\Routing\Router::$controller == 'AdminPlugins' ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/plugins') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-puzzle-piece"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->admin_plugins->menu ?></span>
                </div>
            </a>
        </li>

        <li class="<?= \Altum\Routing\Router::$controller == 'AdminSettings' ? 'active' : null ?>">
            <a class="nav-link d-flex flex-row" href="<?= url('admin/settings') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-wrench"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->admin_settings->menu ?></span>
                </div>
            </a>
        </li>
    </ul>

    <hr />

    <ul class="admin-sidebar-links">
        <li>
            <a class="nav-link d-flex flex-row" target="_blank" href="<?= url('dashboard') ?>">
                <div class="col-1 d-flex align-items-center"><i class="fa fa-fw fa-sm fa-home"></i></div>
                <div class="col">
                    <span class="d-inline"><?= language()->global->menu->website ?></span>
                </div>
            </a>
        </li>

        <li class="dropdown">
            <a class="nav-link d-flex flex-row dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
                <div class="col-1 d-flex align-items-center"><img src="<?= get_gravatar($this->user->email) ?>" class="admin-avatar" /></div>
                <div class="col text-truncate">
                    <span class="d-inline"><?= $this->user->name ?></span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="<?= url('account') ?>"><i class="fa fa-fw fa-sm fa-wrench mr-1"></i> <?= language()->account->menu ?></a>
                <a class="dropdown-item" href="<?= url('logout') ?>"><i class="fa fa-fw fa-sm fa-sign-out-alt mr-1"></i> <?= language()->global->menu->logout ?></a>
            </div>
        </li>
    </ul>
</section>
