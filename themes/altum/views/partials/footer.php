<?php defined('ALTUMCODE') || die() ?>

<div class="row">

    <div class="col-12 col-sm-4 mb-3 mt-sm-0 d-flex flex-column">
        <a class="navbar-brand" href="<?= url() ?>">
            <?= settings()->title ?>
        </a>

        <div class="mb-3 text-muted"><?= sprintf(language()->global->footer->copyright, date('Y'), settings()->title) ?></div>

        <?php if(count(\Altum\Language::$languages) > 1): ?>
            <div class="dropdown mb-2">
                <a class="dropdown-toggle clickable" id="language_switch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= language()->global->language ?></a>

                <div class="dropdown-menu" aria-labelledby="language_switch">
                    <h6 class="dropdown-header"><?= language()->global->choose_language ?></h6>
                    <?php foreach(\Altum\Language::$languages as $language_code => $language_name): ?>
                        <a class="dropdown-item" href="<?= SITE_URL . $language_code . '/' . \Altum\Routing\Router::$original_request . '?set_language=' . $language_name ?>">
                            <?php if($language_name == \Altum\Language::$language): ?>
                                <i class="fa fa-fw fa-sm fa-check mr-1 text-success"></i>
                            <?php else: ?>
                                <i class="fa fa-fw fa-sm fa-circle-notch mr-1 text-muted"></i>
                            <?php endif ?>

                            <?= $language_name ?>
                        </a>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endif ?>

        <?php if(count(\Altum\ThemeStyle::$themes) > 1): ?>
            <div class="mb-2">
                <a href="#" data-choose-theme-style="dark" class="<?= \Altum\ThemeStyle::get() == 'dark' ? 'd-none' : null ?>">
                    <i class="fa fa-fw fa-sm fa-moon text-muted mr-1"></i> <?= sprintf(language()->global->theme_style, language()->global->theme_style_dark) ?>
                </a>
                <a href="#" data-choose-theme-style="light" class="<?= \Altum\ThemeStyle::get() == 'light' ? 'd-none' : null ?>">
                    <i class="fa fa-fw fa-sm fa-sun text-muted mr-1"></i> <?= sprintf(language()->global->theme_style, language()->global->theme_style_light) ?>
                </a>
            </div>

        <?php ob_start() ?>
            <script>
                'use strict';

                document.querySelectorAll('[data-choose-theme-style]').forEach(theme => {

                    theme.addEventListener('click', event => {

                        let chosen_theme_style = event.currentTarget.getAttribute('data-choose-theme-style');

                        /* Set a cookie with the new theme style */
                        set_cookie('theme_style', chosen_theme_style, 30, <?= json_encode(COOKIE_PATH) ?>);

                        /* Change the css and button on the page */
                        let css = document.querySelector(`#css_theme_style`);

                        document.querySelector(`[data-theme-style]`).setAttribute('data-theme-style', chosen_theme_style);

                        switch(chosen_theme_style) {
                            case 'dark':
                                css.setAttribute('href', <?= json_encode(ASSETS_FULL_URL . 'css/' . \Altum\ThemeStyle::$themes['dark']['file'] . '?v=' . PRODUCT_CODE) ?>);
                                document.querySelector(`[data-choose-theme-style="dark"]`).classList.add('d-none');
                                document.querySelector(`[data-choose-theme-style="light"]`).classList.remove('d-none');
                                break;

                            case 'light':
                                css.setAttribute('href', <?= json_encode(ASSETS_FULL_URL . 'css/' . \Altum\ThemeStyle::$themes['light']['file'] . '?v=' . PRODUCT_CODE) ?>);
                                document.querySelector(`[data-choose-theme-style="dark"]`).classList.remove('d-none');
                                document.querySelector(`[data-choose-theme-style="light"]`).classList.add('d-none');
                                break;
                        }

                        event.preventDefault();
                    });

                })
            </script>
            <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

        <?php endif ?>
    </div>


    <?php if(count($data->pages)): ?>
    <div class="col-12 col-sm-4 mt-3 mt-sm-0">
        <div class="mb-2 font-weight-bold text-uppercase"><a href="<?= url('pages') ?>"><?= language()->global->footer->pages ?></a></div>

        <ul class="list-style-none">
        <?php foreach($data->pages as $row): ?>
            <li><a href="<?= $row->url ?>" target="<?= $row->target ?>"><?= $row->title ?></a></li>
        <?php endforeach ?>
        </ul>
    </div>
    <?php endif ?>

    <?php if(!empty(settings()->socials->facebook) || !empty(settings()->socials->twitter) || !empty(settings()->socials->instagram) || !empty(settings()->socials->youtube)): ?>
    <div class="col-12 col-sm-4 mt-3 mt-sm-0">
        <div class="mb-2 font-weight-bold text-uppercase"><?= language()->global->footer->social ?></div>

        <ul class="list-style-none">
            <?php foreach(require APP_PATH . 'includes/admin_socials.php' as $key => $value): ?>

                <?php if(isset(settings()->socials->{$key}) && !empty(settings()->socials->{$key})): ?>
                    <li><a href="<?= sprintf($value['format'], settings()->socials->{$key}) ?>" target="_blank"><i class="<?= $value['icon'] ?> fa-fw"></i> <?= $value['name'] ?></a></li>
                <?php endif ?>

            <?php endforeach ?>
        </ul>
    </div>
    <?php endif ?>

</div>
