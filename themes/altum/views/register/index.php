<?php defined('ALTUMCODE') || die() ?>

<?= \Altum\Alerts::output_alerts() ?>

<h1 class="h5"><?= language()->register->header ?></h1>

<form action="" method="post" class="mt-4" role="form">
    <div class="form-group">
        <label for="name"><?= language()->register->form->name ?></label>
        <input id="name" type="text" name="name" class="form-control <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->values['name'] ?>" required="required" autofocus="autofocus" />
        <?= \Altum\Alerts::output_field_error('name') ?>
    </div>

    <div class="form-group">
        <label for="email"><?= language()->register->form->email ?></label>
        <input id="email" type="text" name="email" class="form-control <?= \Altum\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" value="<?= $data->values['email'] ?>" required="required" />
        <?= \Altum\Alerts::output_field_error('email') ?>
    </div>

    <div class="form-group">
        <label for="password"><?= language()->register->form->password ?></label>
        <input id="password" type="password" name="password" class="form-control <?= \Altum\Alerts::has_field_errors('password') ? 'is-invalid' : null ?>" value="<?= $data->values['password'] ?>" required="required" />
        <?= \Altum\Alerts::output_field_error('password') ?>
    </div>

    <?php if(settings()->captcha->register_is_enabled): ?>
        <div class="form-group">
            <?php $data->captcha->display() ?>
        </div>
    <?php endif ?>

    <div class="custom-control custom-checkbox">
        <input type="checkbox" name="accept" class="custom-control-input" id="accept" required="required">
        <label class="custom-control-label" for="accept">
            <small class="text-muted">
                <?= sprintf(
                    language()->register->form->accept,
                    '<a href="' . settings()->terms_and_conditions_url . '" target="_blank">' . language()->global->terms_and_conditions . '</a>',
                    '<a href="' . settings()->privacy_policy_url . '" target="_blank">' . language()->global->privacy_policy . '</a>'
                ) ?>
            </small>
        </label>
    </div>

    <div class="form-group mt-4">
        <button type="submit" name="submit" class="btn btn-primary btn-block"><?= language()->register->form->register ?></button>
    </div>

    <div class="row">
        <?php if(settings()->facebook->is_enabled): ?>
            <div class="col-sm mt-1">
                <a href="<?= $data->facebook_login_url ?>" class="btn btn-light btn-block"><?= sprintf(language()->login->display->facebook, "<i class=\"fab fa-fw fa-facebook\"></i>") ?></a>
            </div>
        <?php endif ?>
    </div>
</form>


<div class="mt-5 text-center text-muted">
    <?= sprintf(language()->register->display->login, '<a href="' . url('login') . '" class="font-weight-bold">' . language()->register->display->login_help . '</a>') ?></a>
</div>
