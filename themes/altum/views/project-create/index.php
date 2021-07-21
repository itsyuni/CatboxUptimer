<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <small>
            <ol class="custom-breadcrumbs">
                <li>
                    <a href="<?= url('projects') ?>"><?= language()->projects->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page"><?= language()->project_create->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <h1 class="h4 text-truncate mb-4"><?= language()->project_create->header ?></h1>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="name"><i class="fa fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= language()->projects->input->name ?></label>
                    <input type="text" id="name" name="name" class="form-control" value="" required="required" />
                </div>

                <div class="form-group">
                    <label for="color"><i class="fa fa-fw fa-palette fa-sm text-muted mr-1"></i> <?= language()->projects->input->color ?></label>
                    <input type="color" id="color" name="color" class="form-control" value="#000000" required="required" />
                    <small class="text-muted form-text"><?= language()->projects->input->color_help ?></small>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= language()->global->create ?></button>
            </form>

        </div>
    </div>
</div>
