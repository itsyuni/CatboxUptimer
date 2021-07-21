<?php defined('ALTUMCODE') || die() ?>
<!DOCTYPE html>
<html lang="<?= \Altum\Language::$language_code ?>">
    <head>
        <title><?= \Altum\Title::get() ?></title>
        <base href="<?= SITE_URL ?>">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <?php if(\Altum\Meta::$description): ?>
            <meta name="description" content="<?= \Altum\Meta::$description ?>" />
        <?php endif ?>
        <?php if(\Altum\Meta::$keywords): ?>
            <meta name="keywords" content="<?= \Altum\Meta::$keywords ?>" />
        <?php endif ?>

        <?php if(\Altum\Meta::$open_graph['url']): ?>
            <!-- Open Graph / Facebook -->
            <?php foreach(\Altum\Meta::$open_graph as $key => $value): ?>
                <?php if($value): ?>
                    <meta property="og:<?= $key ?>" content="<?= $value ?>" />
                <?php endif ?>
            <?php endforeach ?>
        <?php endif ?>

        <?php if(\Altum\Meta::$twitter['url']): ?>
            <!-- Twitter -->
            <?php foreach(\Altum\Meta::$open_graph as $key => $value): ?>
                <?php if($value): ?>
                    <meta property="twitter:<?= $key ?>" content="<?= $value ?>" />
                <?php endif ?>
            <?php endforeach ?>
        <?php endif ?>

        <?php if(isset($this->status_page) && $this->status_page_user->plan_settings->search_engine_block_is_enabled && !$this->status_page->is_se_visible): ?>
            <meta name="robots" content="noindex">
        <?php endif ?>

        <?php if(isset($this->status_page) && $this->status_page->favicon): ?>
            <link href="<?= UPLOADS_FULL_URL . 'status_pages_favicons/' . $this->status_page->favicon ?>" rel="shortcut icon" />
        <?php else: ?>

            <?php if(!empty(settings()->favicon)): ?>
                <link href="<?= UPLOADS_FULL_URL . 'favicon/' . settings()->favicon ?>" rel="shortcut icon" />
            <?php endif ?>

        <?php endif ?>

        <link href="<?= ASSETS_FULL_URL . 'css/' . \Altum\ThemeStyle::get_file() . '?v=' . PRODUCT_CODE ?>" id="css_theme_style" rel="stylesheet" media="screen,print">
        <?php foreach(['status-page-custom.css'] as $file): ?>
            <link href="<?= ASSETS_FULL_URL . 'css/' . $file . '?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
        <?php endforeach ?>

        <?= \Altum\Event::get_content('head') ?>

        <?php if(!empty(settings()->custom->head_js)): ?>
            <?= settings()->custom->head_js ?>
        <?php endif ?>

        <?php if(!empty(settings()->custom->head_css)): ?>
            <style><?= settings()->custom->head_css ?></style>
        <?php endif ?>

        <?php if(!empty($this->status_page->custom_css) && $this->status_page_user->plan_settings->custom_css_is_enabled): ?>
            <style><?= $this->status_page->custom_css ?></style>
        <?php endif ?>
    </head>

    <body class="<?= $this->status_page->theme ?>" data-theme-style="<?= \Altum\ThemeStyle::get() ?>">

        <?php require THEME_PATH . 'views/s/partials/ads_header.php' ?>

        <main class="altum-animate altum-animate-fill-none altum-animate-fade-in">

            <?= $this->views['content'] ?>

        </main>

        <?php require THEME_PATH . 'views/s/partials/ads_footer.php' ?>

        <?= $this->views['footer'] ?>

        <?= \Altum\Event::get_content('modals') ?>

        <?php require THEME_PATH . 'views/partials/js_global_variables.php' ?>

        <?php foreach(['functions.js'] as $file): ?>
            <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
        <?php endforeach ?>

        <?= \Altum\Event::get_content('javascript') ?>

        <?php if(!empty($this->status_page->custom_js) && $this->status_page_user->plan_settings->custom_js_is_enabled): ?>
            <?= $this->status_page->custom_js ?>
        <?php endif ?>
    </body>
</html>
