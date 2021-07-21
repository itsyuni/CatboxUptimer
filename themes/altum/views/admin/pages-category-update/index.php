<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-book text-primary-900 mr-2"></i> <?= language()->admin_pages_category_update->header ?></h1>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

            <div class="row">
                <div class="col-12 col-md-4">
                    <h2 class="h4"><?= language()->admin_pages_categories->main->header ?></h2>
                    <p class="text-muted"><?= language()->admin_pages_categories->main->subheader ?></p>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="url"><?= language()->admin_pages_categories->input->url ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?= SITE_URL . 'pages/' ?></span>
                            </div>

                            <input id="url" type="text" name="url" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" placeholder="<?= language()->admin_pages_categories->input->url_placeholder ?>" value="<?= $data->pages_category->url ?>" required="required" />
                            <?= \Altum\Alerts::output_field_error('url') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="title"><?= language()->admin_pages_categories->input->title ?></label>
                        <input id="title" type="text" name="title" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('title') ? 'is-invalid' : null ?>" value="<?= $data->pages_category->title ?>" required="required" />
                        <?= \Altum\Alerts::output_field_error('title') ?>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12 col-md-4">
                    <h2 class="h4"><?= language()->admin_pages_categories->secondary->header ?></h2>
                    <p class="text-muted"><?= language()->admin_pages_categories->secondary->subheader ?></p>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="description"><?= language()->admin_pages_categories->input->description ?></label>
                        <input id="description" type="text" name="description" class="form-control form-control-lg" value="<?= $data->pages_category->description ?>" />
                    </div>

                    <div class="form-group">
                        <label for="icon"><?= language()->admin_pages_categories->input->icon ?></label>
                        <input id="icon" type="text" name="icon" class="form-control form-control-lg" placeholder="<?= language()->admin_pages_categories->input->icon_placeholder ?>" value="<?= $data->pages_category->icon ?>" />
                        <small class="form-text text-muted"><?= language()->admin_pages_categories->input->icon_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="order"><?= language()->admin_pages_categories->input->order ?></label>
                        <input id="order" type="number" name="order" class="form-control form-control-lg" value="<?= $data->pages_category->order ?>" />
                        <small class="form-text text-muted"><?= language()->admin_pages_categories->input->order_help ?></small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-4"></div>

                <div class="col">
                    <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= language()->global->update ?></button>
                </div>
            </div>
        </form>

    </div>
</div>
