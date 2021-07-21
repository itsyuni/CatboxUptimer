<?php defined('ALTUMCODE') || die() ?>

<div class="mb-4">
    <div class="d-flex align-items-center">
        <h1 class="h3 mr-3"><i class="fa fa-fw fa-xs fa-map-marked-alt text-primary-900 mr-2"></i> <?= language()->admin_ping_server_update->header ?></h1>

        <?= include_view(THEME_PATH . 'views/admin/ping-servers/admin_ping_server_dropdown_button.php', ['id' => $data->ping_server->ping_server_id]) ?>
    </div>

    <p class="text-muted"><?= language()->admin_ping_server_create->subheader ?></p>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

            <div class="form-group">
                <label for="name"><?= language()->admin_ping_servers->main->name ?></label>
                <input id="name" type="text" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" name="name" value="<?= $data->ping_server->name ?>" required="required" />
                <?= \Altum\Alerts::output_field_error('name') ?>
            </div>

            <div class="form-group">
                <label for="url"><?= language()->admin_ping_servers->main->url ?></label>
                <input id="url" type="text" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" name="url" value="<?= $data->ping_server->url ?>" placeholder="<?= language()->admin_ping_servers->main->url_placeholder ?>" <?= $data->ping_server->ping_server_id == 1 ? 'readonly="readonly"' : 'required="required"' ?> />
                <?= \Altum\Alerts::output_field_error('url') ?>
            </div>

            <div class="form-group">
                <label for="country_code"><?= language()->admin_ping_servers->main->country_code ?></label>
                <select id="country_code" name="country_code" class="form-control form-control-lg">
                    <?php foreach(get_countries_array() as $country_code => $country_name): ?>
                        <option value="<?= $country_code ?>" <?= $data->ping_server->country_code == $country_code ? 'selected="selected"' : null ?>><?= $country_name ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="city_name"><?= language()->admin_ping_servers->main->city_name ?></label>
                <input id="city_name" type="text" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('city_name') ? 'is-invalid' : null ?>" name="city_name" value="<?= $data->ping_server->city_name ?>" required="required" />
                <?= \Altum\Alerts::output_field_error('city_name') ?>
                <small class="form-text text-muted"><?= language()->admin_ping_servers->main->city_name_help ?></small>
            </div>

            <div class="form-group">
                <label for="is_enabled"><?= language()->admin_ping_servers->main->is_enabled ?></label>
                <select id="is_enabled" name="is_enabled" class="form-control form-control-lg" <?= $data->ping_server->ping_server_id == 1 ? 'disabled="disabled"' : null ?>>
                    <option value="1" <?= $data->ping_server->is_enabled ? 'selected="selected"' : null ?>><?= language()->global->active ?></option>
                    <option value="0" <?= !$data->ping_server->is_enabled ? 'selected="selected"' : null ?>><?= language()->global->disabled ?></option>
                </select>
            </div>

            <div class="mt-4">
                <button type="submit" name="submit" class="btn btn-primary"><?= language()->global->update ?></button>
            </div>
        </form>

    </div>
</div>
