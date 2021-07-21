<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['app_sub_menu'] ?>

    <h1 class="h4"><?= language()->account_delete->header ?></h1>
    <p class="text-muted"><?= language()->account_delete->subheader ?></p>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="current_password"><?= language()->account_delete->current_password ?></label>
                    <input type="password" id="current_password" name="current_password" class="form-control <?= \Altum\Alerts::has_field_errors('current_password') ? 'is-invalid' : null ?>" />
                    <?= \Altum\Alerts::output_field_error('current_password') ?>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-danger"><?= language()->global->delete ?></button>
            </form>

        </div>
    </div>

</div>
