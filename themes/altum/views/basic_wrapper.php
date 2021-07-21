<?php defined('ALTUMCODE') || die() ?>
<!DOCTYPE html>
<html lang="<?= \Altum\Language::$language_code ?>" class="h-100">
    <head>
        <title><?= \Altum\Title::get() ?></title>
        <base href="<?= SITE_URL; ?>">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta http-equiv="content-language" content="<?= \Altum\Language::$language_code  ?>" />

        <link rel="alternate" href="<?= SITE_URL . \Altum\Routing\Router::$original_request ?>" hreflang="x-default" />
        <?php if(count(\Altum\Language::$languages) > 1): ?>
            <?php foreach(\Altum\Language::$languages as $language_code => $language_name): ?>
                <?php if(settings()->default_language != $language_name): ?>
                    <link rel="alternate" href="<?= SITE_URL . $language_code . '/' . \Altum\Routing\Router::$original_request ?>" hreflang="<?= $language_code ?>" />
                <?php endif ?>
            <?php endforeach ?>
        <?php endif ?>

        <?php if(!empty(settings()->favicon)): ?>
            <link href="<?= UPLOADS_FULL_URL . 'favicon/' . settings()->favicon ?>" rel="shortcut icon" />
        <?php endif ?>

        <link href="<?= ASSETS_FULL_URL . 'css/' . \Altum\ThemeStyle::get_file() . '?v=' . PRODUCT_CODE ?>" id="css_theme_style" rel="stylesheet" media="screen,print">
        <?php foreach(['custom.css'] as $file): ?>
            <link href="<?= ASSETS_FULL_URL . 'css/' . $file . '?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen">
        <?php endforeach ?>

        <?= \Altum\Event::get_content('head') ?>

        <?php if(!empty(settings()->custom->head_js)): ?>
            <?= settings()->custom->head_js ?>
        <?php endif ?>

        <?php if(!empty(settings()->custom->head_css)): ?>
            <style><?= settings()->custom->head_css ?></style>
        <?php endif ?>
    </head>

    <body class="bg-gray-50 <?= in_array(\Altum\Routing\Router::$controller_key, ['login', 'register']) ? \Altum\Routing\Router::$controller_key . '-background' : null ?>" data-theme-style="<?= \Altum\ThemeStyle::get() ?>">

        <main class="d-flex h-100">
            <section class="flex-grow-1 d-flex flex-column justify-content-lg-center py-5 py-lg-0">
                <div class="container">

                    <div class="d-flex flex-column align-items-center">
                        <div class="col-xs-12 col-md-10 col-lg-7 col-xl-6">

                            <div class="card border-0">
                                <div class="card-body p-5">

                                    <div class="mb-6 text-center">
                                        <a href="<?= url() ?>" class="text-decoration-none text-dark">
                                            <?php if(settings()->logo != ''): ?>
                                                <img src="<?= UPLOADS_FULL_URL . 'logo/' . settings()->logo ?>" class="img-fluid navbar-logo" alt="<?= language()->global->accessibility->logo_alt ?>" />
                                            <?php else: ?>
                                                <span class="h3"><?= settings()->title ?></span>
                                            <?php endif ?>
                                        </a>
                                    </div>

                                    <?= $this->views['content'] ?>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </section>
        </main>

        <?php if(\Altum\Routing\Router::$controller_key != 'index'): ?>
            <?php require THEME_PATH . 'views/partials/ads_footer.php' ?>
        <?php endif ?>

        <?= \Altum\Event::get_content('modals') ?>

        <?php require THEME_PATH . 'views/partials/js_global_variables.php' ?>

        <?php foreach(['libraries/jquery.slim.min.js', 'libraries/popper.min.js', 'libraries/bootstrap.min.js', 'main.js', 'functions.js', 'libraries/fontawesome.min.js', 'libraries/fontawesome-solid.min.js', 'libraries/fontawesome-brands.modified.js'] as $file): ?>
            <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
        <?php endforeach ?>

        <?= \Altum\Event::get_content('javascript') ?>
    </body>
</html>
