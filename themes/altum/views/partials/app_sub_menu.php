<?php defined('ALTUMCODE') || die() ?>

<div class="d-lg-none">
    <select name="app_sub_menu" class="form-control mb-4">
        <option value="<?= url('account') ?>" <?= \Altum\Routing\Router::$controller_key == 'account' ? 'selected="selected"' : null ?>><?= language()->account->menu ?></option>
        <option value="<?= url('account-plan') ?>" <?= \Altum\Routing\Router::$controller_key == 'account-plan' ? 'selected="selected"' : null ?>><?= language()->account_plan->menu ?></option>
        <?php if(settings()->payment->is_enabled): ?>
            <option value="<?= url('account-payments') ?>" <?= \Altum\Routing\Router::$controller_key == 'account-payments' ? 'selected="selected"' : null ?>><?= language()->account_payments->menu ?></option>

            <?php if(settings()->affiliate->is_enabled): ?>
                <option value="<?= url('referrals') ?>" <?= \Altum\Routing\Router::$controller_key == 'referrals' ? 'selected="selected"' : null ?>><?= language()->referrals->menu ?></option>
            <?php endif ?>
        <?php endif ?>
        <option value="<?= url('account-logs') ?>" <?= \Altum\Routing\Router::$controller_key == 'account-logs' ? 'selected="selected"' : null ?>><?= language()->account_logs->menu ?></option>
        <option value="<?= url('account-api') ?>" <?= \Altum\Routing\Router::$controller_key == 'account-api' ? 'selected="selected"' : null ?>><?= language()->account_api->menu ?></option>
        <option value="<?= url('account-delete') ?>" <?= \Altum\Routing\Router::$controller_key == 'account-delete' ? 'selected="selected"' : null ?>><?= language()->account_delete->menu ?></option>
    </select>
</div>

<?php ob_start() ?>
<script>
    document.querySelector('select[name="app_sub_menu"]').addEventListener('change', event => {
        window.location = document.querySelector('select[name="app_sub_menu"]').value;
    })
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<ul class="app-sub-navbar-ul">
    <li class="nav-item">
        <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'account' ? 'active' : null ?>" href="<?= url('account') ?>">
            <i class="fa fa-fw fa-sm fa-wrench mr-1"></i> <?= language()->account->menu ?>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'account-plan' ? 'active' : null ?>" href="<?= url('account-plan') ?>">
            <i class="fa fa-fw fa-sm fa-box-open mr-1"></i> <?= language()->account_plan->menu ?>
        </a>
    </li>

    <?php if(settings()->payment->is_enabled): ?>
        <li class="nav-item">
            <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'account-payments' ? 'active' : null ?>" href="<?= url('account-payments') ?>">
                <i class="fa fa-fw fa-sm fa-dollar-sign mr-1"></i> <?= language()->account_payments->menu ?>
            </a>
        </li>

        <?php if(settings()->affiliate->is_enabled): ?>
            <li class="nav-item">
                <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'referrals' ? 'active' : null ?>" href="<?= url('referrals') ?>">
                    <i class="fa fa-fw fa-sm fa-wallet mr-1"></i> <?= language()->referrals->menu ?>
                </a>
            </li>
        <?php endif ?>
    <?php endif ?>

    <li class="nav-item">
        <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'account-logs' ? 'active' : null ?>" href="<?= url('account-logs') ?>">
            <i class="fa fa-fw fa-sm fa-scroll mr-1"></i> <?= language()->account_logs->menu ?>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'account-api' ? 'active' : null ?>" href="<?= url('account-api') ?>">
            <i class="fa fa-fw fa-sm fa-code mr-1"></i> <?= language()->account_api->menu ?>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'account-delete' ? 'active' : null ?>" href="<?= url('account-delete') ?>">
            <i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= language()->account_delete->menu ?>
        </a>
    </li>
</ul>

