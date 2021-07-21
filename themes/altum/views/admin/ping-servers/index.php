<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-map-marked-alt text-primary-900 mr-2"></i> <?= language()->admin_ping_servers->header ?></h1>

    <div class="col-auto d-flex">
        <div>
            <a href="<?= url('admin/ping-server-create') ?>" class="btn btn-outline-primary"><i class="fa fa-fw fa-plus-circle"></i> <?= language()->admin_ping_server_create->menu ?></a>
        </div>

        <div class="ml-3">
            <div class="dropdown">
                <button type="button" class="btn <?= count($data->filters->get) ? 'btn-outline-primary' : 'btn-outline-secondary' ?> filters-button dropdown-toggle-simple" data-toggle="dropdown"><i class="fa fa-fw fa-sm fa-filter"></i></button>

                <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                    <div class="dropdown-header d-flex justify-content-between">
                        <span class="h6 m-0"><?= language()->global->filters->header ?></span>

                        <?php if(count($data->filters->get)): ?>
                            <a href="<?= url('admin/ping-servers') ?>" class="text-muted"><?= language()->global->filters->reset ?></a>
                        <?php endif ?>
                    </div>

                    <div class="dropdown-divider"></div>

                    <form action="" method="get" role="form">
                        <div class="form-group px-4">
                            <label for="search" class="small"><?= language()->global->filters->search ?></label>
                            <input type="search" name="search" id="search" class="form-control form-control-sm" value="<?= $data->filters->search ?>" />
                        </div>

                        <div class="form-group px-4">
                            <label for="search_by" class="small"><?= language()->global->filters->search_by ?></label>
                            <select name="search_by" id="search_by" class="form-control form-control-sm">
                                <option value="name" <?= $data->filters->search_by == 'name' ? 'selected="selected"' : null ?>><?= language()->admin_ping_servers->filters->search_by_name ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="is_enabled" class="small"><?= language()->global->filters->status ?></label>
                            <select name="is_enabled" id="is_enabled" class="form-control form-control-sm">
                                <option value=""><?= language()->global->filters->all ?></option>
                                <option value="1" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '1' ? 'selected="selected"' : null ?>><?= language()->global->active ?></option>
                                <option value="0" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '0' ? 'selected="selected"' : null ?>><?= language()->global->disabled ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="country" class="small"><?= language()->admin_ping_servers->filters->country_code ?></label>
                            <select name="country_code" id="country_code" class="form-control form-control-sm">
                                <option value=""><?= language()->global->filters->all ?></option>
                                <?php foreach(get_countries_array() as $country_code => $country_name): ?>
                                    <option value="<?= $country_code ?>" <?= isset($data->filters->filters['country_code']) && $data->filters->filters['country_code'] == $country_code ? 'selected="selected"' : null ?>><?= $country_name ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="order_by" class="small"><?= language()->global->filters->order_by ?></label>
                            <select name="order_by" id="order_by" class="form-control form-control-sm">
                                <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= language()->global->filters->order_by_datetime ?></option>
                                <option value="name" <?= $data->filters->order_by == 'name' ? 'selected="selected"' : null ?>><?= language()->admin_ping_servers->filters->order_by_name ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="order_type" class="small"><?= language()->global->filters->order_type ?></label>
                            <select name="order_type" id="order_type" class="form-control form-control-sm">
                                <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= language()->global->filters->order_type_asc ?></option>
                                <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= language()->global->filters->order_type_desc ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="results_per_page" class="small"><?= language()->global->filters->results_per_page ?></label>
                            <select name="results_per_page" id="results_per_page" class="form-control form-control-sm">
                                <?php foreach($data->filters->allowed_results_per_page as $key): ?>
                                    <option value="<?= $key ?>" <?= $data->filters->results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="form-group px-4 mt-4">
                            <button type="submit" class="btn btn-sm btn-primary btn-block"><?= language()->global->submit ?></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="table-responsive table-custom-container">
    <table class="table table-custom">
        <thead>
            <tr>
                <th><?= language()->admin_ping_servers->table->ping_server ?></th>
                <th><?= language()->admin_ping_servers->table->location ?></th>
                <th><?= language()->admin_ping_servers->table->is_enabled ?></th>
                <th><?= language()->admin_ping_servers->table->datetime ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($data->ping_servers as $row): ?>
        <tr>
            <td>
                <div class="d-flex flex-column">
                    <div>
                        <a href="<?= url('admin/ping-server-update/' . $row->ping_server_id) ?>"><?= $row->name ?></a>
                    </div>
                    <span class="text-muted"><?= $row->url ?></span>
                </div>
            </td>
            <td>
                <img src="<?= ASSETS_FULL_URL . 'images/countries/' . mb_strtolower($row->country_code) . '.svg' ?>" class="img-fluid icon-favicon mr-1" />
                <span><?= $row->city_name ?></span>
            </td>
            <td>
                <?php if($row->is_enabled == 0): ?>
                <span class="badge badge-pill badge-warning"><i class="fa fa-fw fa-eye-slash"></i> <?= language()->global->disabled ?>
                <?php elseif($row->is_enabled == 1): ?>
                <span class="badge badge-pill badge-success"><i class="fa fa-fw fa-check"></i> <?= language()->global->active ?>
                <?php endif ?>
            </td>
            <td>
                <span class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime) ?>">
                    <?= \Altum\Date::get($row->datetime, 2) ?>
                </span>
            </td>
            <td>
                <?= include_view(THEME_PATH . 'views/admin/ping-servers/admin_ping_server_dropdown_button.php', ['id' => $row->ping_server_id]) ?>
            </td>
        </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>

<div class="mt-3"><?= $data->pagination ?></div>
