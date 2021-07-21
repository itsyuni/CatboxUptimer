<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['app_sub_menu'] ?>

    <h1 class="h4"><?= language()->account_api->header ?></h1>
    <p class="text-muted"><?= sprintf(language()->account_api->subheader, '<a href="' . url('api-documentation') . '">', '</a>') ?></p>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="api_key"><?= language()->account_api->api_key ?></label>
                    <input type="text" id="api_key" name="api_key" value="<?= $this->user->api_key ?>" class="form-control" readonly="readonly" />
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-outline-secondary"><?= language()->account_api->button ?></button>
            </form>

        </div>
    </div>

</div>
