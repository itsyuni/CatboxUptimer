<?php defined('ALTUMCODE') || die() ?>

<?= \Altum\Alerts::output_alerts() ?>

<h1 class="h5"><?= sprintf(language()->login->header, settings()->title) ?></h1>

<form action="" method="post" class="mt-4" role="form">
    <div class="form-group">
        <label for="email"><?= language()->login->form->email ?></label>
        <input id="email" type="text" name="email" class="form-control <?= \Altum\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" value="<?= $data->values['email'] ?>" required="required" autofocus="autofocus" />
        <?= \Altum\Alerts::output_field_error('email') ?>
    </div>

    <div class="form-group">
        <label for="password"><?= language()->login->form->password ?></label>
        <input id="password" type="password" name="password" class="form-control <?= \Altum\Alerts::has_field_errors('password') ? 'is-invalid' : null ?>" value="<?= $data->user ? $data->values['password'] : null ?>" required="required" />
        <?= \Altum\Alerts::output_field_error('password') ?>
    </div>

    <?php if($data->user && $data->user->twofa_secret && $data->user->active): ?>
        <div class="form-group">
            <label for="twofa_token"><?= language()->login->form->twofa_token ?></label>
            <input id="twofa_token" type="text" name="twofa_token" class="form-control <?= \Altum\Alerts::has_field_errors('twofa_token') ? 'is-invalid' : null ?>" required="required" autocomplete="off" />
            <?= \Altum\Alerts::output_field_error('twofa_token') ?>
        </div>
    <?php endif ?>

    <?php if(settings()->captcha->login_is_enabled): ?>
    <div class="form-group">
        <?php $data->captcha->display() ?>
    </div>
    <?php endif ?>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" name="rememberme" class="custom-control-input" id="rememberme">
            <label class="custom-control-label" for="rememberme"><small class="text-muted"><?= language()->login->form->remember_me ?></small></label>
        </div>

        <small class="text-muted"><a href="lost-password" class="text-muted"><?= language()->login->display->lost_password ?></a> / <a href="resend-activation" class="text-muted" role="button"><?= language()->login->display->resend_activation ?></a></small>
    </div>

    <div class="form-group mt-4">
        <button type="submit" name="submit" class="btn btn-primary btn-block my-1"><?= language()->login->form->login ?></button>
    </div>

    <div class="row">
        <?php if(settings()->facebook->is_enabled): ?>
            <div class="col-sm mt-1">
                <a href="<?= $data->facebook_login_url ?>" class="btn btn-light btn-block"><?= sprintf(language()->login->display->facebook, "<i class=\"fab fa-fw fa-facebook\"></i>") ?></a>
            </div>
        <?php endif ?>
    </div>
</form>


<?php if(settings()->register_is_enabled): ?>
    <div class="mt-5 text-center text-muted">
        <?= sprintf(language()->login->display->register, '<a href="' . url('register') . '" class="font-weight-bold">' . language()->login->display->register_help . '</a>') ?></a>
    </div>
<?php endif ?>

