<?php defined('ALTUMCODE') || die() ?>

<p><?= sprintf($data->language->cron->is_ok->p1, $data->row->name) ?></p>

<div>
    <table>
        <tbody>
            <tr>
                <th><?= $data->language->cron->is_ok->start_datetime ?></th>
                <td>
                    <span class="text-muted">
                        <?= \Altum\Date::get($data->heartbeat_incident->start_datetime) ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th><?= $data->language->cron->is_ok->end_datetime ?></th>
                <td>
                    <span class="text-muted">
                        <?= \Altum\Date::get($data->heartbeat_incident->end_datetime) ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th><?= $data->language->cron->is_ok->length ?></th>
                <td>
                    <span class="text-muted">
                        <?= \Altum\Date::get_elapsed_time($data->heartbeat_incident->start_datetime, $data->heartbeat_incident->end_datetime) ?>
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div style="margin-top: 30px">
    <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
        <tbody>
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <td>
                            <a href="<?= url('heartbeat/' . $data->row->heartbeat_id) ?>">
                                <?= $data->language->cron->is_ok->button ?>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<p>
    <small class="text-muted"><?= sprintf($data->language->cron->is_ok->notice, '<a href="' . url('heartbeat-update/' . $data->row->heartbeat_id) . '">', '</a>') ?></small>
</p>
