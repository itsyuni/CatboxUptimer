<?php defined('ALTUMCODE') || die() ?>

<div class="container my-8">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">

            <div class="mb-4 d-flex">
                <div>
                    <h1 class="h3"><?= language()->s_status_page->password->header  ?></h1>
                    <span class="text-muted">
                        <?= language()->s_status_page->password->subheader ?>
                    </span>
                </div>
            </div>

            <?= \Altum\Alerts::output_alerts() ?>

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="password"><?= language()->s_status_page->password->input ?></label>
                    <input type="password" id="password" name="password" value="" class="form-control <?= \Altum\Alerts::has_field_errors('password') ? 'is-invalid' : null ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('password') ?>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= language()->global->submit ?></button>
            </form>

        </div>
    </div>
</div>


