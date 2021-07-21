<?php defined('ALTUMCODE') || die() ?>

<?= \Altum\Alerts::output_alerts() ?>

<h1 class="h5"><?= language()->reset_password->header ?></h1>
<p class="text-muted"><?= language()->reset_password->subheader ?></p>

<form action="" method="post" class="mt-4" role="form">
    <input type="hidden" name="email" value="<?= $data->values['email'] ?>" class="form-control" />

    <div class="form-group">
        <label for="new_password"><?= language()->reset_password->new_password ?></label>
        <input id="new_password" type="password" name="new_password" class="form-control <?= \Altum\Alerts::has_field_errors('new_password') ? 'is-invalid' : null ?>" required="required" autofocus="autofocus" />
        <?= \Altum\Alerts::output_field_error('new_password') ?>
    </div>

    <div class="form-group">
        <label for="repeat_password"><?= language()->reset_password->repeat_password ?></label>
        <input id="repeat_password" type="password" name="repeat_password" class="form-control <?= \Altum\Alerts::has_field_errors('repeat_password') ? 'is-invalid' : null ?>" required="required" />
        <?= \Altum\Alerts::output_field_error('repeat_password') ?>
    </div>

    <div class="form-group mt-4">
        <button type="submit" name="submit" class="btn btn-primary btn-block my-1"><?= language()->reset_password->submit ?></button>
    </div>
</form>

<div class="mt-5 text-center">
    <a href="login" class="text-muted"><?= language()->reset_password->return ?></a>
</div>
